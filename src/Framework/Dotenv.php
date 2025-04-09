<?php

declare(strict_types = 1);

namespace Framework;

class Dotenv
{
    public function load(string $filePath): void
    {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[$key] = trim($value);
        }
    }
}