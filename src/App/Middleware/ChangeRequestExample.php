<?php

declare(strict_types = 1);

namespace App\Middleware;

use Framework\MiddlewareInterface;
use Framework\Request;
use Framework\RequestHandlerInterface;
use Framework\Response;

class ChangeRequestExample implements MiddlewareInterface
{
    public function handle(Request $request, RequestHandlerInterface $next): Response
    {
        $request->post = array_map('trim', $request->post);
        $response = $next->handle($request);
        return $response;
    }
}