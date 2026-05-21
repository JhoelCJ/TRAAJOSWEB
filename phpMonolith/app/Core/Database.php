<?php

declare(strict_types=1);

namespace App\Core;

use RedBeanPHP\R;

final class Database
{
    private static bool $connected = false;

    public static function loadEnv(string $path): void
    {
        if (!is_file($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines ?: [] as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
        }
    }

    public static function connect(): void
    {
        if (self::$connected) {
            return;
        }

        RedBean::load(APP_ROOT);

        $host = self::env('SUPABASE_DB_HOST');
        $port = self::env('SUPABASE_DB_PORT', '5432');
        $database = self::env('SUPABASE_DB_NAME', 'postgres');
        $user = self::env('SUPABASE_DB_USER');
        $password = self::env('SUPABASE_DB_PASSWORD');
        $sslmode = self::env('SUPABASE_DB_SSLMODE', 'require');

        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
            $host,
            $port,
            $database,
            $sslmode
        );

        R::setup($dsn, $user, $password);
        R::freeze(true);

        self::$connected = true;
    }

    private static function env(string $key, ?string $default = null): string
    {
        $value = $_ENV[$key] ?? getenv($key) ?: $default;

        if ($value === null || $value === '') {
            throw new \RuntimeException("Falta configurar {$key} en el archivo .env.");
        }

        return $value;
    }
}
