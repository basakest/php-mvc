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
        $totalNum = $this->product->getTotalNum();
        $products = $this->product->findAll();
        $title = 'Products';
        echo $this->viewer->render('shared/header.php', compact('title'));
        echo $this->viewer->render('Products/index.php', compact('products', 'totalNum'));
    }

    public function show(string $id): void
    {
        $product = $this->getById($id);
        $title = 'Product Details';
        echo $this->viewer->render('shared/header.php', compact('title'));
        echo $this->viewer->render('Products/show.php', [
            'product' => $product,
        ]);
    }

    public function edit(string $id): void
    {
        $product = $this->getById($id);
        $title = 'Edit Product';
        echo $this->viewer->render('shared/header.php', compact('title'));
        echo $this->viewer->render('Products/edit.php', [
            'product'    => $product,
            'buttonText' => 'Update Product'
        ]);
    }

    public function showPage(string $title, string $id, string $page): void
    {
        echo "Title: $title, ID: $id, Page: $page";
    }

    public function new(): void
    {
        echo $this->viewer->render('shared/header.php', ['title' => 'Create Product']);
        echo $this->viewer->render('Products/new.php');
    }

    private function getById(string $id): array
    {
        $product = $this->product->find($id);
        if ($product === false) {
            throw new PageNotFoundException('Product not found');
        }
        return $product;
    }

    public function create(): void
    {
        $product = [
            'name'        => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];
        $res = $this->product->insert($product);
        if ($res) {
            $lastId = $this->product->getLastInsertId();
            header('Location: /products/' . $lastId);
            exit;
        }
        $errors = $this->product->getErrors();
        echo $this->viewer->render('shared/header.php', ['title' => 'Create Product']);
        echo $this->viewer->render('Products/new.php', compact('errors', 'product'));
    }

    public function update(string $id): void
    {
        $product = $this->getById($id);
        $data = [
            'name'        => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];
        $res = $this->product->update($id, $data);
        // $res = false;
        if ($res) {
            header('Location: /products/' . $id);
            exit;
        }
        $product = $data + $product;
        $errors = $this->product->getErrors();
        echo $this->viewer->render('shared/header.php', ['title' => 'Create Product']);
        echo $this->viewer->render('Products/edit.php', compact('errors', 'product'));
    }

    public function delete(string $id): void
    {
        $product = $this->getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->product->delete($id);
            header('Location: /products/');
            exit;
        }
        echo $this->viewer->render('shared/header.php', ['title' => 'Delete Product']);
        echo $this->viewer->render('Products/delete.php', compact('product'));
    }
}
