<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../configuration/db.php';

try {
    $sql = "SELECT m.*, GROUP_CONCAT(d.nom SEPARATOR ', ') as plats 
            FROM menus m 
            LEFT JOIN menu_dishes md ON m.id = md.menu_id 
            LEFT JOIN dishes d ON md.dish_id = d.id 
            WHERE m.actif = 1";

    $params = [];

    // Filtres
    if (!empty($_GET['theme'])) {
        $sql .= " AND m.theme = ?";
        $params[] = $_GET['theme'];
    }

    if (!empty($_GET['max_price'])) {
        $sql .= " AND m.prix <= ?";
        $params[] = $_GET['max_price'];
    }

    if (!empty($_GET['min_price'])) {
        $sql .= " AND m.prix >= ?";
        $params[] = $_GET['min_price'];
    }

    if (!empty($_GET['regime'])) {
        $sql .= " AND m.regime = ?";
        $params[] = $_GET['regime'];
    }

    if (!empty($_GET['min_people'])) {
        $sql .= " AND m.min_personnes <= ?";
        $params[] = $_GET['min_people'];
    }

    // Group by pour éviter les doublons dus au LEFT JOIN
    $sql .= " GROUP BY m.id ORDER BY m.titre ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $menus = $stmt->fetchAll();

    // Fetch individual dishes for condensed display
    foreach ($menus as &$menu) {
        $dishSql = "SELECT d.nom, d.type 
                   FROM dishes d 
                   JOIN menu_dishes md ON d.id = md.dish_id 
                   WHERE md.menu_id = ?";
        $dishStmt = $pdo->prepare($dishSql);
        $dishStmt->execute([$menu['id']]);
        $allDishes = $dishStmt->fetchAll();

        $condensed = ['entree' => null, 'plat' => null, 'dessert' => null];
        foreach ($allDishes as $dish) {
            if (!$condensed[$dish['type']]) {
                $condensed[$dish['type']] = $dish['nom'];
            }
        }
        $menu['condensed_dishes'] = $condensed;
    }

    echo json_encode($menus);

}
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
