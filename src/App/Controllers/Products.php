<?php

namespace App\Controllers;

use App\Models\Product;
use Framework\Controller;
use Framework\Exceptions\PageNotFoundException;
use Framework\Response;
use JetBrains\PhpStorm\NoReturn;

class Products extends Controller
{
    public function __construct(
        private readonly Product $product,
    )
    {
    }

    public function index(): Response
    {
        $totalNum = $this->product->getTotalNum();
        $products = $this->product->findAll();
        return $this->view('Products/index.mvc.php', compact('products', 'totalNum'));
    }

    public function show(string $id): Response
    {
        $product = $this->getById($id);
        return $this->view('Products/show.mvc.php', compact('product'));
    }

    public function edit(string $id): Response
    {
        $product = $this->getById($id);
        return $this->view('Products/edit.mvc.php', [
            'product'    => $product,
            'buttonText' => 'Update Product',
        ]);
    }

    public function showPage(string $title, string $id, string $page): void
    {
        echo "Title: $title, ID: $id, Page: $page";
    }

    public function new(): Response
    {
        $buttonText = 'Create Product';
        return $this->view('Products/new.mvc.php', compact('buttonText'));
    }

    private function getById(string $id): array
    {
        $product = $this->product->find($id);
        if ($product === false) {
            throw new PageNotFoundException('Product not found');
        }
        return $product;
    }

    public function create(): Response
    {
        $product = [
            'name'        => $this->request->post['name'] ?? '',
            'description' => $this->request->post['description'] ?? '',
        ];
        $res = $this->product->insert($product);
        if ($res) {
            $lastId = $this->product->getLastInsertId();
            return $this->redirect('/products/' . $lastId);
        }
        $errors = $this->product->getErrors();
        $buttonText = 'Create Product';
        return $this->view('Products/new.mvc.php', compact('errors', 'product', 'buttonText'));
    }

    public function update(string $id): Response
    {
        $product = $this->getById($id);
        $data = [
            'name'        => $this->request->post['name'] ?? '',
            'description' => $this->request->post['description'] ?? '',
        ];
        $res = $this->product->update($id, $data);
        if ($res) {
            return $this->redirect('/products/' . $id);
        }
        $product = $data + $product;
        $errors = $this->product->getErrors();
        return $this->view('Products/edit.mvc.php', compact('errors', 'product'));
    }

    public function delete(string $id): Response
    {
        $product = $this->getById($id);

        return $this->view('Products/delete.mvc.php', compact('product'));
    }

    public function destroy(string $id): Response
    {
        $this->getById($id);
        $this->product->delete($id);
        return $this->redirect('/products/');
    }
}
