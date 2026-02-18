<?php
// Configuration base de données
function env_get($key, $default = '')
{
    if (isset($_ENV[$key]) && $_ENV[$key] !== '')
        return $_ENV[$key];
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '')
        return $_SERVER[$key];
    $val = getenv($key);
    if ($val !== false && $val !== '')
        return $val;
    return $default;
}

define('DB_HOST', env_get('MYSQLHOST', 'localhost'));
define('DB_PORT', env_get('MYSQLPORT', '3306'));
define('DB_NAME', env_get('MYSQLDATABASE', 'vite_et_gourmand'));
define('DB_USER', env_get('MYSQLUSER', 'root'));
define('DB_PASS', env_get('MYSQLPASSWORD', ''));

$estLocal = (DB_HOST === 'localhost');

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    // Aiven requiert SSL en production
    if (!$estLocal) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        $options[PDO::MYSQL_ATTR_SSL_CA] = '';
    }

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
}
catch (PDOException $e) {
    if ($estLocal) {
        die("Erreur de connexion : " . $e->getMessage());
    }
    else {
        die("Erreur de connexion à la base de données. " . $e->getMessage());
    }
}
?>
