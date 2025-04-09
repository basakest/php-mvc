<?php

declare(strict_types = 1);

use App\Database;
use Framework\Dispatcher;
use Framework\Exceptions\PageNotFoundException;
use Framework\Router;
use Framework\Container;

set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

set_exception_handler(function (Throwable $exception) {
    $template = '500.php';
    if ($exception instanceof PageNotFoundException) {
        $template = '404.php';
        http_response_code(404);
    } else {
        http_response_code(500);
    }

    $showErrors = false;
    if ($showErrors) {
        ini_set('display_errors', '1');
    } else {
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
        require "views/$template";
    }
    throw $exception;
});



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
if ( ! $path) {
    throw new UnexpectedValueException("Malformed URI: '{$_SERVER['REQUEST_URI']}'");
}

$container = new Container();
// 如果 set 的第二个参数是一个 object, 那么它的 __construct 会被调用
// 但并不是所有的请求都需要执行其中的逻辑
// $container->set(Database::class, new Database('127.0.0.1', 'mvc', 'root', ''));
$container->set(Database::class, function () {
    return new Database('127.0.0.1', 'mvc', 'root', '');
});
$dispatcher = new Dispatcher($router, $container);
$dispatcher->handle($path);
