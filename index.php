<?php

declare(strict_types = 1);

use Framework\Dispatcher;
use Framework\Dotenv;

spl_autoload_register(function ($class) {
    require 'src/' . str_replace('\\', '/', $class) . '.php';
});

set_error_handler(['Framework\ErrorHandler', 'handleError']);
set_exception_handler(['Framework\ErrorHandler', 'handleException']);

$dotEnv = new Dotenv();
$dotEnv->load('.env');

// uri without query string
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ( ! $path) {
    throw new UnexpectedValueException("Malformed URI: '{$_SERVER['REQUEST_URI']}'");
}

$router = require 'config/routes.php';

$container = require 'config/services.php';
$dispatcher = new Dispatcher($router, $container);
$dispatcher->handle($path);
