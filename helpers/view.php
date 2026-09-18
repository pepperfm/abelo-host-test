<?php

declare(strict_types=1);

use Core\App;
use Core\Response;
use Core\View;

/**
 * @param array<string, mixed> $data
 * @param array<string, string> $headers
 */
function view(string $name, array $data = [], int $status = 200, array $headers = []): Response
{
    /** @var View $renderer */
    $renderer = App::get('view');

    return new Response($renderer->render("$name.tpl", $data), $status, $headers);
}
