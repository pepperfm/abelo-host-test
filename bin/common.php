<?php

declare(strict_types=1);

use Core\Database\Connection;

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require dirname(__DIR__) . '/vendor/autoload.php';
date_default_timezone_set('UTC');
$config = require dirname(__DIR__) . '/config.php';

return Connection::create($config['database']);
