<?php

namespace Framework;

use App\Middleware\ChangeRequestExample;
use App\Middleware\ChangeResponseExample;
use Framework\Exceptions\PageNotFoundException;
use ReflectionException;
use ReflectionMethod;
use UnexpectedValueException;

readonly class Dispatcher
{
    public function __construct(
        private Router    $router,
        private Container $container,
        private array     $middlewareAliasMap,
    )
    {
    }

    public function handle(Request $request): Response
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
        $objController->setViewer($this->container->get(TemplateViewerInterface::class));
        $objController->setResponse($this->container->get(Response::class));
        $controllerRequestHandler = new ControllerRequestHandler($objController, $action, $args);
        $middlewares = $this->getMiddlewares($params);
        // $middleware = $this->container->get(ChangeResponseExample::class);
        // $middleware2 = $this->container->get(ChangeRequestExample::class);
        $middlewareRequestHandler = new MiddlewareRequestHandler(
            $middlewares,
            $controllerRequestHandler
        );
        return $middlewareRequestHandler->handle($request);
    }

    private function getMiddlewares(array $params): array
    {
        $res = [];
        if (isset($params['middleware'])) {
            $middlewares = explode('|', $params['middleware']);
            foreach ($middlewares as $middleware) {
                if ( ! isset($this->middlewareAliasMap[$middleware])) {
                    throw new UnexpectedValueException("Middleware '$middleware' not found in alias map");
                }
                $middleware = $this->middlewareAliasMap[$middleware] ?? $middleware;
                $res[] = $this->container->get($middleware);
            }
        }
        return $res;
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