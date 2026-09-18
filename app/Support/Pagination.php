<?php

declare(strict_types=1);

namespace Core\Support;

use App\Models\ArticleSort;
use Core\HttpException;
use InvalidArgumentException;

final readonly class Pagination
{
    public int $lastPage;
    public int $offset;

    public function __construct(public int $total, public int $perPage, public int $page)
    {
        if ($total < 0 || $perPage < 1 || $page < 1) {
            throw new InvalidArgumentException('Invalid pagination arguments.');
        }
        $this->lastPage = $total === 0 ? 1 : intdiv($total - 1, $perPage) + 1;
        if ($page > $this->lastPage) {
            throw new HttpException(404, 'В этой категории нет такой страницы.');
        }
        $this->offset = ($page - 1) * $perPage;
    }

    /** @return array<string, mixed> */
    public function forTemplate(string $path, ArticleSort $sort): array
    {
        $url = static fn (int $page): string => $path . '?' . http_build_query(
            ['sort' => $sort->value, 'page' => $page], '', '&', PHP_QUERY_RFC3986,
        );
        $numbers = array_unique(array_merge(
            [1, $this->lastPage],
            range(max(1, $this->page - 2), min($this->lastPage, $this->page + 2)),
        ));
        sort($numbers);
        $links = [];
        $previous = 0;
        foreach ($numbers as $number) {
            if ($number > $previous + 1) {
                $links[] = ['gap' => true];
            }
            $links[] = ['gap' => false, 'number' => $number, 'url' => $url($number), 'current' => $number === $this->page];
            $previous = $number;
        }
        return [
            'total' => $this->total,
            'page' => $this->page,
            'last_page' => $this->lastPage,
            'from' => $this->total === 0 ? 0 : $this->offset + 1,
            'to' => min($this->offset + $this->perPage, $this->total),
            'links' => $links,
            'previous_url' => $this->page > 1 ? $url($this->page - 1) : null,
            'next_url' => $this->page < $this->lastPage ? $url($this->page + 1) : null,
        ];
    }
}
