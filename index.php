<?php

require 'src/controllers/products.php';

$action = $_GET['action'] ?? 'index';
$products = new Products();
if ($action === 'index') {
    $products->index();
} elseif ($action === 'show') {
    $products->show();
} else {
    $products->index();
}
