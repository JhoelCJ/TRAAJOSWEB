<?php
namespace App\Services;

class OAuthService {
    public function __construct(private array $config) {}

    public function getAuthorizationUrl(string $provider, string $state): string {
        $providerConfig = $this->providerConfig($provider);

        $params = [
            'client_id' => $providerConfig['client_id'],
            'redirect_uri' => $this->redirectUri($provider),
            'response_type' => 'code',
            'scope' => $providerConfig['scope'],
            'state' => $state,
        ];

        if ($provider === 'google') {
            $params['access_type'] = 'online';
            $params['prompt'] = 'select_account';
        }

        return $providerConfig['authorization_url'] . '?' . http_build_query($params);
    }

    public function getUserProfile(string $provider, string $code): array {
        $providerConfig = $this->providerConfig($provider);
        $tokenPayload = [
            'client_id' => $providerConfig['client_id'],
            'client_secret' => $providerConfig['client_secret'],
            'code' => $code,
            'redirect_uri' => $this->redirectUri($provider),
            'grant_type' => 'authorization_code',
        ];

        $tokenResponse = $this->postForm($providerConfig['token_url'], $tokenPayload);
        $accessToken = $tokenResponse['access_token'] ?? null;

        if (!$accessToken) {
            throw new \RuntimeException('The provider did not return an access token.');
        }

        return match ($provider) {
            'google' => $this->googleProfile($providerConfig, $accessToken),
            'facebook' => $this->facebookProfile($providerConfig, $accessToken),
            'github' => $this->githubProfile($providerConfig, $accessToken),
            default => throw new \InvalidArgumentException('Unsupported OAuth provider.'),
        };
    }

    public function assertConfigured(string $provider): void {
        $providerConfig = $this->providerConfig($provider);

        if (empty($providerConfig['client_id']) || empty($providerConfig['client_secret'])) {
            throw new \RuntimeException(ucfirst($provider) . ' OAuth credentials are not configured.');
        }
    }

    private function googleProfile(array $providerConfig, string $accessToken): array {
        $data = $this->getJson($providerConfig['user_url'], [
            'Authorization: Bearer ' . $accessToken,
        ]);

        return [
            'provider' => 'google',
            'provider_id' => (string) ($data['sub'] ?? ''),
            'name' => $data['name'] ?? $data['email'] ?? 'Google User',
            'email' => $data['email'] ?? null,
            'avatar_url' => $data['picture'] ?? null,
        ];
    }

    private function facebookProfile(array $providerConfig, string $accessToken): array {
        $url = $providerConfig['user_url'] . '?' . http_build_query([
            'fields' => 'id,name,email,picture',
            'access_token' => $accessToken,
        ]);
        $data = $this->getJson($url);

        return [
            'provider' => 'facebook',
            'provider_id' => (string) ($data['id'] ?? ''),
            'name' => $data['name'] ?? $data['email'] ?? 'Facebook User',
            'email' => $data['email'] ?? null,
            'avatar_url' => $data['picture']['data']['url'] ?? null,
        ];
    }

    private function githubProfile(array $providerConfig, string $accessToken): array {
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/vnd.github+json',
            'User-Agent: BiconoirsGourmet',
        ];
        $data = $this->getJson($providerConfig['user_url'], $headers);
        $email = $data['email'] ?? null;

        if (!$email) {
            $emails = $this->getJson($providerConfig['emails_url'], $headers);
            foreach ($emails as $candidate) {
                if (!empty($candidate['primary']) && !empty($candidate['verified']) && !empty($candidate['email'])) {
                    $email = $candidate['email'];
                    break;
                }
            }
        }

        return [
            'provider' => 'github',
            'provider_id' => (string) ($data['id'] ?? ''),
            'name' => $data['name'] ?? $data['login'] ?? 'GitHub User',
            'email' => $email,
            'avatar_url' => $data['avatar_url'] ?? null,
        ];
    }

    private function providerConfig(string $provider): array {
        if (!isset($this->config[$provider])) {
            throw new \InvalidArgumentException('Unsupported OAuth provider.');
        }

        return $this->config[$provider];
    }

    private function redirectUri(string $provider): string {
        $configuredUri = $this->config[$provider]['redirect_uri'] ?? '';
        if ($configuredUri !== '') {
            return $configuredUri;
        }

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $scheme . '://' . $host . '/index.php?action=oauth_callback&provider=' . urlencode($provider);
    }

    private function postForm(string $url, array $payload): array {
        return $this->requestJson($url, [
            'method' => 'POST',
            'headers' => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
            ],
            'content' => http_build_query($payload),
        ]);
    }

    private function getJson(string $url, array $headers = []): array {
        return $this->requestJson($url, [
            'method' => 'GET',
            'headers' => $headers,
        ]);
    }

    private function requestJson(string $url, array $options): array {
        if (function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => $options['method'],
                CURLOPT_HTTPHEADER => $options['headers'] ?? [],
                CURLOPT_POSTFIELDS => $options['content'] ?? null,
                CURLOPT_TIMEOUT => 15,
            ]);

            $response = curl_exec($curl);
            $statusCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $error = curl_error($curl);
            curl_close($curl);

            if ($response === false) {
                throw new \RuntimeException($error ?: 'OAuth request failed.');
            }

            return $this->decodeResponse($response, $statusCode);
        }

        $context = stream_context_create([
            'http' => [
                'method' => $options['method'],
                'header' => implode("\r\n", $options['headers'] ?? []),
                'content' => $options['content'] ?? null,
                'timeout' => 15,
            ],
        ]);

        $response = file_get_contents($url, false, $context);
        $statusCode = 200;

        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches)) {
            $statusCode = (int) $matches[1];
        }

        if ($response === false) {
            throw new \RuntimeException('OAuth request failed.');
        }

        return $this->decodeResponse($response, $statusCode);
    }

    private function decodeResponse(string $response, int $statusCode): array {
        $data = json_decode($response, true);

        if (!is_array($data)) {
            throw new \RuntimeException('The provider returned an invalid response.');
        }

        if ($statusCode < 200 || $statusCode >= 300) {
            $message = $data['error_description'] ?? $data['error']['message'] ?? $data['message'] ?? 'OAuth request was rejected.';
            throw new \RuntimeException($message);
        }

        return $data;
    }
}
