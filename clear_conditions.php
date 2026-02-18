<?php
/**
 * fix_conditions2.php - Check and clear conditions_reservation
 * Delete after use.
 */
include __DIR__ . '/noyau_backend/configuration/db.php';

// Show current values
$rows = $pdo->query("SELECT id, titre, LEFT(conditions_reservation, 80) as cond FROM menus")->fetchAll(PDO::FETCH_ASSOC);
echo "<h3>Valeurs actuelles :</h3><pre>";
foreach ($rows as $r) {
    echo "ID {$r['id']} | {$r['titre']} | " . var_export($r['cond'], true) . "\n";
}
echo "</pre>";

// Clear ALL non-empty values (NULL or empty string)
$n = $pdo->exec("UPDATE menus SET conditions_reservation = NULL");
echo "<p>✅ $n lignes vidées.</p>";

// Also check if the text is in description
$rows2 = $pdo->query("SELECT id, titre, LEFT(description, 100) as desc FROM menus WHERE description LIKE '%mat%'")->fetchAll(PDO::FETCH_ASSOC);
if ($rows2) {
    echo "<h3>Descriptions contenant 'mat' :</h3><pre>" . print_r($rows2, true) . "</pre>";
    // Clear those too
    $pdo->exec("UPDATE menus SET description = REGEXP_REPLACE(description, 'En attente du retour.*?(CGV)\\\\. ?', '') WHERE description LIKE '%mat%'");
    echo "<p>✅ Descriptions nettoyées.</p>";
}
echo "<p>Supprimez ce fichier.</p>";
?>
