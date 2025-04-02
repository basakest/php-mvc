<?php

namespace App\Controllers;

use Framework\Viewer;

class Home
{
    public function index(): void
    {
        $viewer = new Viewer();
        $title = 'Home';
        echo $viewer->render('shared/header.php', compact('title'));
        echo $viewer->render('Home/index.php');
    }
}
