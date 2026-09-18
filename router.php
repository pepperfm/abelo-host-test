<?php

declare(strict_types=1);

// Development router: php -S 127.0.0.1:8080 -t public router.php
$path = explode('?', $_SERVER['REQUEST_URI'] ?? '/', 2)[0];
$assets = realpath(__DIR__ . '/public/assets');
$file = realpath(__DIR__ . '/public' . rawurldecode($path));
if ($assets !== false && $file !== false && str_starts_with($file, $assets . DIRECTORY_SEPARATOR) && is_file($file)) {
    return false;
}
require __DIR__ . '/public/index.php';
