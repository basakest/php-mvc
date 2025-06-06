<?php

declare(strict_types = 1);

define('ROOT_PATH', dirname(__DIR__));

use Framework\Dispatcher;
use Framework\Dotenv;
use Framework\Request;

spl_autoload_register(function ($class) {
    require ROOT_PATH . '/src/' . str_replace('\\', '/', $class) . '.php';
});

$dotEnv = new Dotenv();
$dotEnv->load(ROOT_PATH . '/.env');

set_error_handler(['Framework\ErrorHandler', 'handleError']);
set_exception_handler(['Framework\ErrorHandler', 'handleException']);

$router = require ROOT_PATH . '/config/routes.php';
$container = require ROOT_PATH . '/config/services.php';

$middlewareAliasMap = require ROOT_PATH . '/config/middleware.php';
$dispatcher = new Dispatcher($router, $container, $middlewareAliasMap);
$request = Request::createFromGlobals();
$response = $dispatcher->handle($request);
$response->send();
