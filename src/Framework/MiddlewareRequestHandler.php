<?php

declare(strict_types = 1);

namespace Framework;

class MiddlewareRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private array                             $middlewareStack,
        private readonly ControllerRequestHandler $controllerRequestHandler,
    )
    {
    }

    public function handle(Request $request): Response
    {
        $middleware = array_shift($this->middlewareStack);
        if ($middleware === null) {
            return $this->controllerRequestHandler->handle($request);
        } else {
            // 递归调用
            return $middleware->handle($request, $this);
        }
    }
}