<?php

declare(strict_types = 1);

namespace Framework;

class Request
{
    public function __construct(
        public string $uri,
        public string $method,
        public array $get,
        public array $post,
        public array $cookies,
        public array $files,
        public array $server,
    )
    {
    }

    public static function createFromGlobals(): Request
    {
        return new static(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD'],
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES,
            $_SERVER,
        );
    }
}