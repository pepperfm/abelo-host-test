<?php

declare(strict_types=1);

use Core\HttpException;
use Core\Request;
use Core\Router;

$root = dirname(__DIR__);
$head = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'HEAD';

try {
    require "$root/app/bootstrap.php";

    try {
        $response = Router::load("$root/routes.php")->direct(Request::uri(), Request::method());
    } catch (HttpException $e) {
        $response = view('error', [
            'title' => $e->status . ' — Лист',
            'status' => $e->status,
            'message' => $e->getMessage(),
            'page_type' => 'error',
            'robots' => 'noindex, nofollow',
        ], $e->status, $e->headers);
    }

    $response->send($head);
} catch (Throwable $e) {
    error_log((string) $e);
    // This fallback works even when Composer, MySQL or Smarty is unavailable.
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
    header("Content-Security-Policy: default-src 'none'; style-src 'self'; img-src 'self'; base-uri 'none'; frame-ancestors 'none'");
    if (!$head) {
        readfile("$root/resources/errors/500.html");
    }
}
