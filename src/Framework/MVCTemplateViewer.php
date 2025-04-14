<?php

declare(strict_types = 1);

namespace Framework;

class MVCTemplateViewer implements TemplateViewerInterface
{
    public function render(string $template, array $data = []): string
    {
        $viewPath = dirname(__DIR__, 2) . '/views/';
        $path = $viewPath . $template;
        $code = file_get_contents($path);
        if (preg_match('#^{% extends "(?<template>.*)" %}#', $code, $matches)) {
            $templatePath = $viewPath. $matches['template'];
            $blocks = $this->getBlocks($code);
            $templateCode = file_get_contents($templatePath);
            $code = $this->replaceYields($templateCode, $blocks);
        }
        $code = $this->loadIncludes($viewPath, $code);
        /* {{ $placeholder }} => <?= htmlspecialchars($placeholder); ?> */
        $code = $this->replaceVariables($code);
        /* {% $placeholder %} => <?php $placeholder ?> */
        $code = $this->replacePhp($code);
        extract($data, EXTR_SKIP);
        // 使用 file_get_contents 来读区文件内容, 里面的 PHP 代码并不会被执行
        // 浏览器会将其中的 PHP 代码当做 HTML 代码来解析
        // 也可以保证结果不会立即输出
        ob_start();
        // eval function is expecting PHP code right from the first character of the string it receives
        // 如果 $code 中有 require/include 对应的 views 引擎语句不会被解析
        eval("?>$code");
        return ob_get_clean();
    }

    private function replaceVariables(string $code): string
    {
        return preg_replace("#{{\s*(\S+)\s*}}#", "<?= htmlspecialchars(\$$1 ?? ''); ?>", $code);
    }

    private function replacePhp(string $code): string
    {
        return preg_replace("#{%\s*(.+)\s*%}#", "<?php $1 ?>", $code);
    }

    private function getBlocks(string $code): array
    {
        // {% block title %}Products{% endblock %}
        // . doesn't math new line characters
        // preg_match_all("#{% block (?<name>\w+) %}(?<content>.*){% endblock %}#", $code, $matches, PREG_PATTERN_ORDER);
        // add s to last
        // dot all mode: dot matches new line
        // by default, regular expressions are greedy, which means that it will match as much as possible
        // the ? after ?<content>.* make it lazy, which means it will stop at the first endblock
        preg_match_all("#{% block (?<name>\w+) %}(?<content>.*?){% endblock %}#s", $code, $matches, PREG_PATTERN_ORDER);
        $res = [];
        if ($matches) {
            $res = array_combine($matches['name'], $matches['content']);
        }
        return $res;
    }

    private function replaceYields(string $code, array $blocks): string
    {
        // {% yield title %}
        preg_match_all("#{% yield (?<name>\w+) %}#", $code, $matches, PREG_PATTERN_ORDER);
        foreach ($matches['name'] as $name) {
            $code = preg_replace("#{% yield $name %}#", $blocks[$name]?? '', $code);
        }
        return $code;
    }

    private function loadIncludes(string $dir, string $code): string
    {
        // {% include "file_name" %}
        preg_match_all('#{% include "(?<file>.*?)" %}#', $code, $matches, PREG_PATTERN_ORDER);
        foreach ($matches['file'] as $file) {
            $content = file_get_contents($dir. $file);
            $code = preg_replace("#{% include \"$file\" %}#", $content, $code);
        }
        return $code;
    }
}