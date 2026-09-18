<?php

declare(strict_types=1);

try {
    $pdo = require __DIR__ . '/common.php';
    // Our schema has exactly three plain CREATE TABLE statements, no procedures.
    foreach (explode(';', file_get_contents(dirname(__DIR__) . '/database/schema.sql')) as $sql) {
        if (trim($sql) !== '') {
            $pdo->exec($sql);
        }
    }
    fwrite(STDOUT, "Схема базы данных готова.\n");
} catch (Throwable $exception) {
    fwrite(STDERR, "Инициализация не выполнена: {$exception->getMessage()}\n");
    exit(1);
}
