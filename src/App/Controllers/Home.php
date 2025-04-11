<?php

namespace App\Controllers;

use Framework\Controller;

class Home extends Controller
{
    public function index(): void
    {
        $title = 'Home';
        echo $this->viewer->render('shared/header.php', compact('title'));
        echo $this->viewer->render('Home/index.php');
    }
}
