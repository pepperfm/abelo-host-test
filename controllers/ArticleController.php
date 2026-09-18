<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\Models\Category;
use Core\App;
use Core\HttpException;
use Core\Request;
use Core\Response;
use Core\Support\Format;

final class ArticleController
{
    private Article $articles;
    private Category $categories;

    public function __construct()
    {
        $this->articles = App::get(Article::class);
        $this->categories = App::get(Category::class);
    }

    public function show(int $id): Response
    {
        $article = $this->articles->findPublished($id) ?? throw new HttpException(404, 'Статья не найдена или ещё не опубликована.');
        $countView = Request::method() === 'GET';
        if ($countView) {
            ++$article['views'];
        }

        $response = view('article', [
            'title' => $article['title'] . ' — Лист',
            'description' => $article['description'],
            'page_type' => 'article',
            'article' => $article,
            'categories' => $this->categories->forArticle($id),
            'paragraphs' => Format::paragraphs($article['body']),
            'reading_minutes' => Format::readingMinutes($article['body']),
            'similar' => $this->articles->similarTo($id, 3),
        ]);

        // Count only after successful rendering. HEAD requests leave views unchanged.
        if ($countView) {
            $this->articles->incrementViews($id);
        }

        return $response;
    }
}
