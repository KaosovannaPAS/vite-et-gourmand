<?php
include __DIR__ . '/noyau_backend/configuration/db.php';
$pdo->exec("SET NAMES utf8mb4");

echo "<h1>Diagnostic Base de Données</h1>";

$rows = $pdo->query("SELECT id, titre, length(conditions_reservation) as cond_len, conditions_reservation, description FROM menus")->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $r) {
    echo "<div style='border:1px solid #ccc; margin:10px; padding:10px;'>";
    echo "<h3>ID {$r['id']} : {$r['titre']}</h3>";

    // Check conditions
    echo "<strong>Conditions (Len: {$r['cond_len']}):</strong> ";
    if (!empty($r['conditions_reservation'])) {
        echo "<span style='color:red;'>" . htmlspecialchars($r['conditions_reservation']) . "</span>";
    }
    else {
        echo "<span style='color:green;'>VIDE</span>";
    }

    echo "<br><br>";

    // Check description
    echo "<strong>Description:</strong><br>";
    $desc = $r['description'];
    if (strpos($desc, 'En attente') !== false || strpos($desc, '600') !== false) {
        echo "<div style='color:red; background:#ffebeb; padding:5px; white-space:pre-wrap;'>" . htmlspecialchars($desc) . "</div>";
    }
    else {
        echo "<div style='color:green; padding:5px; white-space:pre-wrap;'>" . htmlspecialchars($desc) . "</div>";
    }
    echo "</div>";
}
?>
