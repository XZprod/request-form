<?php

namespace App;

use Exception;
use PDO;

class DB
{
    const DEFAULT_PORT = 3306;
    private $connect;

    public function __construct(DBConfig $config)
    {
        $host = $config->host;
        $database = $config->database;
        $port = $config->port ?? static::DEFAULT_PORT;
        try {
            $this->connect = new PDO("mysql:host=$host;port=$port;dbname=$database", $config->username, $config->password);
        } catch (Exception $e) {
            print "Не удалось подключиться к БД. " . $e->getMessage();
        }
    }

    /**
     * @return PDO
     */
    public function getConnect()
    {
        return $this->connect;
    }

    /**
     * @param $query string
     * @return void
     */
    public function exec($query)
    {
        $result = $this->connect->exec($query);
        if (!$result) {
            $error = implode('\n', $this->connect->errorInfo());
            throw new \PDOException('Ошибка запроса: ' . $error);
        }
    }

    /**
     * @param $query string
     * @return array
     */
    public function fetch($query)
    {
        $result = $this->connect->query($query, PDO::FETCH_ASSOC)->fetchAll();
        return $result;
    }
}