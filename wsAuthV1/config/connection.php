<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$readEnv = static function (string $key, mixed $default = null): mixed {
    $value = getenv($key);
    if ($value !== false && $value !== '') {
        return $value;
    }

    $envPath = __DIR__ . '/../.env';
    if (!is_readable($envPath)) {
        return $default;
    }

    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$envKey, $envValue] = explode('=', $line, 2);
        if (trim($envKey) === $key) {
            return trim($envValue, " \t\n\r\0\x0B\"'");
        }
    }

    return $default;
};

$databaseUrl = $readEnv('DATABASE_URL');
if (!$databaseUrl) {
    throw new RuntimeException('DATABASE_URL is not configured.');
}

$capsule = new Capsule;

$capsule->addConnection([
    'driver'   => 'pgsql',
    'url'      => $databaseUrl,
    'charset'  => 'utf8',
    'prefix'   => '',
    'sslmode'  => 'require',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
