<?php

namespace Src\Model;

class Database
{
    public static function getConnection(): \PDO
    {
        $config = require __DIR__ . '/../../config/database.php';
        return new \PDO($config['dsn'], $config['user'], $config['password']);
    }
}
