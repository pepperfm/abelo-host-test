<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final readonly class Article
{
    public function __construct(private PDO $pdo, private string $publishedBefore)
    {
    }

    /**
     * @return array<int, list<array<string, mixed>>>
     */
    public function latestByCategory(int $limit = 3): array
    {
        // Rank only IDs; avoid copying long article bodies into the window result.
        $statement = $this->pdo->prepare(<<<'SQL'
            WITH ranked AS (
                SELECT ac.category_id, a.id AS article_id,
                    ROW_NUMBER() OVER (
                        PARTITION BY ac.category_id
                        ORDER BY a.published_at DESC, a.id DESC
                    ) AS row_number_in_category
                FROM articles AS a
                JOIN article_category AS ac ON ac.article_id = a.id
                WHERE a.published_at <= :published_before
            )
            SELECT r.category_id, a.id, a.title, a.description, a.image_path, a.views, a.published_at
            FROM ranked AS r
            JOIN articles AS a ON a.id = r.article_id
            WHERE r.row_number_in_category <= :limit
            ORDER BY r.category_id, r.row_number_in_category
            SQL);
        $statement->bindValue(':published_before', $this->publishedBefore);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        $grouped = [];
        foreach ($statement->fetchAll() as $article) {
            $grouped[(int) $article['category_id']][] = $article;
        }
        return $grouped;
    }

    public function countByCategory(int $categoryId): int
    {
        $statement = $this->pdo->prepare(<<<'SQL'
            SELECT COUNT(*)
            FROM articles AS a
            JOIN article_category AS ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id AND a.published_at <= :published_before
            SQL);
        $statement->execute(['category_id' => $categoryId, 'published_before' => $this->publishedBefore]);

        return (int) $statement->fetchColumn();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function paginateByCategory(int $categoryId, ArticleSort $sort, int $limit, int $offset): array
    {
        $orderBy = $sort->orderBy();
        $statement = $this->pdo->prepare(<<<SQL
            SELECT a.id, a.title, a.description, a.image_path, a.views, a.published_at
            FROM articles AS a
            JOIN article_category AS ac ON ac.article_id = a.id
            WHERE ac.category_id = :category_id AND a.published_at <= :published_before
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
            SQL);
        $statement->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue(':published_before', $this->publishedBefore);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findPublished(int $id): ?array
    {
        $statement = $this->pdo->prepare(<<<'SQL'
            SELECT id, title, description, body, image_path, views, published_at
            FROM articles WHERE id = :id AND published_at <= :published_before
            SQL);
        $statement->execute(['id' => $id, 'published_before' => $this->publishedBefore]);

        return $statement->fetch() ?: null;
    }

    public function incrementViews(int $id): void
    {
        // Increment inside MySQL: concurrent requests cannot overwrite each other's increments.
        $statement = $this->pdo->prepare('UPDATE articles SET views = views + 1 WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function similarTo(int $articleId, int $limit = 3): array
    {
        // EXISTS avoids duplicate cards when two articles share several categories.
        $statement = $this->pdo->prepare(<<<'SQL'
            SELECT a.id, a.title, a.description, a.image_path, a.views, a.published_at
            FROM articles AS a
            WHERE a.id <> :excluded_id
              AND a.published_at <= :published_before
              AND EXISTS (
                  SELECT 1
                  FROM article_category AS candidate
                  JOIN article_category AS current_article
                    ON current_article.category_id = candidate.category_id
                  WHERE candidate.article_id = a.id AND current_article.article_id = :article_id
              )
            ORDER BY a.published_at DESC, a.id DESC
            LIMIT :limit
            SQL);
        $statement->bindValue(':excluded_id', $articleId, PDO::PARAM_INT);
        $statement->bindValue(':article_id', $articleId, PDO::PARAM_INT);
        $statement->bindValue(':published_before', $this->publishedBefore);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
