<?php
require_once __DIR__ . '/noyau_backend/configuration/db.php';

echo "--- MENUS ---\n";
$stmt = $pdo->query("SELECT id, titre, description FROM menus LIMIT 5");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}

echo "\n--- REVIEWS ---\n";
$stmt = $pdo->query("SELECT id, commentaire, valide FROM reviews LIMIT 5");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
?>
