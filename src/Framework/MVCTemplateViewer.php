<?php

declare(strict_types = 1);

namespace Framework;

class MVCTemplateViewer implements TemplateViewerInterface
{
    public function render(string $template, array $data = []): string
    {
        $path = dirname(__DIR__, 2) . '/views/' . $template;
        $code = file_get_contents($path);
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
        eval("?>$code");
        return ob_get_clean();
    }

    private function replaceVariables(string $code): string
    {
        return preg_replace("#{{\s*(\S+)\s*}}#", "<?= htmlspecialchars(\$$1); ?>", $code);
    }

    private function replacePhp(string $code): string
    {
        return preg_replace("#{%\s*(.+)\s*%}#", "<?php $1 ?>", $code);
    }
}