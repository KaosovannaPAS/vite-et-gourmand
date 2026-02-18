<?php
include __DIR__ . '/noyau_backend/configuration/db.php';
$pdo->exec("SET NAMES utf8mb4");

// Rewrite all menu descriptions with clean, proper text
$descriptions = [
    1 => "Un repas de fête inoubliable avec des produits nobles et une touche de magie.",
    2 => "Un voyage culinaire raffiné pour célébrer les grandes occasions avec élégance.",
    3 => "Une déclaration d'amour dans l'assiette, avec des saveurs douces et romantiques.",
    4 => "Un voyage culinaire au cœur de l'Asie, entre épices subtiles et saveurs authentiques.",
    5 => "Une cuisine 100% végétale, généreuse et pleine de saveurs, pour prendre soin de vous.",
    6 => "Les trésors de l'Atlantique dans votre assiette : fraîcheur et iode garantis.",
    7 => "Le meilleur du terroir bordelais sublimé par nos chefs, pour un repas ancré dans la tradition.",
];

$updated = 0;
foreach ($descriptions as $id => $desc) {
    $stmt = $pdo->prepare("UPDATE menus SET description = ? WHERE id = ?");
    $stmt->execute([$desc, $id]);
    $updated++;
}

// Also catch any menu not in the list above that still has the bad text
$rows = $pdo->query("SELECT id, titre, description FROM menus")->fetchAll(PDO::FETCH_ASSOC);
$extra = 0;
foreach ($rows as $r) {
    $desc = $r['description'] ?? '';
    if (stripos($desc, '600') !== false || stripos($desc, 'restitution') !== false || stripos($desc, 'mat') !== false) {
        // Cut at first bad phrase
        foreach (['En attente', 'Pour toute', 'Si du mat', '600', 'restitution', 'CGV'] as $phrase) {
            $pos = mb_stripos($desc, $phrase);
            if ($pos !== false && $pos > 5) {
                $desc = trim(mb_substr($desc, 0, $pos));
                break;
            }
        }
        $stmt = $pdo->prepare("UPDATE menus SET description = ? WHERE id = ?");
        $stmt->execute([$desc, $r['id']]);
        $extra++;
        echo "<p>✅ Extra nettoyé: <strong>{$r['titre']}</strong></p>";
    }
}

echo "<h2>✅ $updated descriptions réécrites, $extra supplémentaires nettoyées.</h2>";

// Show final state
$rows = $pdo->query("SELECT id, titre, description FROM menus ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
echo "<h3>État final :</h3>";
foreach ($rows as $r) {
    echo "<p><strong>ID {$r['id']} — {$r['titre']}</strong>: " . htmlspecialchars($r['description'] ?? '') . "</p>";
}
echo "<p><em>Supprimez ce fichier.</em></p>";
?>
