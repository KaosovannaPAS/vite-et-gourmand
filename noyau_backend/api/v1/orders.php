<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT");

require_once __DIR__ . '/../../models/Order.php';

$orderModel = new Order();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        echo json_encode($orderModel->getById($_GET['id']));
    }
    elseif (isset($_GET['user_id'])) {
        echo json_encode($orderModel->getByUserId($_GET['user_id']));
    }
    else {
        echo json_encode($orderModel->getAll()); // Admin ONLY
    }
}
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $items = $data['items'] ?? [];

    $orderId = $orderModel->create($data, $items);
    if ($orderId) {
        http_response_code(201);
        echo json_encode(["message" => "Commande créée", "order_id" => $orderId]);
    }
    else {
        http_response_code(500);
        echo json_encode(["message" => "Erreur lors de la création de la commande"]);
    }
}
elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($_GET['id']) && isset($data['statut'])) {
        if ($orderModel->updateStatus($_GET['id'], $data['statut'])) {
            echo json_encode(["message" => "Statut mis à jour"]);
        }
        else {
            http_response_code(500);
            echo json_encode(["message" => "Erreur lors de la mise à jour"]);
        }
    }
}
?>
