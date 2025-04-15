<?php

declare(strict_types = 1);

namespace Framework;

interface MiddlewareInterface
{
    public function handle(Request $request, RequestHandlerInterface $next): Response;
}