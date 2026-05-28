<?php

$readEnv = static function (string $key, mixed $default = null): mixed {
    $value = getenv($key);
    if ($value !== false && $value !== '') {
        return $value;
    }

    static $fileValues = null;

    if ($fileValues === null) {
        $fileValues = [];
        $envPath = __DIR__ . '/../.env';

        if (is_readable($envPath)) {
            foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                    continue;
                }

                [$envKey, $envValue] = explode('=', $line, 2);
                $envKey = trim($envKey);
                $envValue = trim($envValue, " \t\n\r\0\x0B\"'");
                $fileValues[$envKey] = $envValue;
            }
        }
    }

    return $fileValues[$key] ?? $default;
};

return [
    'session_lifetime_seconds' => (int) $readEnv('SESSION_LIFETIME_SECONDS', 60),
    'oauth' => [
        'google' => [
            'client_id' => $readEnv('GOOGLE_CLIENT_ID', ''),
            'client_secret' => $readEnv('GOOGLE_CLIENT_SECRET', ''),
            'redirect_uri' => $readEnv('GOOGLE_REDIRECT_URI', ''),
            'authorization_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
            'token_url' => 'https://oauth2.googleapis.com/token',
            'user_url' => 'https://www.googleapis.com/oauth2/v3/userinfo',
            'scope' => 'openid email profile',
        ],
        'facebook' => [
            'client_id' => $readEnv('FACEBOOK_CLIENT_ID', ''),
            'client_secret' => $readEnv('FACEBOOK_CLIENT_SECRET', ''),
            'redirect_uri' => $readEnv('FACEBOOK_REDIRECT_URI', ''),
            'authorization_url' => 'https://www.facebook.com/dialog/oauth',
            'token_url' => 'https://graph.facebook.com/oauth/access_token',
            'user_url' => 'https://graph.facebook.com/me',
            'scope' => 'email,public_profile',
        ],
        'github' => [
            'client_id' => $readEnv('GITHUB_CLIENT_ID', ''),
            'client_secret' => $readEnv('GITHUB_CLIENT_SECRET', ''),
            'redirect_uri' => $readEnv('GITHUB_REDIRECT_URI', ''),
            'authorization_url' => 'https://github.com/login/oauth/authorize',
            'token_url' => 'https://github.com/login/oauth/access_token',
            'user_url' => 'https://api.github.com/user',
            'emails_url' => 'https://api.github.com/user/emails',
            'scope' => 'read:user user:email',
        ],
    ],
];
