<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class User extends Model {
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'password_hash',
        'role',
        'auth_provider',
        'provider_id',
        'avatar_url'
    ];

    public static function getAll() {
        return self::all()->toArray();
    }

    public static function add($data) {
        $userId = $data['user_id'] ?? 'u_' . bin2hex(random_bytes(4));

        $passwordHash = $data['password_hash'] ?? null;
        if (!$passwordHash && isset($data['password'])) {
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $userData = self::filterColumns([
            'user_id' => $userId,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password_hash' => $passwordHash,
            'role' => $data['role'] ?? 'customer'
        ]);

        $user = self::create($userData);

        return $user->user_id;
    }

    public static function authenticate($email, $password) {
        $user = self::where('email', $email)
                    ->first();

        if (!$user) {
            return null;
        }

        $passwordHash = $user->password_hash ?? '';
        $isValidHash = password_verify($password, $passwordHash);
        $isLegacyPlainText = hash_equals((string) $passwordHash, (string) $password);

        return ($isValidHash || $isLegacyPlainText) ? $user->toArray() : null;
    }

    public static function findOrCreateFromOAuth(array $profile): array {
        if (empty($profile['email'])) {
            throw new \RuntimeException('The provider did not share an email address.');
        }

        $columns = self::tableColumns();
        $user = null;

        if (in_array('auth_provider', $columns, true) && in_array('provider_id', $columns, true)) {
            $user = self::where('auth_provider', $profile['provider'])
                ->where('provider_id', $profile['provider_id'])
                ->first();
        }

        if (!$user) {
            $user = self::where('email', $profile['email'])->first();
        }

        $userData = self::filterColumns([
            'name' => $profile['name'],
            'email' => $profile['email'],
            'password_hash' => password_hash(bin2hex(random_bytes(24)), PASSWORD_DEFAULT),
            'role' => 'customer',
            'auth_provider' => $profile['provider'],
            'provider_id' => $profile['provider_id'],
            'avatar_url' => $profile['avatar_url'] ?? null,
        ]);

        if ($user) {
            unset($userData['password_hash'], $userData['role']);
            $user->fill($userData);
            $user->save();
            return $user->fresh()->toArray();
        }

        $userData['user_id'] = 'u_' . bin2hex(random_bytes(4));
        return self::create($userData)->toArray();
    }

    private static function filterColumns(array $data): array {
        $columns = self::tableColumns();

        return array_filter(
            $data,
            static fn($key) => in_array($key, $columns, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    private static function tableColumns(): array {
        static $columns = null;

        if ($columns !== null) {
            return $columns;
        }

        try {
            $columns = DB::schema()->getColumnListing('users');
        } catch (\Throwable $exception) {
            $columns = ['user_id', 'name', 'email', 'phone', 'password_hash', 'role'];
        }

        return $columns;
    }
}
