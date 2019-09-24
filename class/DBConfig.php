<?php

namespace App;

class DBConfig
{
    public $host;
    public $port;
    public $database;
    public $username;
    public $password;
    public function __construct(array $params)
    {
        foreach ($params as $param => $value) {
            if (property_exists(static::class, $param)) {
                $this->$param = $value;
            }
        }
    }
}