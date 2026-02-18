<?php
// Configuration base de données
// Vercel expose les env vars via $_ENV, $_SERVER ou getenv()
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

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    $estLocal = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost');
    if ($estLocal) {
        die("Erreur de connexion : " . $e->getMessage());
    }
    else {
        // En production, afficher l'hôte pour debug (à retirer après)
        die("Erreur de connexion à la base de données. Host: " . DB_HOST . " DB: " . DB_NAME);
    }
}
?>
