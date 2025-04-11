<?php

namespace App\Controllers;

use Framework\Controller;

class UserAdmin2 extends Controller
{
    public function getList($a = 23): void
    {
        echo 'UserAdmin2::getList()';
    }
}