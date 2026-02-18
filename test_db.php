<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

echo "--- DIAGNOSTICS: TABLES ---\n";

require_once __DIR__ . '/noyau_backend/configuration/db.php';

try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "No tables found in database.\n";
    }
    else {
        echo "Tables found:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    }

    // Check specific tables needed for index.php
    echo "\nChecking 'reviews' table count:\n";
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
        echo "Reviews count: $count\n";
    }
    catch (Exception $e) {
        echo "Error querying reviews: " . $e->getMessage() . "\n";
    }

}
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}

echo "\n--- END ---\n";
?>
