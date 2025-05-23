<?php

use Framework\Router;

$router = new Router();
$router->add("/admin/{controller}/{action}", ['namespace' => 'Admin']);
$router->add("{title}/{id:\d+}/{page:\d+}", ['controller' => 'products', 'action' => 'showPage']);
// $router->add('/products/{slug:[\w-]+}', ['controller' => 'products', 'action' => 'show']);
// $router->add('/products/{id:\d+}', ['controller' => 'products', 'action' => 'show']);
$router->add('/{controller}/{id:\d+}/show', ['action' => 'show', 'middleware' => 'deny|message|message']);
$router->add('/{controller}/{id:\d+}/edit', ['action' => 'edit']);
$router->add('/{controller}/{id:\d+}/update', ['action' => 'update']);
$router->add('/{controller}/{id:\d+}/delete', ['action' => 'delete']);
$router->add('/{controller}/{id:\d+}/destroy', ['action' => 'destroy', 'method' => 'POST']);
// $router->add('/{controller}/{id:\d+}/{action}');
$router->add('/', ['controller' => 'home', 'action' => 'index']);
$router->add('/home/index', ['controller' => 'home', 'action' => 'index']);
$router->add('/products', ['controller' => 'products', 'action' => 'index']);
$router->add('/{controller}/{action}');

return $router;
