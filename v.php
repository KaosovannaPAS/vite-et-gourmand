<?php
// v.php - DIAGNOSTIC ENTRY POINT
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Checking DB connection...\n";
require_once __DIR__ . '/noyau_backend/configuration/db.php';

if (isset($pdo)) {
    echo "SUCCESS: PDO is initialized.\n";
}
else {
    echo "FAILURE: PDO is still null.\n";
}
?>
