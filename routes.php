<?php

declare(strict_types=1);

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use Core\Router;

/** @var Router $router */
$router->get('/', [HomeController::class, 'index']);
$router->get('/categories/{id}', [CategoryController::class, 'show']);
$router->get('/articles/{id}', [ArticleController::class, 'show']);
