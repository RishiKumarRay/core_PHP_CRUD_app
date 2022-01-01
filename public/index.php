<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use App\DBConnection;

$db = DBConnection::getInstance();

echo "app' works".PHP_EOL;