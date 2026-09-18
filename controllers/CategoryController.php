<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\Models\ArticleSort;
use App\Models\Category;
use Core\App;
use Core\HttpException;
use Core\Request;
use Core\Response;
use Core\Support\Input;
use Core\Support\Pagination;

final class CategoryController
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
        $category = $this->categories->find($id) ?? throw new HttpException(404, 'Категория не найдена.');
        $sort = ArticleSort::fromQuery(Request::query('sort'));
        $page = Input::page(Request::query('page'));
        $perPage = App::get('config')['per_page'];
        $pagination = new Pagination($this->articles->countByCategory($id), $perPage, $page);

        return view('category', [
            'title' => $category['name'] . ' — Лист',
            'description' => $category['description'],
            'page_type' => 'category',
            'active_category_id' => $id,
            'category' => $category,
            'articles' => $this->articles->paginateByCategory($id, $sort, $perPage, $pagination->offset),
            'sort' => $sort->value,
            'pagination' => $pagination->forTemplate("/categories/$id", $sort),
        ]);
    }
}
