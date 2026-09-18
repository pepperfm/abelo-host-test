<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use Core\App;
use Core\Response;

final class HomeController
{
    private Article $articles;

    public function __construct()
    {
        $this->articles = App::get(Article::class);
    }

    public function index(): Response
    {
        $latest = $this->articles->latestByCategory();
        $sections = [];

        foreach (App::get('navigation') as $category) {
            if (isset($latest[(int) $category['id']])) {
                $sections[] = ['category' => $category, 'articles' => $latest[(int) $category['id']]];
            }
        }

        return view('home', ['sections' => $sections, 'page_type' => 'home']);
    }
}
