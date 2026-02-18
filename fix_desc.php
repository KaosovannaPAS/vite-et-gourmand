<?php
include __DIR__ . '/noyau_backend/configuration/db.php';
$pdo->exec("SET NAMES utf8mb4");

$rows = $pdo->query("SELECT id, titre, description FROM menus")->fetchAll(PDO::FETCH_ASSOC);

$fixed = 0;
foreach ($rows as $r) {
    $desc = $r['description'] ?? '';

    // Find any of the known bad phrases and cut everything from there
    $cutPhrases = [
        'Pour toute restitution',
        'En attente du retour',
        'Si du mat',
        '600',
        'CGV',
    ];

    $cutAt = strlen($desc); // default: no cut
    foreach ($cutPhrases as $phrase) {
        $pos = strpos($desc, $phrase);
        if ($pos !== false && $pos < $cutAt) {
            $cutAt = $pos;
        }
    }

    $clean = trim(substr($desc, 0, $cutAt));

    if ($clean !== $desc) {
        $stmt = $pdo->prepare("UPDATE menus SET description = ? WHERE id = ?");
        $stmt->execute([$clean, $r['id']]);
        $fixed++;
        echo "<p>✅ Menu <strong>{$r['titre']}</strong> nettoyé.<br>";
        echo "<small>Avant (" . strlen($desc) . " chars) → Après (" . strlen($clean) . " chars)</small></p>";
    }
}

if ($fixed === 0) {
    echo "<p>⚠️ Aucune description ne contenait le texte problématique.</p>";
    echo "<h3>Toutes les descriptions actuelles :</h3>";
    foreach ($rows as $r) {
        echo "<p><strong>ID {$r['id']} — {$r['titre']}</strong> (" . strlen($r['description']) . " chars):<br>";
        echo "<code>" . htmlspecialchars(substr($r['description'] ?? '', 0, 200)) . "</code></p>";
    }
}

echo "<p><em>Supprimez ce fichier.</em></p>";
?>
