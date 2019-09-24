<?php

namespace App;

use ReflectionClass;

class ServiceLocator
{
    private static $instance;
    private static $services;

    private function __construct()
    {
        static::initServices();
    }

    private function initServices()
    {
        $configs = include dirname(__DIR__) . '../config/config.php';
        foreach ($configs as $i => $config) {
            if (!isset($config['class'])) throw new \InvalidArgumentException('Не задан class в config.php(' . $i . ')');
            if (!isset($config['params'])) throw new \InvalidArgumentException('Не заданы параметры в config.php(' . $i . ')');
            $this->initService($config);
        }
    }

    private function initService($config)
    {
        $className = $config['class'];
        $serviceName = $config['name'];
        try {
            $reflection = new ReflectionClass($className);
        } catch (\ReflectionException $e) {
            return;
        }
        $params = $reflection->getConstructor()->getParameters();
        //перепилить для несколкьких параметров
        foreach ($params AS $param) {
            $configuratorClassName = $param->getClass()->name;
            $configurator = new $configuratorClassName($config['params']);
            $service = new $className($configurator);
            $this->addService($serviceName, $service);
        }
    }

    private function addService($name, $service)
    {
        static::$services[$name] = $service;
    }

    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public static function getService($serviceName)
    {
        if (isset(static::$services[$serviceName])) {
            return static::$services[$serviceName];
        }
        throw new \InvalidArgumentException('Нет сервиса с таким именем');
    }

}