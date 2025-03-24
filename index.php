<?php

// uri without query string
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$items = array_values(array_filter(explode('/', $path)));
list($controller, $action) = $items + ['home', 'index'];

require "src/controllers/{$controller}.php";
$objController = new $controller();
$objController->$action();
