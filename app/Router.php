<?php

declare(strict_types=1);

namespace Core;

use LogicException;

/* GET routes and their HEAD equivalents; {id} is a positive numeric ID. */
final class Router
{
    /**
     * @var list<array{
     *     pattern: string,
     *     action: array{
     *         0: class-string,
     *         1: string
     *     }
     * }>
     */
    private array $routes = [];

    public static function load(string $file): self
    {
        $router = new self();
        require $file;

        return $router;
    }

    /** @param array{0: class-string, 1: string} $action */
    public function get(string $uri, array $action): void
    {
        $pattern = str_replace('\{id\}', '([1-9][0-9]{0,9})', preg_quote($uri, '~'));
        $this->routes[] = ['pattern' => '~^' . $pattern . '$~D', 'action' => $action];
    }

    public function direct(string $uri, string $method): Response
    {
        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $uri, $matches) !== 1) {
                continue;
            }
            if (!in_array($method, ['GET', 'HEAD'], true)) {
                throw new HttpException(405, 'Этот адрес поддерживает только GET и HEAD.', ['Allow' => 'GET, HEAD']);
            }

            [$class, $action] = $route['action'];
            $controller = new $class();
            if (!is_callable([$controller, $action])) {
                throw new LogicException('Controller action not found: ' . $class . '::' . $action);
            }

            $parameters = array_map('intval', array_slice($matches, 1));
            return $controller->$action(...$parameters);
        }

        throw new HttpException(404, 'Такой страницы нет. Возможно, ссылка устарела или в адресе есть опечатка.');
    }
}
