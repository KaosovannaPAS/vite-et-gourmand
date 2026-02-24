<?php

$h = getenv('MYSQLHOST') ?: 'localhost';
$u = getenv('MYSQLUSER') ?: 'root';
$p = getenv('MYSQLPASSWORD') ?: '';
$d = getenv('MYSQLDATABASE') ?: 'vite_et_gourmand';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    $pdo = new PDO("mysql:host=$h;port=$port;dbname=$d;charset=utf8mb4", $u, $p, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ]);
    $GLOBALS['pdo'] = $pdo;
    $pdo_global_check = $pdo;
}
catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed", "details" => $e->getMessage()]);
    exit;
}
?>
