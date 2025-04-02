<?php

namespace App\Controllers;

use App\Models\Product;
use Framework\Viewer;

class Products
{
    public function index(): void
    {
        $products = (new Product())->getData();
        $viewer = new Viewer();
        $title = 'Products';
        echo $viewer->render('shared/header.php', compact('title'));
        echo $viewer->render('Products/index.php', [
            'products' => $products
        ]);
    }

    public function show(string $slug): void
    {
        $viewer = new Viewer();
        $title = 'Product Details';
        echo $viewer->render('shared/header.php', compact('title'));
        echo $viewer->render('Products/show.php', [
            'slug' => $slug
        ]);
    }

    public function showPage(string $title, string $id, string $page): void
    {
        echo "Title: $title, ID: $id, Page: $page";
    }
}
