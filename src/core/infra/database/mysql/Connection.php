<?php

namespace plugse\server\core\infra\database\mysql;

use PDO;
use plugse\server\core\infra\database\connection\DbSettings;

class Connection
{
    private static ?PDO $instance = null;

    public function __construct()
    {
    }

    public function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function getInstance(array $settings): PDO
    {
        if (self::$instance === null) {
            self::setConnection($settings);
        }

        return self::$instance;
    }
    
    private static function setConnection(array $settings)
    {
        $dsn = "mysql:host={$settings['host']};dbname={$settings['name']}";
        self::$instance = new PDO(
            $dsn, $settings['user'], $settings['password'],
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]
        );
    }
}
