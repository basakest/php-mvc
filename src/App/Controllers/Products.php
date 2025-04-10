<?php

namespace App\Controllers;

use App\Models\Product;
use Framework\Exceptions\PageNotFoundException;
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
        $products = $this->product->findAll();
        $title = 'Products';
        echo $this->viewer->render('shared/header.php', compact('title'));
        echo $this->viewer->render('Products/index.php', [
            'products' => $products
        ]);
    }

    public function show(string $id): void
    {
        $product = $this->product->find($id);
        if ($product === false) {
            throw new PageNotFoundException('Product not found');
        }
        $title = 'Product Details';
        echo $this->viewer->render('shared/header.php', compact('title'));
        echo $this->viewer->render('Products/show.php', [
            'product' => $product,
        ]);
    }

    public function showPage(string $title, string $id, string $page): void
    {
        echo "Title: $title, ID: $id, Page: $page";
    }
}
