<?php
/**
 * fix_final.php - Final fix: show raw hex + clear all text fields
 * Delete after use.
 */
include __DIR__ . '/noyau_backend/configuration/db.php';
$pdo->exec("SET NAMES utf8mb4");
$pdo->exec("SET CHARACTER SET utf8mb4");

// Show raw description values with length
$rows = $pdo->query("SELECT id, titre, LENGTH(description) as dlen, LENGTH(conditions_reservation) as clen, 
    SUBSTRING(description, 1, 150) as desc_preview,
    SUBSTRING(conditions_reservation, 1, 150) as cond_preview
    FROM menus ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Aperçu des champs texte :</h3>";
foreach ($rows as $r) {
    echo "<p><strong>ID {$r['id']} — {$r['titre']}</strong> | desc: {$r['dlen']} chars | cond: {$r['clen']} chars</p>";
    if ($r['clen'] > 0) {
        echo "<pre style='background:#fee;padding:5px'>[COND] " . htmlspecialchars($r['cond_preview']) . "</pre>";
    }
    if ($r['dlen'] > 200) {
        echo "<pre style='background:#ffe;padding:5px'>[DESC long] " . htmlspecialchars($r['desc_preview']) . "</pre>";
    }
}

// Force clear conditions_reservation regardless
$n = $pdo->exec("UPDATE menus SET conditions_reservation = ''");
echo "<p>✅ conditions_reservation forcé à vide pour $n lignes.</p>";

// Truncate any description longer than 500 chars (the bad text is appended)
$n2 = $pdo->exec("UPDATE menus SET description = LEFT(description, 500) WHERE LENGTH(description) > 500");
echo "<p>✅ $n2 descriptions tronquées à 500 chars.</p>";

echo "<p>Supprimez ce fichier.</p>";
?>
