<?php
include __DIR__ . '/noyau_backend/configuration/db.php';
$pdo->exec("SET NAMES utf8mb4");

// Just show all current descriptions in full
$rows = $pdo->query("SELECT id, titre, description FROM menus ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Descriptions actuelles complètes</h2>";
foreach ($rows as $r) {
    echo "<div style='border:1px solid #ccc;margin:10px;padding:10px;'>";
    echo "<strong>ID {$r['id']} — {$r['titre']}</strong> (" . strlen($r['description']) . " chars)<br>";
    echo "<pre style='white-space:pre-wrap;background:#f5f5f5;padding:8px;'>" . htmlspecialchars($r['description'] ?? '') . "</pre>";
    echo "</div>";
}
?>
