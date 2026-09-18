<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final readonly class Category
{
    public function __construct(private PDO $pdo, private string $publishedBefore)
    {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allWithCounts(): array
    {
        $statement = $this->pdo->prepare(<<<'SQL'
            SELECT c.id, c.name, c.description, COUNT(a.id) AS article_count
            FROM categories AS c
            LEFT JOIN article_category AS ac ON ac.category_id = c.id
            LEFT JOIN articles AS a ON a.id = ac.article_id AND a.published_at <= :published_before
            GROUP BY c.id, c.name, c.description
            ORDER BY c.id
            SQL);
        $statement->execute(['published_before' => $this->publishedBefore]);

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT id, name, description FROM categories WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function forArticle(int $articleId): array
    {
        $statement = $this->pdo->prepare(<<<'SQL'
            SELECT c.id, c.name, c.description
            FROM categories AS c
            JOIN article_category AS ac ON ac.category_id = c.id
            WHERE ac.article_id = :article_id
            ORDER BY c.id
            SQL);
        $statement->execute(['article_id' => $articleId]);

        return $statement->fetchAll();
    }
}
