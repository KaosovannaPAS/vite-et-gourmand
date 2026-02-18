<?php
// Script d'import pour TiDB (utilise la connexion de db.php qui gère le SSL)
require_once __DIR__ . '/noyau_backend/configuration/db.php';

echo "Connexion à TiDB via db.php...\n";

// Vérification de la connexion PDO existante
if (!isset($pdo)) {
    die("Erreur: La variable \$pdo n'est pas définie après l'inclusion de db.php\n");
}

echo "Connecté ! Importation en cours...\n";

// Lecture du fichier SQL
$sqlFile = __DIR__ . '/export_final.sql';
if (!file_exists($sqlFile)) {
    die("Erreur: Fichier SQL introuvable: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);

// Séparation des requêtes (simple split sur ;)
// Attention: ceci est basique et peut casser si des ; sont dans les strings
// Mais pour un dump standard mysqldump --compact, c'est souvent OK.
// Sinon on peut exécuter tout le bloc si le driver le supporte.
// TiDB supporte les transactions multi-statements.

try {
    // On essaie d'exécuter le script en entier ou par blocs
    // PDO::exec supporte-t-il multi-query ? Dépend du driver et config.
    // Mysqldump génère souvent des gros INSERTs.
    // On va tenter d'exécuter tel quel si possible, sinon split.

    // TiDB Serverless : mieux vaut splitter pour plus de contrôle et éviter timeouts.

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
            // Ignorer les erreurs "Table already exists" ou commentaires
            if (strpos($stmt, '/*') === 0 && strpos($stmt, '*/') !== false)
                continue;

            echo "ERREUR sur requête: " . substr($stmt, 0, 100) . "...\n";
            echo "Message: " . $e->getMessage() . "\n\n";
            $errors++;
        }
    }

    echo "\nTerminé ! $ok requêtes exécutées avec succès, $errors erreurs.\n";


}
catch (Exception $e) {
    die("Erreur générale lors de l'import: " . $e->getMessage() . "\n");
}
?>
