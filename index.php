<?php

require './src/router.php';

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
// $items = array_values(array_filter(explode('/', $path)));
// list($controller, $action) = $items + ['home', 'index'];

require "src/controllers/{$controller}.php";
$objController = new $controller();
$objController->$action();
