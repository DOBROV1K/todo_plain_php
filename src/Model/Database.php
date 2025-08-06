<?php

namespace Src\Model;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Src\Logger\Logger;

class Database
{
    /**
     * @return Connection
     */
    public static function getConnection(): Connection
    {
        $config = require __DIR__ . '/../../config/database.php';

        $connectionParams = [
            'driver'   => $config['driver'],
            'host'     => $config['host'],
            'port'     => $config['port'] ?? 3306,
            'dbname'   => $config['dbname'],
            'user'     => $config['user'],
            'password' => $config['password'],
            'charset'  => $config['charset'] ?? 'utf8mb4',
        ];
        
        $logger = Logger::getInstance();
        $logger->write("DB CONNECT: " . json_encode($connectionParams));

        return DriverManager::getConnection($connectionParams);
    }
}
