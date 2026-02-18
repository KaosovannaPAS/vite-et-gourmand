<?php
/**
 * fix_conditions3.php - Find and remove "retour de matériel" text from menu descriptions
 * Delete after use.
 */
include __DIR__ . '/noyau_backend/configuration/db.php';

// Show all menu descriptions to find the culprit
$rows = $pdo->query("SELECT id, titre, description FROM menus")->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Descriptions des menus :</h3>";
$found = false;
foreach ($rows as $r) {
    if (stripos($r['description'], 'mat') !== false || stripos($r['description'], 'retour') !== false || strlen($r['description']) > 200) {
        echo "<p><strong>ID {$r['id']} — {$r['titre']}</strong></p>";
        echo "<pre style='background:#fee;padding:10px;font-size:12px'>" . htmlspecialchars(substr($r['description'], 0, 500)) . "</pre>";
        $found = true;
    }
}
if (!$found)
    echo "<p>Aucune description suspecte trouvée.</p>";

// Truncate descriptions that are too long (keep only first 300 chars before the bad text)
$updated = 0;
foreach ($rows as $r) {
    $pos = stripos($r['description'], 'En attente du retour');
    if ($pos !== false) {
        $clean = trim(substr($r['description'], 0, $pos));
        $stmt = $pdo->prepare("UPDATE menus SET description = ? WHERE id = ?");
        $stmt->execute([$clean, $r['id']]);
        $updated++;
        echo "<p>✅ Menu ID {$r['id']} nettoyé.</p>";
    }
}

if ($updated === 0) {
    echo "<p>⚠️ Texte non trouvé dans les descriptions. Vérification des autres colonnes...</p>";
    // Show all columns
    $cols = $pdo->query("DESCRIBE menus")->fetchAll(PDO::FETCH_COLUMN);
    echo "<pre>" . implode(', ', $cols) . "</pre>";

    // Check all text columns
    foreach ($rows as $r) {
        echo "<p><strong>Menu {$r['id']} — {$r['titre']}</strong> | desc length: " . strlen($r['description']) . "</p>";
    }
}

echo "<p>Supprimez ce fichier.</p>";
?>
