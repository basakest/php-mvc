<?php

declare(strict_types = 1);

namespace Framework;

class ControllerRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly Controller $controller,
        private readonly string     $action,
        private array               $args,
    )
    {
    }

    public function handle(Request $request): Response
    {
        $this->controller->setRequest($request);
        /**
         * Arrays and Traversable objects can be unpacked into argument lists when calling functions by using the ... operator.
         * @see https://www.php.net/manual/en/migration56.new-features.php
         */
        // when you run a method from a variable like $action, it's not case sensitive
        // 不过 mac 默认的文件系统本身就是不区分大小写的, 所以不会出问题
        return ($this->controller)->{$this->action}(...$this->args);
    }
}