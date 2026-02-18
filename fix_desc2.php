<?php
include __DIR__ . '/noyau_backend/configuration/db.php';
$pdo->exec("SET NAMES utf8mb4");

$rows = $pdo->query("SELECT id, titre, description FROM menus")->fetchAll(PDO::FETCH_ASSOC);

// All possible cut phrases - cut description at the FIRST occurrence of any of these
$cutPhrases = [
    'En attente du retour',
    'Pour toute restitution',
    'Si du mat',
    '600',
    'CGV',
    'matériel',
    'mat\u00e9riel', // URL-encoded variant
    'restitution',
    'prêté',
    'pr\u00eat\u00e9',
];

$fixed = 0;
echo "<h2>Nettoyage des descriptions</h2>";

foreach ($rows as $r) {
    $desc = $r['description'] ?? '';
    $original = $desc;

    // Find earliest cut point
    $cutAt = mb_strlen($desc);
    foreach ($cutPhrases as $phrase) {
        // Case-insensitive search
        $pos = mb_stripos($desc, $phrase);
        if ($pos !== false && $pos < $cutAt && $pos > 5) {
            // Only cut if there's meaningful content before it
            $cutAt = $pos;
        }
    }

    $clean = trim(mb_substr($desc, 0, $cutAt));

    // Remove trailing punctuation/connectors
    $clean = rtrim($clean, ' .,;:-');

    if ($clean !== $original) {
        $stmt = $pdo->prepare("UPDATE menus SET description = ? WHERE id = ?");
        $stmt->execute([$clean, $r['id']]);
        $fixed++;
        echo "<p>✅ <strong>{$r['titre']}</strong><br>";
        echo "<small>Avant: " . htmlspecialchars(mb_substr($original, 0, 150)) . "...</small><br>";
        echo "<small>Après: <em>" . htmlspecialchars($clean) . "</em></small></p>";
    }
    else {
        echo "<p>⬜ <strong>{$r['titre']}</strong> — OK (" . mb_strlen($desc) . " chars): <small>" . htmlspecialchars(mb_substr($desc, 0, 100)) . "</small></p>";
    }
}

echo "<hr><p><strong>$fixed menus nettoyés.</strong></p>";
echo "<p><em>Supprimez ce fichier.</em></p>";
?>
