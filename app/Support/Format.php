<?php

declare(strict_types=1);

namespace Core\Support;

use DateTimeImmutable;
use DateTimeZone;

final class Format
{
    /**
     * @throws \DateMalformedStringException
     */
    public static function date(string $value): string
    {
        $months = [1 => 'янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'];
        $date = new DateTimeImmutable($value, new DateTimeZone('UTC'));

        return $date->format('j') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y');
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function isoDate(string $value): string
    {
        return new DateTimeImmutable($value, new DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
    }

    public static function number(int|string $value): string
    {
        return number_format((int) $value, 0, ',', ' ');
    }

    /* Plain text, never trusted HTML. */
    public static function paragraphs(string $body): array
    {
        $body = trim($body);

        return $body === '' ? [] : preg_split('/\R\s*\R/u', $body);
    }

    public static function readingMinutes(string $body): int
    {
        $words = preg_match_all('/[\p{L}\p{N}]+/u', $body);

        return max(1, (int) ceil($words / 180));
    }
}
