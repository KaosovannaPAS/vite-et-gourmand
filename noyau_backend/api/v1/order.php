<?php
session_start();
header('Content-Type: application/json');

include __DIR__ . '/../../configuration/db.php'; // DB connection

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Non authentifié.', 'redirect' => '/Vite-et-gourmand/interface_frontend/pages/login.html']);
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Données invalides.']);
    exit;
}

$date_livraison = $input['date_livraison'] ?? '';
$heure_livraison = $input['heure_livraison'] ?? '';
$lieu_livraison = htmlspecialchars($input['lieu'] ?? '');
$quantite = intval($input['quantite'] ?? 0);
$menu_id = intval($input['menu_id'] ?? 0);
$distance = intval($input['distance'] ?? 0);
$choices = $input['choices'] ?? [];
$choices_json = json_encode($choices);

if (!$menu_id || !$quantite || !$date_livraison || !$heure_livraison || !$lieu_livraison) {
    echo json_encode(['success' => false, 'error' => 'Des informations sont manquantes.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
    $stmt->execute([$menu_id]);
    $menu = $stmt->fetch();

    if (!$menu) {
        echo json_encode(['success' => false, 'error' => 'Menu invalide.']);
        exit;
    }

    if ($quantite < $menu['min_personnes']) {
        echo json_encode(['success' => false, 'error' => "Le minimum de commande est de " . $menu['min_personnes'] . " personnes."]);
        exit;
    }

    // Calcul Frais
    $frais_livraison = 5.00;
    if (strtolower(trim($lieu_livraison)) !== 'bordeaux' && !strpos(strtolower($lieu_livraison), 'bordeaux') && !strpos(strtolower($lieu_livraison), '33000')) {
        $frais_livraison += ($distance * 0.59);
    }

    $prix_menu = $menu['prix'];
    if ($quantite >= ($menu['min_personnes'] + 5)) {
        $prix_menu *= 0.9; // 10% discount
    }

    $total = ($prix_menu * $quantite) + $frais_livraison;

    $pdo->beginTransaction();

    // 1. Création Commande
    $stmtOrder = $pdo->prepare("INSERT INTO orders (user_id, date_livraison, heure_livraison, lieu_livraison, prix_total, statut) VALUES (?, ?, ?, ?, ?, 'en_attente')");
    $stmtOrder->execute([$user['id'], $date_livraison, $heure_livraison, $lieu_livraison, $total]);
    $order_id = $pdo->lastInsertId();

    // 2. Ajout Item avec Choix
    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantite, prix_unitaire, choices) VALUES (?, ?, ?, ?, ?)");
    $stmtItem->execute([$order_id, $menu['id'], $quantite, $prix_menu, $choices_json]);

    // 3. Mise à jour Stock
    $stmtStock = $pdo->prepare("UPDATE menus SET stock = stock - ? WHERE id = ?");
    $stmtStock->execute([$quantite, $menu['id']]);

    $pdo->commit();

    echo json_encode(['success' => true, 'redirect' => '/Vite-et-gourmand/interface_frontend/pages/dashboard.html?success=1']);

}
catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => "Erreur technique : " . $e->getMessage()]);
}
