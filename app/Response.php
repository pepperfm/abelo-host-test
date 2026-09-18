<?php

declare(strict_types=1);

namespace Core;

final readonly class Response
{
    /** @param array<string, string> $headers */
    public function __construct(
        public string $body,
        public int $status = 200,
        public array $headers = [],
    ) {
    }

    public function send(bool $head = false): void
    {
        http_response_code($this->status);
        $headers = array_replace([
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-store',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Content-Security-Policy' => "default-src 'none'; style-src 'self'; img-src 'self'; font-src 'self'; base-uri 'none'; form-action 'self'; frame-ancestors 'none'",
        ], $this->headers);
        foreach ($headers as $name => $value) {
            header("$name:$value");
        }
        if (!$head) {
            echo $this->body;
        }
    }
}
