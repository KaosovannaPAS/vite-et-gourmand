<?php
// Configuration base de données
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

    // Si aucune valeur trouvée, retourner défaut
    if ($val === '')
        return $default;

    // Important: nettoyer les retours à la ligne qui peuvent casser la connexion
    return trim($val);
}

define('DB_HOST', env_get('MYSQLHOST', 'localhost'));
define('DB_PORT', env_get('MYSQLPORT', '3306'));
define('DB_NAME', env_get('MYSQLDATABASE', 'vite_et_gourmand'));
define('DB_USER', env_get('MYSQLUSER', 'root'));
define('DB_PASS', env_get('MYSQLPASSWORD', ''));

$estLocal = (DB_HOST === 'localhost' || DB_HOST === '127.0.0.1');

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
}
catch (PDOException $e) {
    if ($estLocal) {
        die("Erreur de connexion : " . $e->getMessage());
    }
    else {
        // En prod, on log l'erreur mais on affiche un message générique pour sécu
        error_log("DB Connection Error: " . $e->getMessage());
        die("Erreur de connexion à la base de données.");
    }
}
?>
