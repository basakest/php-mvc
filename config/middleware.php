<?php

use App\Middleware\ChangeRequestExample;
use App\Middleware\ChangeResponseExample;
use App\Middleware\RedirectExample;

return [
    'message' => ChangeResponseExample::class,
    'trim'    => ChangeRequestExample::class,
    'deny'    => RedirectExample::class,
];