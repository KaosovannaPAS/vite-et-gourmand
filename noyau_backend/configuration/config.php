<?php
// Détection automatique de l'environnement
$host = $_SERVER['HTTP_HOST'];
$is_local = ($host === 'localhost' || $host === '127.0.0.1');

// Définition de la racine du site
// Si on est en local, on ajoute le dossier du projet
// Sinon (Vercel), on est à la racine
define('BASE_URL', $is_local ? '/Vite-et-gourmand' : '');

// Définition du chemin absolu pour les inclusions PHP
define('ROOT_PATH', dirname(__DIR__, 2));
?>
