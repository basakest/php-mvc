<?php

namespace App\Controllers;

use App\Models\Product;

class Products
{
    public function index()
    {
        $products = (new Product())->getData();
        require 'views/products_index.php';
    }

    public function show(string $slug)
    {
        require 'views/products_show.php';
    }

    public function showPage(string $title, string $id, string $page)
    {
        echo "Title: $title, ID: $id, Page: $page";
    }
}
