<?php

declare(strict_types = 1);

define('ROOT_PATH', dirname(__DIR__));

use Framework\Dispatcher;
use Framework\Dotenv;

spl_autoload_register(function ($class) {
    require ROOT_PATH . '/src/' . str_replace('\\', '/', $class) . '.php';
});

$dotEnv = new Dotenv();
$dotEnv->load(ROOT_PATH . '/.env');

set_error_handler(['Framework\ErrorHandler', 'handleError']);
set_exception_handler(['Framework\ErrorHandler', 'handleException']);

// uri without query string
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ( ! $path) {
    throw new UnexpectedValueException("Malformed URI: '{$_SERVER['REQUEST_URI']}'");
}

$router = require ROOT_PATH . '/config/routes.php';

$container = require ROOT_PATH . '/config/services.php';
$dispatcher = new Dispatcher($router, $container);
$dispatcher->handle($path);
