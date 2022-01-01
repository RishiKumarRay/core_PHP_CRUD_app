<?php

namespace App;

use PDO;
use PDOException;

final class DBConnection {

    private ?PDO $_connection = null;
    private static $_instance = null;

    private function __construct() {
        // TODO also support MongoDB + corresponding ORM
        // check that PDO extension is enabled
        if( in_array ('pdo_mysql', get_loaded_extensions())) {
            $dsn = "mysql:host=".$_ENV['MYSQL_HOST'].";dbname=".$_ENV['MYSQL_DB'].";charset=utf8";
            $opt = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
            try {
                $this->_connection = new PDO($dsn, $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $opt);
            } catch (PDOException $pdoe) {
                // TODO log connection errors in local or remote infrastructure
                echo $pdoe->getMessage(); // TODO hide message in prod.
            }
        }
    }

    public static function getInstance(): DBConnection 
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new DBConnection();  
        }
        return self::$_instance;
    }

    public function getConnection() {
        return $this->_connection;
    }

}