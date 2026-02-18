<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain');

echo "Testing Connectivity...\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Host: " . getenv('MYSQLHOST') . "\n";
echo "Port: " . getenv('MYSQLPORT') . "\n";
echo "IP lookup for google.com: " . gethostbyname('google.com') . "\n";

$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT');

echo "IP lookup for $host: " . gethostbyname($host) . "\n";

echo "Trying fsockopen to $host:$port ...\n";
$fp = fsockopen($host, $port, $errno, $errstr, 5);
if (!$fp) {
    echo "ERROR: $errno - $errstr\n";
}
else {
    echo "SUCCESS: Connected to port $port\n";
    fclose($fp);
}

echo "\nTrying PDO...\n";
require_once __DIR__ . '/noyau_backend/configuration/db.php';
echo "PDO initialized if no error above.\n";
?>
