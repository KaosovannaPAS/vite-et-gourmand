<?php
// Configuration MongoDB
// Assurez-vous d'avoir l'extension PHP MongoDB installée (php_mongodb.dll)

// Chaîne de connexion standard (à adapter selon votre config Atlas ou locale)
define('MONGO_URI', 'mongodb+srv://ViteetGourmand:Vite&Gourmand@cluster0.ypwko9k.mongodb.net/?appName=Cluster0');
define('MONGO_DB', 'vite_et_gourmand_stats');

try {
    $mongoManager = new MongoDB\Driver\Manager(MONGO_URI);
    // Test de connexion simple
    $command = new MongoDB\Driver\Command(['ping' => 1]);
    $mongoManager->executeCommand('admin', $command);
}
catch (MongoDB\Driver\Exception\Exception $e) {
    // En cas d'erreur (souvent si l'extension n'est pas là ou le serveur éteint)
    // On ne bloque pas tout le site, mais les stats ne marcheront pas
    error_log("Erreur MongoDB : " . $e->getMessage());
    $mongoManager = null;
}
?>
