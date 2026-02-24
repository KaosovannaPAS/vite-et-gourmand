<?php
session_start();
header('Content-Type: application/json');

include __DIR__ . '/../../../configuration/db.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Non authentifié']);
    exit;
}

$user = $_SESSION['user'];

try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user['id']]);
    $orders = $stmt->fetchAll();

    $result = [];
    foreach ($orders as $order) {
        $stmtItems = $pdo->prepare("SELECT oi.*, m.titre 
                                    FROM order_items oi 
                                    JOIN menus m ON oi.menu_id = m.id 
                                    WHERE oi.order_id = ?");
        $stmtItems->execute([$order['id']]);
        $items = $stmtItems->fetchAll();

        // Pour chaque item de commande, formater les choix
        foreach ($items as &$item) {
            $item['choices_decoded'] = json_decode($item['choices'] ?? '[]', true);
            $choicesDetails = [];
            foreach ($item['choices_decoded'] as $type => $dishId) {
                // Essayer de récupérer le nom du plat
                $stmtDish = $pdo->prepare("SELECT nom FROM dishes WHERE id = ?");
                $stmtDish->execute([$dishId]);
                $dish = $stmtDish->fetch();
                $choicesDetails[$type] = $dish ? $dish['nom'] : "Plat #$dishId";
            }
            $item['choices_names'] = $choicesDetails;
        }

        $order['items'] = $items;
        $result[] = $order;
    }

    echo json_encode(['success' => true, 'orders' => $result]);

}
catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => "Erreur DB: " . $e->getMessage()]);
}
