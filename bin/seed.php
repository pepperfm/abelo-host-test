<?php

declare(strict_types=1);

use Core\Database\Seeder;

try {
    $options = array_slice($argv, 1);
    if (array_diff($options, ['--reset']) !== []) {
        throw new InvalidArgumentException('Использование: php bin/seed.php [--reset]');
    }
    $pdo = require __DIR__ . '/common.php';
    $inserted = new Seeder($pdo)->run(in_array('--reset', $options, true));
    fwrite(STDOUT, $inserted
        ? "Созданы 5 категорий, 32 опубликованные статьи и 1 запланированная.\n"
        : "Данные уже существуют, сидинг пропущен. --reset удалит их и создаст демонабор заново.\n");
} catch (Throwable $exception) {
    fwrite(STDERR, "Сидинг не выполнен: {$exception->getMessage()}\n");
    exit(1);
}
