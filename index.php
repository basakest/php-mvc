<?php

use Framework\Router;

spl_autoload_register(function ($class) {
    require 'src/' . str_replace('\\', '/', $class) . '.php';
});

$router = new Router();
$router->add('/', ['controller' => 'home', 'action' => 'index']);
$router->add('/home/index', ['controller' => 'home', 'action' => 'index']);
$router->add('/products', ['controller' => 'products', 'action' => 'index']);

// uri without query string
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$items = $router->match($path);
if ( ! $items) {
    exit('no matched route');
}
extract($items);

$objController = new ('App\\Controllers\\' . ucfirst($controller));
$objController->$action();
