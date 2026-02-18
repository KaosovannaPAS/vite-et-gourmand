<?php
session_start();
include __DIR__ . '/../../noyau_backend/configuration/db.php';

if (!isset($_GET['id']) || !isset($_SESSION['user'])) {
    header('Location: /');
    exit;
}

$order_id = $_GET['id'];
// Sécurité : Vérifier que la commande appartient au user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user']['id']]);
$order = $stmt->fetch();

if (!$order) {
    die("Commande introuvable.");
}

include __DIR__ . '/../composants/header.php';
?>

<div class="container" style="padding: 4rem 0; text-align: center;">
    <i class="fas fa-check-circle" style="font-size: 5rem; color: #2ecc71; margin-bottom: 2rem;"></i>
    <h1>Merci pour votre commande !</h1>
    <p style="font-size: 1.2rem;">Votre commande <strong>#<?php echo $order['id']; ?></strong> a bien été enregistrée.</p>
    <p>Un email de confirmation vous a été envoyé.</p>
    
    <div style="margin-top: 3rem;">
        <a href="/interface_frontend/pages/dashboard.php" class="btn btn-primary">Suivre ma commande</a>
        <a href="/" class="btn" style="background: #eee;">Retour accueil</a>
    </div>
</div>

<?php include __DIR__ . '/../composants/footer.php'; ?>
