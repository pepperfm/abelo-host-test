<?php

declare(strict_types=1);

namespace Core;

final class Request
{
    public static function uri(): string
    {
        // Keep the path unchanged: encoded slashes and // must not become other routes.
        return explode('?', $_SERVER['REQUEST_URI'] ?? '/', 2)[0];
    }

    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public static function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }
}
