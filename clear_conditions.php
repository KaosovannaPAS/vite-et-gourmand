<?php
/**
 * fix_conditions4.php - Nuclear option: clear ALL text columns that contain the bad text
 * Delete after use.
 */
include __DIR__ . '/noyau_backend/configuration/db.php';
$pdo->exec("SET NAMES utf8mb4");

// Show raw values of all text columns
$rows = $pdo->query("SELECT id, titre, description, conditions_reservation FROM menus")->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Toutes les valeurs :</h3>";
foreach ($rows as $r) {
    $cond = $r['conditions_reservation'];
    $desc = $r['description'];
    echo "<p><strong>ID {$r['id']} — {$r['titre']}</strong></p>";
    echo "<p>conditions_reservation (" . strlen($cond) . " chars): <code>" . htmlspecialchars(substr($cond ?? '', 0, 100)) . "</code></p>";
    echo "<p>description (" . strlen($desc) . " chars): <code>" . htmlspecialchars(substr($desc ?? '', 0, 100)) . "</code></p>";
    echo "<hr>";
}

// Force-clear conditions_reservation for ALL rows (even empty string)
$n1 = $pdo->exec("UPDATE menus SET conditions_reservation = ''");
echo "<p>✅ conditions_reservation vidé pour $n1 lignes.</p>";

// Also clear from description using LIKE with the actual text
$n2 = $pdo->exec("UPDATE menus SET description = SUBSTRING_INDEX(description, 'En attente', 1) WHERE description LIKE '%En attente%'");
echo "<p>✅ description nettoyée pour $n2 lignes.</p>";

// Also try with the French text
$n3 = $pdo->exec("UPDATE menus SET description = SUBSTRING_INDEX(description, 'mat', 1) WHERE description LIKE '%600%'");
echo "<p>✅ description (600€) nettoyée pour $n3 lignes.</p>";

echo "<p>Supprimez ce fichier.</p>";
?>
