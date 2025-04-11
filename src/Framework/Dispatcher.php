<?php

namespace Framework;

use Framework\Exceptions\PageNotFoundException;
use ReflectionException;
use ReflectionMethod;
use UnexpectedValueException;

readonly class Dispatcher
{
    public function __construct(
        private Router $router,
        private Container $container,
    )
    {
    }

    public function handle(Request $request): void
    {
        $path = $this->getPath($request->uri);
        $requestMethod = $request->method;
        $params = $this->router->match($path, $requestMethod);
        if ( ! $params) {
            throw new PageNotFoundException("no matched route for '$path' with method '$requestMethod'");
        }
        $action = $this->getActionName($params);
        $namespacedController = $this->getControllerName($params);
        $args = $this->getActionArguments($namespacedController, $action, $params);
        /** @var Controller $objController */
        $objController = $this->container->get($namespacedController);
        $objController->setRequest($request);
        $objController->setViewer($this->container->get(Viewer::class));
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

    private function getPath(string $uri): string
    {
        // uri without query string
        $path = parse_url($uri, PHP_URL_PATH);
        if ( ! $path) {
            throw new UnexpectedValueException("Malformed URI: '{$uri}'");
        }
        return $path;
    }
}