<?php

class Products
{
    public function index()
    {
        require 'src/models/product.php';
        $products = (new Product())->getData();
        require 'views/products_index.php';
    }
}
