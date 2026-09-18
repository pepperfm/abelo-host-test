<?php

declare(strict_types=1);

namespace App\Models;

use Core\HttpException;

enum ArticleSort: string
{
    case Date = 'date';
    case Views = 'views';

    public static function fromQuery(mixed $value): self
    {
        if ($value === null) {
            return self::Date;
        }
        if (!is_string($value) || self::tryFrom($value) === null) {
            throw new HttpException(400, 'Неизвестная сортировка. Используйте date или views.');
        }
        return self::from($value);
    }

    public function orderBy(): string
    {
        // SQL identifiers cannot be bound as PDO values. This is an explicit allowlist.
        return match ($this) {
            self::Date => 'a.published_at DESC, a.id DESC',
            self::Views => 'a.views DESC, a.published_at DESC, a.id DESC',
        };
    }
}
