<?php

class Products
{
    public function index()
    {
        require 'src/models/product.php';
        $products = (new Product())->getData();
        require './view.php';
    }
}
