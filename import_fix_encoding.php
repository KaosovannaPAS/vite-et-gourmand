<?php
// Script d'import pour TiDB (version corrigée pour UTF-8)
require_once __DIR__ . '/noyau_backend/configuration/db.php';

echo "Connexion à TiDB via db.php...\n";

// S'assurer que la connexion est bien en UTF-8MB4
$pdo->exec("SET NAMES 'utf8mb4'");

echo "Connecté ! Importation en cours...\n";

// Lecture du fichier SQL corrigé
$sqlFile = __DIR__ . '/export_final_fixed.sql';
if (!file_exists($sqlFile)) {
    die("Erreur: Fichier SQL introuvable: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);

try {
    // Nettoyage de la table reviews avant import pour éviter les doublons/erreurs
    // On suppose que l'export contient des INSERT, pas forcément des TRUNCATE
    $pdo->exec("TRUNCATE TABLE reviews");

    $statements = array_filter(array_map('trim', explode(';', $sql)));

    $ok = 0;
    $errors = 0;

    foreach ($statements as $stmt) {
        if (empty($stmt))
            continue;

        try {
            $pdo->exec($stmt);
            $ok++;
        }
        catch (PDOException $e) {
            // Ignorer les erreurs commentaires
            if (strpos($stmt, '/*') === 0)
                continue;

            echo "ERREUR sur requête: " . substr($stmt, 0, 100) . "...\n";
            $errors++;
        }
    }

    echo "\nTerminé ! $ok requêtes exécutées avec succès, $errors erreurs.\n";


}
catch (Exception $e) {
    die("Erreur générale lors de l'import: " . $e->getMessage() . "\n");
}
?>
