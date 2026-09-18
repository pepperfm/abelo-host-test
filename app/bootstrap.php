<?php

declare(strict_types=1);

use App\Models\Article;
use App\Models\Category;
use Core\App;
use Core\Database\Connection;
use Core\View;

$root = dirname(__DIR__);
if (!is_file($root . '/vendor/autoload.php')) {
    throw new RuntimeException('Dependencies are missing. Run composer install.');
}
require_once $root . '/vendor/autoload.php';
date_default_timezone_set('UTC');

$config = require $root . '/config.php';
$pdo = Connection::create($config['database']);

// All models use the same publication cutoff throughout this request.
$publishedBefore = gmdate('Y-m-d H:i:s');
$articles = new Article($pdo, $publishedBefore);
$categories = new Category($pdo, $publishedBefore);
$navigation = $categories->allWithCounts();

App::bind('config', $config);
App::bind('navigation', $navigation);
App::bind('view', new View($root, $navigation));

App::bind(Article::class, $articles);
App::bind(Category::class, $categories);
