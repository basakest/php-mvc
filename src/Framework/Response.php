<?php

namespace Framework;

class Response
{
    private string $body = '';

    private array $headers = [];

    private int $statusCode = 200;

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function send(): void
    {
        if ($this->statusCode >= 0) {
            http_response_code($this->statusCode);
        }
        foreach ($this->headers as $header) {
            header($header);
        }
        echo $this->body;
    }

    public function addHeader($value): void
    {
        $this->headers[] = $value;
    }

    public function redirect(string $url): void
    {
        $this->addHeader('Location: '. $url);
    }
}