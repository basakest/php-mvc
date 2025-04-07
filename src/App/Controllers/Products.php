<?php

namespace App\Controllers;

use App\Models\Product;
use Framework\Viewer;

class Products
{
    public function __construct(
        private readonly Viewer $viewer,
        private readonly Product $product,
    )
    {
    }

    public function index(): void
    {
        $products = $this->product->getData();
        $title = 'Products';
        echo $this->viewer->render('shared/header.php', compact('title'));
        echo $this->viewer->render('Products/index.php', [
            'products' => $products
        ]);
    }

    public function show(string $slug): void
    {
        $title = 'Product Details';
        echo $this->viewer->render('shared/header.php', compact('title'));
        echo $this->viewer->render('Products/show.php', [
            'slug' => $slug
        ]);
    }

    public function showPage(string $title, string $id, string $page): void
    {
        echo "Title: $title, ID: $id, Page: $page";
    }
}
