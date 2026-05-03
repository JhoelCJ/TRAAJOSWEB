<?php
require_once 'rb.php';

$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$port = getenv('DB_PORT');

try {
    if (!R::testConnection()) {
        R::setup("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}
?>