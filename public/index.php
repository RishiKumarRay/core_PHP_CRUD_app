<?php

require dirname(__DIR__).'/vendor/autoload.php';

use App\Controllers\NotFoundController;
use App\IOC\Resolver;

$resolver = new Resolver();

$request = $_SERVER['REQUEST_URI'];

$controller = "App\\Controllers\\";
$controller .= match ($request) {
    "/contact" => "ContactForm",
    default => "NotFound",
};
$controller .= "Controller";

$controllerInstance = $resolver->resolveClass($controller, [dirname(__DIR__) . DIRECTORY_SEPARATOR . "views"]);
if (is_null($controllerInstance)) {
    echo "failed";
} else {
    $controllerInstance->outputView();
}
