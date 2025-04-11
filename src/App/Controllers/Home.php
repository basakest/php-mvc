<?php

namespace App\Controllers;

use Framework\Controller;
use Framework\Viewer;

class Home extends Controller
{
    public function index(): void
    {
        $viewer = new Viewer();
        $title = 'Home';
        echo $viewer->render('shared/header.php', compact('title'));
        echo $viewer->render('Home/index.php');
    }
}
