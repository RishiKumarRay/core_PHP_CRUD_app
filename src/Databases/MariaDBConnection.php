<?php

namespace App\Databases;

use PDO;
use PDOException;

final class MariaDBConnection implements DBConnection {

    private ?PDO $_connection = null;
    private static $_instance = null;

    private function __construct() {
        // check that PDO extension is enabled
        if( in_array ('pdo_mysql', get_loaded_extensions())) {
            $dsn = "mysql:host=".$_ENV['MYSQL_HOST'].";dbname=".$_ENV['MYSQL_DB'].";charset=utf8";
            $opt = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
            try {
                $this->_connection = new PDO($dsn, $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $opt);
            } catch (PDOException $pdoe) {
                echo $pdoe->getMessage();
            }
        }
    }

    public static function getSingletonInstance(): MariaDBConnection
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new MariaDBConnection();
        }
        return self::$_instance;
    }

}