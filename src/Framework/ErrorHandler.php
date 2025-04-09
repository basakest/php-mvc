<?php

declare(strict_types = 1);

namespace Framework;

use ErrorException;
use Framework\Exceptions\PageNotFoundException;
use Throwable;

class ErrorHandler
{
    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function handleException(Throwable $exception) {
        $template = '500.php';
        if ($exception instanceof PageNotFoundException) {
            $template = '404.php';
            http_response_code(404);
        } else {
            http_response_code(500);
        }

        $showErrors = $_ENV['SHOW_ERRORS'];
        if ($showErrors) {
            ini_set('display_errors', '1');
        } else {
            ini_set('display_errors', '0');
            ini_set('log_errors', '1');
            require "views/$template";
        }
        throw $exception;
    }
}