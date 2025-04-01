<?php

use Framework\Dispatcher;
use Framework\Router;

spl_autoload_register(function ($class) {
    require 'src/' . str_replace('\\', '/', $class) . '.php';
});

$router = new Router();
$router->add("/admin/{controller}/{action}", ['namespace' => 'Admin']);
$router->add("{title}/{id:\d+}/{page:\d+}", ['controller' => 'products', 'action' => 'showPage']);
$router->add('/products/{slug:[\w-]+}', ['controller' => 'products', 'action' => 'show']);
$router->add('/{controller}/{id:\d+}/{action}');
$router->add('/', ['controller' => 'home', 'action' => 'index']);
$router->add('/home/index', ['controller' => 'home', 'action' => 'index']);
$router->add('/products', ['controller' => 'products', 'action' => 'index']);
$router->add('/{controller}/{action}');

// uri without query string
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$dispatcher = new Dispatcher($router);
$dispatcher->handle($path);
