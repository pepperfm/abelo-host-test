<?php

declare(strict_types=1);

// Docker injects environment variables. For a non-Docker installation,
// copy config.local.php.example to config.local.php (not committed).
$config = [
    'database' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_DATABASE') ?: 'blog',
        'username' => getenv('DB_USERNAME') ?: 'blog',
        'password' => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : 'local_blog_password',
    ],
    'per_page' => 6,
];

if (is_file(__DIR__ . '/config.local.php')) {
    $config = array_replace_recursive($config, require __DIR__ . '/config.local.php');
}

return $config;
