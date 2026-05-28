<?php
namespace App\Support;

class SessionManager {
    private static int $lifetimeSeconds = 60;

    public static function configure(int $lifetimeSeconds): void {
        self::$lifetimeSeconds = max(1, $lifetimeSeconds);
    }

    public static function login(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user'] = $user;
        $_SESSION['authenticated_at'] = time();
        $_SESSION['last_activity_at'] = time();
        unset($_SESSION['session_expired_at'], $_SESSION['session_ended_at']);
    }

    public static function logout(): void {
        self::clearUser();
        $_SESSION['session_ended_at'] = time();
        session_regenerate_id(true);
    }

    public static function clearUser(): void {
        unset($_SESSION['user'], $_SESSION['authenticated_at'], $_SESSION['last_activity_at']);
    }

    public static function hasActiveUser(): bool {
        if (empty($_SESSION['user'])) {
            return false;
        }

        if (self::isExpired()) {
            self::expire();
            return false;
        }

        $_SESSION['last_activity_at'] = time();
        return true;
    }

    public static function requireUser(string $redirectAction = 'home'): void {
        if (self::hasActiveUser()) {
            return;
        }

        self::redirectToExpired($redirectAction);
    }

    public static function requireRole(array $roles, string $redirectAction = 'home'): void {
        self::requireUser($redirectAction);

        $userRole = $_SESSION['user']['role'] ?? '';
        if (!in_array($userRole, $roles, true)) {
            header('Location: index.php?action=login');
            exit();
        }
    }

    public static function redirectAfterLogin(string $fallback = 'home'): string {
        return self::sanitizeAction($_GET['redirect'] ?? $_POST['redirect'] ?? $_SESSION['oauth_redirect_after_login'] ?? $fallback, $fallback);
    }

    public static function sanitizeAction(?string $action, string $fallback = 'home'): string {
        if (!$action || !preg_match('/^[a-zA-Z0-9_]+$/', $action)) {
            return $fallback;
        }

        return $action;
    }

    public static function getLifetimeSeconds(): int {
        return self::$lifetimeSeconds;
    }

    public static function startOAuth(string $provider, string $redirectAction): string {
        $state = bin2hex(random_bytes(24));
        $_SESSION['oauth_state'] = $state;
        $_SESSION['oauth_provider'] = $provider;
        $_SESSION['oauth_redirect_after_login'] = self::sanitizeAction($redirectAction);

        return $state;
    }

    public static function validateOAuthState(string $provider, ?string $state): bool {
        return !empty($state)
            && hash_equals($_SESSION['oauth_state'] ?? '', $state)
            && hash_equals($_SESSION['oauth_provider'] ?? '', $provider);
    }

    public static function finishOAuth(): void {
        unset($_SESSION['oauth_state'], $_SESSION['oauth_provider']);
    }

    public static function redirectToExpired(string $redirectAction = 'home'): void {
        $redirectAction = self::sanitizeAction($redirectAction);
        header('Location: index.php?action=session_expired&redirect=' . urlencode($redirectAction));
        exit();
    }

    private static function isExpired(): bool {
        $lastActivityAt = (int) ($_SESSION['last_activity_at'] ?? $_SESSION['authenticated_at'] ?? 0);
        return $lastActivityAt > 0 && (time() - $lastActivityAt) >= self::$lifetimeSeconds;
    }

    private static function expire(): void {
        self::clearUser();
        $_SESSION['session_expired_at'] = time();
    }
}
