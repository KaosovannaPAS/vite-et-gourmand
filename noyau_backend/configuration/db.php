<?php
// UNCONDITIONAL DIAGNOSTIC DB.PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "DIAG_START_REALLY_UNCONDITIONAL\n";

$h = getenv('MYSQLHOST') ?: 'localhost';
$u = getenv('MYSQLUSER') ?: 'root';
$p = getenv('MYSQLPASSWORD') ?: '';
$d = getenv('MYSQLDATABASE') ?: 'vite_et_gourmand';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    echo "DIAG: TRYING PDO CONNECT TO $h...\n";
    $pdo = new PDO("mysql:host=$h;port=$port;dbname=$d;charset=utf8mb4", $u, $p, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ]);
    echo "DIAG: PDO CONNECTION SUCCESSFUL\n";
    $GLOBALS['pdo'] = $pdo;
    $pdo_global_check = $pdo;
}
catch (Exception $e) {
    die("DIAG: PDO ERROR: " . $e->getMessage());
}
?>
