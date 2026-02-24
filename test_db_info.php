<?php
require 'noyau_backend/configuration/db.php';
echo "DB_NAME: " . DB_NAME . "<br>";
echo "DB_HOST: " . DB_HOST . "<br>";
echo "DB_USER: " . DB_USER . "<br>";
echo "DB_PORT: " . DB_PORT . "<br>";
$stmt = $pdo->query("SHOW COLUMNS FROM menus");
while ($row = $stmt->fetch()) {
    echo $row['Field'] . " - " . $row['Type'] . "<br>";
}
