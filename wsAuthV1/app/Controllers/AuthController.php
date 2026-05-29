<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\OAuthService;
use App\Support\SessionManager;

class AuthController {
    public function login() {
        $redirectAction = SessionManager::redirectAfterLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $redirectAction = SessionManager::sanitizeAction($_POST['redirect'] ?? 'home');

            $user = User::authenticate($email, $password);

            if ($user) {
                SessionManager::login($user);
                header('Location: index.php?action=' . urlencode($redirectAction));
                exit();
            } else {
                $error = "Credenciales incorrectas";
            }
        }
        require_once __DIR__ . '/../Views/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = User::add([
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'] ?? null,
                'password' => $_POST['password'],
                'role' => 'customer'
            ]);

            SessionManager::login([
                'user_id' => $userId,
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => 'customer'
            ]);
            header('Location: index.php?action=home');
            exit();
        }
        require_once __DIR__ . '/../Views/register.php';
    }

    public function logout() {
        SessionManager::logout();
        header('Location: index.php?action=home');
        exit();
    }

    public function redirectToProvider(string $provider) {
        $redirectAction = SessionManager::sanitizeAction($_GET['redirect'] ?? 'home');

        try {
            $oauth = $this->oauthService();
            $oauth->assertConfigured($provider);
            $state = SessionManager::startOAuth($provider, $redirectAction);
            header('Location: ' . $oauth->getAuthorizationUrl($provider, $state));
            exit();
        } catch (\Throwable $exception) {
            $error = $exception->getMessage();
            require_once __DIR__ . '/../Views/login.php';
        }
    }

    public function handleProviderCallback(string $provider) {
        $redirectAction = SessionManager::sanitizeAction($_SESSION['oauth_redirect_after_login'] ?? 'home');

        try {
            if (!SessionManager::validateOAuthState($provider, $_GET['state'] ?? null)) {
                throw new \RuntimeException('Invalid OAuth state.');
            }

            if (empty($_GET['code'])) {
                throw new \RuntimeException('The provider did not return an authorization code.');
            }

            $profile = $this->oauthService()->getUserProfile($provider, $_GET['code']);
            $user = User::findOrCreateFromOAuth($profile);
            SessionManager::finishOAuth();
            SessionManager::login($user);

            header('Location: index.php?action=' . urlencode($redirectAction));
            exit();
        } catch (\Throwable $exception) {
            SessionManager::finishOAuth();
            $error = 'No pudimos iniciar sesion con ' . ucfirst($provider) . ': ' . $exception->getMessage();
            require_once __DIR__ . '/../Views/login.php';
        }
    }

    public function sessionExpired() {
        SessionManager::clearUser();
        $redirectAction = SessionManager::sanitizeAction($_GET['redirect'] ?? 'home');
        require_once __DIR__ . '/../Views/session_expired.php';
    }

    private function oauthService(): OAuthService {
        $config = require __DIR__ . '/../../config/auth.php';
        return new OAuthService($config['oauth'] ?? []);
    }
}
