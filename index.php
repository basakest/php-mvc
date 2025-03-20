<?php

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

if ($controller === 'products') {
    require 'src/controllers/products.php';
    $objController = new Products();
} else {
    require 'src/controllers/home.php';
    $objController = new Home();
}

if ($action === 'index') {
    $objController->index();
} elseif ($action === 'show') {
    $objController->show();
} else {
    $objController->index();
}
