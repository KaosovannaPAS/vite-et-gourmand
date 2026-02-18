<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

echo "--- DIAGNOSTICS ---\n";

echo "MYSQLHOST (getenv): " . getenv('MYSQLHOST') . "\n";

$host = '130.162.54.212';
$port = '3306';
$db = 'freedb_Vite_Gourmand';
$user = 'freedb_Admin2026';
$pass = '9nm9WK@!Uzhn#C6';

echo "Testing PDO to $host...\n";
$dsn = "mysql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "PDO Connect: SUCCESS\n";

    // Test Query
    $stmt = $pdo->query("SELECT 1");
    if ($stmt) {
        echo "Query SELECT 1: SUCCESS\n";
    }
}
catch (PDOException $e) {
    echo "PDO Connect: FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n--- END ---\n";
?>
