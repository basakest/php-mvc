<?php

declare(strict_types = 1);

namespace App\Middleware;

use Framework\MiddlewareInterface;
use Framework\Request;
use Framework\RequestHandlerInterface;
use Framework\Response;

class ChangeResponseExample implements MiddlewareInterface
{
    public function handle(Request $request, RequestHandlerInterface $next): Response
    {
        $response = $next->handle($request);
        $response->setBody($response->getBody() . ' Response modified by middleware');
        return $response;
    }
}