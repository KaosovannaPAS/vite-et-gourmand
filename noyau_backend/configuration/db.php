<?php
// Protection contre les inclusions multiples de constantes
if (!defined('DB_CONFIG_LOADED')) {
    require_once __DIR__ . '/config.php';

    function env_get($key, $default = '')
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
        return ($val === '') ? $default : trim($val);
    }

    define('DB_HOST', env_get('MYSQLHOST', 'localhost'));
    define('DB_PORT', env_get('MYSQLPORT', '3306'));
    define('DB_NAME', env_get('MYSQLDATABASE', 'vite_et_gourmand'));
    define('DB_USER', env_get('MYSQLUSER', 'root'));
    define('DB_PASS', env_get('MYSQLPASSWORD', ''));
    define('DB_CONFIG_LOADED', true);
}

// Initialisation de $pdo si non existant
if (!isset($pdo) || $pdo === null) {
    $estLocal = (DB_HOST === 'localhost' || DB_HOST === '127.0.0.1');
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        if (!$estLocal) {
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        $GLOBALS['pdo'] = $pdo;
    }
    catch (PDOException $e) {
        if ($estLocal)
            die("Erreur de connexion : " . $e->getMessage());
        else {
            error_log("DB Connection Error: " . $e->getMessage());
            die("Erreur de connexion à la base de données.");
        }
    }
}
?>
