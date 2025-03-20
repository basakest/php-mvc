<?php

$controller = ucfirst($_GET['controller'] ?? 'home');
$action = $_GET['action'] ?? 'index';

require "src/controllers/{$controller}.php";
$objController = new $controller();
$objController->$action();
