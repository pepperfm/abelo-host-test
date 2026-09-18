<?php

declare(strict_types=1);

namespace Core\Database;

use PDO;
use Pdo\Mysql;
use InvalidArgumentException;

final class Connection
{
    /**
     * @param array{
     *     host: string,
     *     port: int,
     *     name: string,
     *     username: string,
     *     password: string
     * } $config
     */
    public static function create(array $config): PDO
    {
        // DSN pieces are configuration, never request parameters.
        foreach (['host', 'name'] as $key) {
            if (preg_match('/[;\x00-\x1f]/', $config[$key])) {
                throw new InvalidArgumentException('Invalid database configuration.');
            }
        }

        $pdo = new PDO(
            sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['name']),
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
                Mysql::ATTR_MULTI_STATEMENTS => false,
            ],
        );
        $pdo->exec("SET time_zone = '+00:00'");

        return $pdo;
    }
}
