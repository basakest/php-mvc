<?php

declare(strict_types = 1);

namespace Framework;

use ReflectionClass;
use Closure;

class Container
{
    private array $registry = [];

    public function set(string $name, Closure $value): void
    {
        $this->registry[$name] = $value;
    }

    public function get(string $className)
    {
        if (isset($this->registry[$className])) {
            return $this->registry[$className]();
        }
        $class = new ReflectionClass($className);
        $constructor = $class->getConstructor();
        $dependencies = [];
        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $reflectionType = $param->getType();
                if ($reflectionType == null) {
                    exit("Unable to resolve constructor parameter '{$param->getName()}' of type '$reflectionType' in the $className class");
                }
                if ($reflectionType instanceof \ReflectionNamedType) {
                    if ($reflectionType->isBuiltin()) {
                        exit("Unable to resolve construct parameter: {$param->getName()} of type '{$reflectionType}' in the $className class");
                    }
                    // 反射类和 xdebug 一起使用可能会有问题, 在 $name 后设置断点也看不到这个变量的信息
                    $name = $reflectionType->getName();
                    $dependencies[] = $this->get($name);
                } else {
                    exit("Constructor parameter '{$param->getName()}' in the $className class is an invalid type: '$reflectionType' - only single named types supported");
                }
            }
            return new $className(...$dependencies);
        } else {
            return new $className();
        }
    }
}