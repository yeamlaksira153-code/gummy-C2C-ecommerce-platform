<?php
// Load environment variables from secret.env
$env_file = __DIR__ . '/../secret.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $value = trim($value, '"');
            putenv(trim($key) . '=' . $value);
        }
    }
}

// Database configuration
$db_host = getenv('DB_HOST') ?: 'sql207.infinityfree.com';
$db_port = getenv('DB_PORT') ?: '3306';
$db_user = getenv('DB_USER') ?: 'if0_41827976';
$db_pass = getenv('DB_PASS') ?: 'yamla1819';
$db_name = getenv('DB_NAME') ?:'if0_41827976_gummy';

try {
    $pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
