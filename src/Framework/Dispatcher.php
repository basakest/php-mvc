<?php

namespace Framework;

use Framework\Exceptions\PageNotFoundException;
use ReflectionException;
use ReflectionMethod;

readonly class Dispatcher
{
    public function __construct(
        private Router $router,
        private Container $container,
    )
    {
    }

    public function handle(string $path): void
    {
        $params = $this->router->match($path);
        if ( ! $params) {
            throw new PageNotFoundException("no matched route for '$path'");
        }
        $action = $this->getActionName($params);
        $namespacedController = $this->getControllerName($params);
        $args = $this->getActionArguments($namespacedController, $action, $params);
        $objController = $this->container->get($namespacedController);
        /**
         * Arrays and Traversable objects can be unpacked into argument lists when calling functions by using the ... operator.
         * @see https://www.php.net/manual/en/migration56.new-features.php
         */
        // when you run a method from a variable like $action, it's not case sensitive
        // 不过 mac 默认的文件系统本身就是不区分大小写的, 所以不会出问题
        $objController->$action(...$args);
    }

    private function getActionArguments(string $controller, string $action, array $params): array
    {
        $args = [];
        try {
            $method = new ReflectionMethod($controller, $action);
        } catch (ReflectionException $e) {
            return [];
        }
        $methodParams = $method->getParameters();
        foreach ($methodParams as $methodParam) {
            $paramName = $methodParam->getName();
            if (isset($params[$paramName])) {
                $args[$paramName] = $params[$paramName];
            }
        }
        return $args;
    }

    private function getControllerName(array $params): string
    {
        $controller = $params['controller'];
        $controller = ucwords(strtolower($controller), '-');
        $controller = str_replace('-', '', $controller);
        $namespace = 'App\\Controllers';
        if (isset($params['namespace'])) {
            $namespace .= '\\' . $params['namespace'];
        }
        return $namespace. '\\'. $controller;
    }

    private function getActionName(array $params): string
    {
        $action = $params['action'];
        $action = ucwords(strtolower($action), '-');
        $action = str_replace('-', '', $action);
        return lcfirst($action);
    }
}