<?php
// DIAGNOSTIC DB.PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['diag_db'])) {
    echo "DIAG: DB START\n";
    echo "DIAG: MYSQLHOST=" . getenv('MYSQLHOST') . "\n";
}

$h = getenv('MYSQLHOST') ?: 'localhost';
$u = getenv('MYSQLUSER') ?: 'root';
$p = getenv('MYSQLPASSWORD') ?: '';
$d = getenv('MYSQLDATABASE') ?: 'vite_et_gourmand';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    if (isset($_GET['diag_db']))
        echo "DIAG: TRYING PDO CONNECT TO $h...\n";
    $pdo = new PDO("mysql:host=$h;port=$port;dbname=$d;charset=utf8mb4", $u, $p, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ]);
    if (isset($_GET['diag_db']))
        die("DIAG: PDO CONNECTION SUCCESSFUL");
    $GLOBALS['pdo'] = $pdo;
}
catch (Exception $e) {
    die("DIAG: PDO ERROR: " . $e->getMessage());
}
?>
