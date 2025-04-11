<?php

namespace Framework;

class Router
{
    private array $routes = [];

    public function add(string $path, array $params = []): void
    {
        $this->routes[] = compact('path', 'params');
    }

    public function match(string $path, string $requestMethod): array|false
    {
        // 如果浏览器地址包括西班牙语等字符, 会被转码
        $path = urldecode($path); // 解码 %2F 等转义字符
        $path = trim($path, '/');
        foreach ($this->routes as $route) {
            if (isset($route['params']['method']) && $route['params']['method'] !== $requestMethod) {
                continue;
            }
            $pattern = $this->getPatternFromRouterPath($route['path']);
            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return array_merge($params, $route['params']);
            }
        }

        return false;
    }

    private function getPatternFromRouterPath(string $routePath): string
    {
        // eg: /{controller}/{action}
        $routePath = trim($routePath, '/');
        $segments = explode('/', $routePath);
        $segments = array_map(function (string $segment): string {
            // 获取 {} 中的内容, 生成新的正则
            // {controller}, {action}
            if (preg_match('#^\{([a-z][a-zA-Z]*)\}$#', $segment, $matches)) {
                return "(?<{$matches[1]}>[^/]*)";
            }
            // 处理 {name:regex} 这种形式的路由参数
            // eg: {id:\d+}
            if (preg_match('#^\{([a-z][a-zA-Z]*):(.+)\}$#', $segment, $matches)) {
                return "(?<{$matches[1]}>{$matches[2]})";
            }
            // var_dump($matches);exit;
            // html 会将 <controller>, <action> 这样的捕获组名称转义, 需要 view source
            return $segment;
        }, $segments);
        // 末尾的 i 表示不区分大小写
        // u: make the pattern match any unicode characters
        return '#^'. implode('/', $segments). '$#iu';
    }
}
