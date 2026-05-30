<?php
// tests/bootstrap.php
// Bootstrap file para PHPUnit

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir ruta raíz
define('ROOT_DIR', dirname(__DIR__));
define('APP_DIR', ROOT_DIR . '/app');
define('CONFIG_DIR', ROOT_DIR . '/config');

// Cargar autoloader de Composer
require_once ROOT_DIR . '/vendor/autoload.php';

// Cargar configuración
require_once CONFIG_DIR . '/connection.php';

// Registrar namespace autoload para la aplicación
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = APP_DIR . '/' . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Funciones de helper para tests
function createTestUser($email = null, $password = null) {
    $email = $email ?? 'test' . time() . '@example.com';
    $password = $password ?? 'TestPassword123';

    $user = new \App\Models\User();
    return $user->create([
        'name' => 'Test User',
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'phone' => '3001234567'
    ]);
}

function createTestDish($name = null) {
    $name = $name ?? 'Test Dish ' . time();

    $dish = new \App\Models\Dish();
    return $dish->create([
        'name' => $name,
        'description' => 'Test description',
        'price' => 15.50,
        'category' => 'Main Course'
    ]);
}

function createTestOrder($userId = null, $items = []) {
    $userId = $userId ?? 1;

    $order = new \App\Models\Order();
    return $order->create([
        'user_id' => $userId,
        'items' => $items ?: [[1, 2], [2, 1]],
        'total' => 35.50
    ]);
}

function cleanTestData() {
    // Limpiar datos de prueba de la BD
    // Implementar según tu estructura de BD
}
