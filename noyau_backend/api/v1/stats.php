<?php
// ============================================
// PARTIE BACK-END : API STATISTIQUES (JSON)
// ============================================
header('Content-Type: application/json');
require_once '../../configuration/db.php';
require_once '../../configuration/mongo.php';

// Sécurité : Vérifier admin (Session via cookie PHP, accessible ici si même domaine)
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

try {
    // 1. Données Relationnelles (MySQL) : Commandes par statut
    $stmt = $pdo->query("SELECT statut, COUNT(*) as count FROM orders GROUP BY statut");
    $ordersByStatus = $stmt->fetchAll();

    // 2. Données NoSQL (MongoDB) : Simulation logs ou autre métrique
    // Ici on on renvoie des données factices pour le graphique si Mongo vide
    $mongoData = [
        'labels' => ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        'visits' => [12, 19, 3, 5, 2, 3, 15] // Données simulées pour l'exemple
    ];

    echo json_encode([
        'orders_status' => $ordersByStatus,
        'activity' => $mongoData
    ]);

}
catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
