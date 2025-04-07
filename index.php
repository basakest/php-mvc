<?php

declare(strict_types = 1);

use App\Database;
use Framework\Dispatcher;
use Framework\Router;
use Framework\Container;

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

$container = new Container();
// 如果 set 的第二个参数是一个 object, 那么它的 __construct 会被调用
// 但并不是所有的请求都需要执行其中的逻辑
// $container->set(Database::class, new Database('127.0.0.1', 'mvc', 'root', ''));
$container->set(Database::class, function () {
    return new Database('127.0.0.1', 'mvc', 'root', '');
});
$dispatcher = new Dispatcher($router, $container);
$dispatcher->handle($path);
