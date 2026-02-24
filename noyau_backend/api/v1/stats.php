<?php
session_start();
require_once __DIR__ . '/../../configuration/config.php';
require_once __DIR__ . '/../../configuration/db.php';

header('Content-Type: application/json; charset=utf-8');

// Basic auth check for admin API
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Non autorisé"]);
    exit;
}

try {
    $stats = [];

    // Commandes en cours
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE statut IN ('en_attente', 'en_preparation')");
    $stats['active_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Chiffre d'affaires (Jour)
    $stmt = $pdo->query("SELECT SUM(prix_total) as revenue FROM orders WHERE date_livraison = CURDATE() AND statut != 'annullee'");
    $stats['revenue_today'] = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'] ?? 0;

    // Avis à modérer
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM reviews WHERE valide = 0");
    $stats['pending_reviews'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    // Dernières commandes
    $stmt = $pdo->query("
        SELECT o.id, o.date_livraison, o.heure_livraison, o.prix_total, o.statut, u.nom, u.prenom 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.id DESC 
        LIMIT 5
    ");
    $stats['recent_orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($stats);
}
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur base de données"]);
}
