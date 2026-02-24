<?php
// Configuration base de données
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
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    // Configuration SSL pour TiDB / Aiven en production
    if (!$estLocal) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;

        // Tentative de détection du CA du système (Vercel tourne sur Amazon Linux 2 ou similaire)
        $ca_paths = [
            '/etc/pki/tls/certs/ca-bundle.crt', // Amazon Linux / RHEL / CentOS
            '/etc/ssl/certs/ca-certificates.crt', // Debian / Ubuntu
            '/etc/ssl/ca-bundle.pem', // OpenSUSE
            '/usr/local/share/certs/ca-root-nss.crt', // FreeBSD
        ];

        $ca_found = false;
        foreach ($ca_paths as $path) {
            if (file_exists($path)) {
                $options[PDO::MYSQL_ATTR_SSL_CA] = $path;
                $ca_found = true;
                break;
            }
        }

        // Si aucun CA trouvé, on désactive la vérification (moins sécurisé mais fonctionnel)
        // TiDB Serverless requiert SSL, donc si on ne trouve pas de CA, on laisse vide (le système peut le gérer)
        // ou on met verify à false selon le driver.
        if (!$ca_found) {
            // Fallback: On espère que le driver utilise le store système par défaut ou on désactive la vérif stricte
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        // $options[PDO::MYSQL_ATTR_SSL_CA] = ''; // On laisse vide
        }
    }

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    $GLOBALS['pdo'] = $pdo;
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
