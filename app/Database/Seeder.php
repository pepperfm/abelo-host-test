<?php

declare(strict_types=1);

namespace Core\Database;

use DateTimeImmutable;
use DateTimeZone;
use PDO;
use Throwable;

final readonly class Seeder
{
    public function __construct(private PDO $pdo)
    {
    }

    public function run(bool $reset = false): bool
    {
        $fixtures = require dirname(__DIR__, 2) . '/database/fixtures.php';
        $this->pdo->beginTransaction();
        try {
            $hasData = (int) $this->pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn() > 0
                || (int) $this->pdo->query('SELECT COUNT(*) FROM articles')->fetchColumn() > 0;
            if ($hasData && !$reset) {
                $this->pdo->rollBack();
                return false;
            }
            if ($reset) {
                // DELETE is transactional; TRUNCATE would implicitly commit in MySQL.
                $this->pdo->exec('DELETE FROM article_category');
                $this->pdo->exec('DELETE FROM articles');
                $this->pdo->exec('DELETE FROM categories');
            }
            $categoryStatement = $this->pdo->prepare('INSERT INTO categories (id, name, description) VALUES (:id, :name, :description)');
            foreach ($fixtures['categories'] as $category) {
                $categoryStatement->execute($category);
            }
            $articleStatement = $this->pdo->prepare(<<<'SQL'
                INSERT INTO articles (id, title, description, body, image_path, views, published_at)
                VALUES (:id, :title, :description, :body, :image_path, :views, :published_at)
                SQL);
            $linkStatement = $this->pdo->prepare('INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)');
            $anchor = new DateTimeImmutable('yesterday 12:00:00', new DateTimeZone('UTC'));
            foreach ($fixtures['articles'] as $index => $article) {
                $categories = $article['categories'];
                unset($article['categories']);
                $article['id'] = $index + 1;
                $article['views'] = (($index * 137 + 431) % 2600) + 40;
                // Neighbouring items deliberately share a timestamp to exercise the ID tiebreaker.
                $article['published_at'] = $anchor->modify('-' . intdiv($index, 2) . ' days')->format('Y-m-d H:i:s');
                $articleStatement->execute($article);
                foreach (array_unique($categories) as $categoryId) {
                    $linkStatement->execute(['article_id' => $article['id'], 'category_id' => $categoryId]);
                }
            }
            $articleStatement->execute([
                'id' => 33,
                'title' => 'Материал, который ещё готовится',
                'description' => 'Эта статья появится после наступления даты публикации.',
                'body' => 'Запланированная статья для проверки фильтра публикации.',
                'image_path' => '/assets/images/cover-1.svg',
                'views' => 0,
                'published_at' => new DateTimeImmutable('+1 month', new DateTimeZone('UTC'))->format('Y-m-d H:i:s'),
            ]);
            $linkStatement->execute(['article_id' => 33, 'category_id' => 1]);
            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
