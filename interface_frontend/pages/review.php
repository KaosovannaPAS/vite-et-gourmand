<?php
session_start();
include __DIR__ . '/../../noyau_backend/configuration/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: /interface_frontend/pages/login.php');
    exit;
}

$user = $_SESSION['user'];
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    header('Location: /interface_frontend/pages/dashboard.php');
    exit;
}

// Vérifier que la commande appartient au user et est terminée
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $user['id']]);
$order = $stmt->fetch();

if (!$order) {
    die("Commande introuvable ou vous n'avez pas les droits.");
}

if ($order['statut'] !== 'terminee') {
    die("Vous ne pouvez laisser un avis que sur une commande terminée.");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = intval($_POST['note']);
    $comment = trim($_POST['comment']);

    if ($note < 1 || $note > 5) {
        $error = "La note doit être entre 1 et 5.";
    }
    elseif (empty($comment)) {
        $error = "Le commentaire ne peut pas être vide.";
    }
    else {
        try {
            // Vérifier si un avis existe déjà pour cette commande
            $stmtCheck = $pdo->prepare("SELECT id FROM reviews WHERE order_id = ?");
            $stmtCheck->execute([$order_id]);
            if ($stmtCheck->fetch()) {
                $error = "Vous avez déjà laissé un avis pour cette commande.";
            }
            else {
                $stmtInsert = $pdo->prepare("INSERT INTO reviews (user_id, order_id, note, commentaire, valide, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
                $stmtInsert->execute([$user['id'], $order_id, $note, $comment]);
                $success = "Votre avis a été enregistré et sera publié après validation.";
            }
        }
        catch (PDOException $e) {
            $error = "Erreur lors de l'enregistrement de l'avis : " . $e->getMessage();
        }
    }
}

include __DIR__ . '/../composants/header.php';
?>

<div class="container" style="padding: 4rem 0; max-width: 600px;">
    <h2 style="text-align: center; margin-bottom: 2rem;">Laisser un avis pour la commande #<?php echo htmlspecialchars($order_id); ?></h2>
    
    <?php if ($error): ?>
        <div style="background: #fab1a0; color: #c0392b; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
            <?php echo $error; ?>
        </div>
    <?php
endif; ?>

    <?php if ($success): ?>
        <div style="background: #55efc4; color: #00b894; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; text-align: center;">
            <?php echo $success; ?>
            <br><a href="/interface_frontend/pages/dashboard.php" class="btn" style="margin-top: 1rem; display: inline-block; background: white; color: #00b894;">Retour au tableau de bord</a>
        </div>
    <?php
else: ?>
        <form method="POST" style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <div style="margin-bottom: 1rem;">
                <label for="note" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Note</label>
                <select name="note" id="note" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Très bon</option>
                    <option value="3">3 - Moyen</option>
                    <option value="2">2 - Décevant</option>
                    <option value="1">1 - Mauvais</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="comment" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Commentaire</label>
                <textarea name="comment" id="comment" rows="5" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; font-family: inherit;" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Envoyer mon avis</button>
        </form>
    <?php
endif; ?>
</div>

<?php include '../composants/footer.php'; ?>
