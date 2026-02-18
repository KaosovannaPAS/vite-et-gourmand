<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

echo "--- DIAGNOSTICS: CONNECT ---\n";

function env_get_debug($key, $default = '')
{
    $val = '';
    if (isset($_ENV[$key]) && $_ENV[$key] !== '')
        $val = $_ENV[$key];
    elseif (isset($_SERVER[$key]) && $_SERVER[$key] !== '')
        $val = $_SERVER[$key];
    else {
        $v = getenv($key);
        if ($v !== false && $v !== '')
            $val = $v;
    }

    if ($val === '')
        return $default;

    $trimmed = trim($val);
    echo "Key [$key]: Raw length " . strlen($val) . ", Trimmed length " . strlen($trimmed) . "\n";
    return $trimmed;
}

define('DB_HOST', env_get_debug('MYSQLHOST', 'localhost'));
define('DB_PORT', env_get_debug('MYSQLPORT', '3306'));
define('DB_NAME', env_get_debug('MYSQLDATABASE', 'vite_et_gourmand'));
define('DB_USER', env_get_debug('MYSQLUSER', 'root'));
$pass = env_get_debug('MYSQLPASSWORD', '');
define('DB_PASS', $pass);

echo "Host: " . DB_HOST . "\n";
echo "Port: " . DB_PORT . "\n";
echo "User: " . DB_USER . "\n";
echo "Pass: " . substr(DB_PASS, 0, 1) . "..." . substr(DB_PASS, -1) . "\n";

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "Connection SUCCESS!\n";

    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(", ", $tables) . "\n";


}
catch (PDOException $e) {
    echo "Connection FAILED: " . $e->getMessage() . "\n";
}

echo "\n--- END ---\n";
?>
