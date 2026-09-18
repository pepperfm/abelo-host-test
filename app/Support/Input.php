<?php

declare(strict_types=1);

namespace Core\Support;

use Core\HttpException;

final class Input
{
    public static function page(mixed $value): int
    {
        if ($value === null) {
            return 1;
        }
        if (!is_string($value) || !preg_match('/^[1-9]\d*$/D', $value)) {
            throw new HttpException(400, 'Номер страницы должен быть положительным целым числом.');
        }
        $page = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 2147483647]]);
        if ($page === false) {
            throw new HttpException(400, 'Слишком большой номер страницы.');
        }
        return $page;
    }
}
