<?php
header('Content-Type: application/json');

function test_conn($h, $u, $p, $d, $port)
{
    try {
        $pdo = new PDO("mysql:host=$h;port=$port;dbname=$d;charset=utf8mb4", $u, $p, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);
        return "SUCCESS";
    }
    catch (Exception $e) {
        return $e->getMessage();
    }
}

$h = trim(getenv('MYSQLHOST'));
$u = trim(getenv('MYSQLUSER'));
$p = trim(getenv('MYSQLPASSWORD'));
$d = trim(getenv('MYSQLDATABASE'));
$port = trim(getenv('MYSQLPORT')) ?: '4000';

$results = [
    'host_check' => [
        'length' => strlen($h),
        'start' => substr($h, 0, 3),
        'end' => substr($h, -3)
    ],
    'user_check' => [
        'length' => strlen($u),
        'start' => substr($u, 0, 3),
        'end' => substr($u, -3)
    ],
    'db_check' => [
        'length' => strlen($d),
        'value' => $d
    ],
    'port_check' => $port,
    'connection_test' => test_conn($h, $u, $p, $d, $port)
];

echo json_encode($results);
?>
