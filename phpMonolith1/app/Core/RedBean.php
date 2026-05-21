<?php

declare(strict_types=1);

namespace App\Core;

final class RedBean
{
    private static bool $loaded = false;

    public static function load(string $root): void
    {
        if (self::$loaded) {
            return;
        }

        $configuredFile = $_ENV['REDBEAN_FILE'] ?? getenv('REDBEAN_FILE') ?: null;
        $candidates = array_filter([
            $configuredFile,
            $root . '/vendor/rb.php',
            $root . '/vendor/gabordemooij/redbean/rb.php',
            $root . '/lib/rb.php',
            $root . '/rb.php',
        ]);

        foreach ($candidates as $file) {
            $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, (string) $file);

            if (is_file($path)) {
                require_once $path;
                self::normalizeFacade();
                self::$loaded = true;
                return;
            }
        }

        throw new \RuntimeException(
            'No se encontro rb.php. Copialo en vendor/rb.php o configura REDBEAN_FILE en .env.'
        );
    }

    private static function normalizeFacade(): void
    {
        if (class_exists('RedBeanPHP\\R')) {
            return;
        }

        if (class_exists('R')) {
            class_alias('R', 'RedBeanPHP\\R');
            return;
        }

        throw new \RuntimeException('El archivo rb.php no cargo la clase R de RedBeanPHP.');
    }
}
