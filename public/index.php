<?php

require dirname(__DIR__).'/vendor/autoload.php';

use App\Controllers\NotFoundController;

$request = $_SERVER['REQUEST_URI'];

$controller = "App\\Controllers\\";

switch ($request) {
    default:
        $controller .= "NotFound";
        break;
}

$controller .= "Controller";

$controllerInstance = new $controller(dirname(__DIR__).DIRECTORY_SEPARATOR."views");

$controllerInstance->echoHTML();

