<?php

namespace Framework;

class Viewer
{
    public function render(string $template, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        // 使用 file_get_contents 来读区文件内容, 里面的 PHP 代码并不会被执行
        // 浏览器会将其中的 PHP 代码当做 HTML 代码来解析
        ob_start();
        require 'views/' . $template;
        return ob_get_clean();
    }
}