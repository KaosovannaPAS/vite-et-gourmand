<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/../../configuration/db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    global $pdo;

    $sql = "SELECT r.*, u.prenom, u.nom, u.avatar_url, 
            (SELECT GROUP_CONCAT(DISTINCT m.titre SEPARATOR ', ') 
             FROM order_items oi 
             JOIN menus m ON oi.menu_id = m.id 
             WHERE oi.order_id = r.order_id) as menu_titre
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.valide = 1 
            ORDER BY r.created_at DESC 
            LIMIT 20";

    try {
        $stmt = $pdo->query($sql);
        $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($avis);
    }
    catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["message" => "Erreur lors de la récupération des avis"]);
    }
}
else {
    http_response_code(405);
    echo json_encode(["message" => "Méthode non autorisée"]);
}
?>
