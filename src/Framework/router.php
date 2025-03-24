<?php

namespace Framework;

class Router
{
    private array $routes = [];

    public function add(string $path, array $params): void
    {
        $this->routes[] = compact('path', 'params');
    }

    public function match(string $path): array|false
    {
        $filteredRoutes = array_filter($this->routes, function ($route) use ($path) {
            return $route['path'] === $path;
        });
        if ($filteredRoutes) {
            $matchedRoute = array_shift($filteredRoutes);
            return $matchedRoute['params'];
        }
        return false;
    }
}
