<?php

declare(strict_types=1);

namespace Core;

use LogicException;

/* A small registry, configured once in bootstrap. No autowiring or factories. */
final class App
{
    /** @var array<string, mixed> */
    private static array $bindings = [];

    public static function bind(string $key, mixed $value): void
    {
        self::$bindings[$key] = $value;
    }

    public static function get(string $key): mixed
    {
        if (!array_key_exists($key, self::$bindings)) {
            throw new LogicException("Application binding not found: $key");
        }

        return self::$bindings[$key];
    }
}
