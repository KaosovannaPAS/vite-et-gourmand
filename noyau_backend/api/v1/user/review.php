<?php
session_start();
header('Content-Type: application/json');

include __DIR__ . '/../../../configuration/db.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'Non authentifié.']);
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

$order_id = intval($input['order_id'] ?? 0);
$note = intval($input['note'] ?? 0);
$comment = trim(htmlspecialchars($input['comment'] ?? ''));

if (!$order_id) {
    echo json_encode(['success' => false, 'error' => 'Commande manquante.']);
    exit;
}

try {
    // Check if order belongs to user and is finished
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$order_id, $user['id']]);
    $order = $stmt->fetch();

    if (!$order) {
        echo json_encode(['success' => false, 'error' => 'Commande introuvable ou droits insuffisants.']);
        exit;
    }

    if ($order['statut'] !== 'terminee') {
        echo json_encode(['success' => false, 'error' => 'Vous ne pouvez laisser un avis que sur une commande terminée.']);
        exit;
    }

    if ($note < 1 || $note > 5) {
        echo json_encode(['success' => false, 'error' => 'La note doit être entre 1 et 5.']);
        exit;
    }

    if (empty($comment)) {
        echo json_encode(['success' => false, 'error' => 'Le commentaire ne peut pas être vide.']);
        exit;
    }

    $stmtCheck = $pdo->prepare("SELECT id FROM reviews WHERE order_id = ?");
    $stmtCheck->execute([$order_id]);
    if ($stmtCheck->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Vous avez déjà laissé un avis pour cette commande.']);
        exit;
    }

    $stmtInsert = $pdo->prepare("INSERT INTO reviews (user_id, order_id, note, commentaire, valide, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
    $stmtInsert->execute([$user['id'], $order_id, $note, $comment]);

    echo json_encode(['success' => true, 'message' => 'Votre avis a été enregistré et sera publié après validation.']);

}
catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'enregistrement de l\'avis : ' . $e->getMessage()]);
}
