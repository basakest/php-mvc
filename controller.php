<?php

class Controller
{
    public function index()
    {
        require './model.php';
        $products = (new Model())->getData();
        require './view.php';
    }
}
