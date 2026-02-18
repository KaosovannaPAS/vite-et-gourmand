<?php
session_start();
// ============================================
// PARTIE NOYAU (BACK-END) : LOGIQUE METIER & DONNEES
// ============================================
include '../../noyau_backend/configuration/db.php';

if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['employe', 'admin'])) {
    header('Location: /');
    exit;
}

// Changement de statut de commande
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // Sécurité: Vérifier si statut valide... (Omis pour breveter)
    $stmt = $pdo->prepare("UPDATE orders SET statut = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);

    // Notification si en attente de retour de matériel
    if ($new_status === 'retour_materiel_en_attente') {
        $stmtClient = $pdo->prepare("SELECT u.email, u.nom FROM users u JOIN orders o ON o.user_id = u.id WHERE o.id = ?");
        $stmtClient->execute([$order_id]);
        $client = $stmtClient->fetch();

        if ($client) {
            $subject = "Action requise : Retour de matériel - Commande #$order_id";
            $message = "Bonjour " . $client['nom'] . ",\n\n" .
                "Votre commande #$order_id est désormais marquée comme 'En attente du retour de matériel'.\n" .
                "Conformément à nos CGV, nous vous rappelons que vous disposez de 10 jours ouvrés pour nous restituer le matériel prêté.\n" .
                "Passé ce délai, des frais forfaitaires de 600,00 € TTC seront appliqués.\n\n" .
                "Pour organiser le retour, merci de prendre contact avec nous par mail ou par téléphone (05 56 00 00 00).\n\n" .
                "Cordialement,\nL'équipe Vite & Gourmand";

            // Simulation d'envoi de mail (on écrit dans un fichier log ou on affiche un message)
            file_put_contents('../../noyau_backend/logs/mail_simu.log', "[" . date('Y-m-d H:i:s') . "] TO: " . $client['email'] . " | SUBJECT: $subject\n$message\n\n", FILE_APPEND);
            $success_msg = "Mail de notification envoyé à " . $client['email'];
        }
    }
}

// Filtres
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$sql = "SELECT o.*, u.nom, u.prenom, u.email FROM orders o JOIN users u ON o.user_id = u.id";
$params = [];

if ($filter_status) {
    $sql .= " WHERE o.statut = ?";
    $params[] = $filter_status;
}

$sql .= " ORDER BY o.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();

include '../composants/header.php';
?>

<!-- ============================================
     PARTIE INTERFACE (FRONT-END) : VUE DASHBOARD EMPLOYE
     ============================================ -->
<div class="container" style="padding: 4rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Espace Employé - Gestion des Commandes</h2>
        <div>
            <a href="/interface_frontend/employe/menus_edit.php" class="btn btn-primary">Gérer les Menus</a>
        </div>
    </div>

    <!-- Filtres -->
    <div style="background: white; padding: 1rem; border-radius: 5px; margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center;">
        <strong>Filtrer par statut :</strong>
        <a href="?" class="btn" style="background: #eee;">Tous</a>
        <a href="?status=en_attente" class="btn" style="background: #fab1a0; color: #c0392b;">En Attente</a>
        <a href="?status=preparation" class="btn" style="background: #ffeaa7; color: #d35400;">En Préparation</a>
        <a href="?status=livraison" class="btn" style="background: #74b9ff; color: #0984e3;">En Livraison</a>
    </div>

    <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 10px; overflow: hidden;">
        <thead>
            <tr style="background: var(--secondary-color); color: white;">
                <th style="padding: 1rem; text-align: left;">#</th>
                <th style="padding: 1rem; text-align: left;">Client</th>
                <th style="padding: 1rem; text-align: left;">Livraison</th>
                <th style="padding: 1rem; text-align: left;">Total</th>
                <th style="padding: 1rem; text-align: left;">Statut</th>
                <th style="padding: 1rem; text-align: left;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 1rem;">#<?php echo $order['id']; ?></td>
                    <td style="padding: 1rem;">
                        <?php echo htmlspecialchars($order['nom'] . ' ' . $order['prenom']); ?><br>
                        <small><?php echo htmlspecialchars($order['email']); ?></small>
                        <br><small><?php echo htmlspecialchars($order['lieu_livraison']); ?></small>
                    </td>
                    <td style="padding: 1rem;">
                        <?php echo date('d/m/Y', strtotime($order['date_livraison'])); ?><br>
                        <?php echo date('H:i', strtotime($order['heure_livraison'])); ?>
                    </td>
                    <td style="padding: 1rem; font-weight: bold;"><?php echo $order['prix_total']; ?> €</td>
                    <td style="padding: 1rem;">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status" onchange="this.form.submit()" style="padding: 5px; border-radius: 5px; border: 1px solid #ddd;">
                                <option value="en_attente" <?php if ($order['statut'] == 'en_attente')
        echo 'selected'; ?>>En Attente</option>
                                <option value="accepte" <?php if ($order['statut'] == 'accepte')
        echo 'selected'; ?>>Accepté</option>
                                <option value="preparation" <?php if ($order['statut'] == 'preparation')
        echo 'selected'; ?>>Préparation</option>
                                <option value="livraison" <?php if ($order['statut'] == 'livraison')
        echo 'selected'; ?>>Livraison</option>
                                <option value="livre" <?php if ($order['statut'] == 'livre')
        echo 'selected'; ?>>Livré</option>
                                <option value="terminee" <?php if ($order['statut'] == 'terminee')
        echo 'selected'; ?>>Terminée</option>
                                <option value="retour_materiel_en_attente" <?php if ($order['statut'] == 'retour_materiel_en_attente')
        echo 'selected'; ?>>En attente du retour de matériel</option>
                                <option value="annulee" <?php if ($order['statut'] == 'annulee')
        echo 'selected'; ?>>Annulée</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding: 1rem;">
                        <button onclick="document.getElementById('details-<?php echo $order['id']; ?>').style.display = document.getElementById('details-<?php echo $order['id']; ?>').style.display === 'none' ? 'table-row' : 'none'" class="btn btn-primary" style="font-size: 0.8rem; cursor: pointer;">Détail</button>
                    </td>
                </tr>
                <tr id="details-<?php echo $order['id']; ?>" style="display: none; background: #f9f9f9;">
                    <td colspan="6" style="padding: 1rem;">
                        <strong>Contenu de la commande :</strong>
                        <?php
    $stmtItems = $pdo->prepare("SELECT oi.*, m.titre 
                                                    FROM order_items oi 
                                                    JOIN menus m ON oi.menu_id = m.id 
                                                    WHERE oi.order_id = ?");
    $stmtItems->execute([$order['id']]);
    $items = $stmtItems->fetchAll();
?>
                        <ul style="margin-top: 0.5rem; list-style: circle; padding-left: 1.5rem;">
                            <?php foreach ($items as $item):
        $choices = json_decode($item['choices'] ?? '[]', true);
?>
                                <li>
                                    <strong><?php echo htmlspecialchars($item['titre']); ?></strong> x<?php echo $item['quantite']; ?>
                                    <?php if (!empty($choices)): ?>
                                        <ul style="font-size: 0.85rem; color: #666; margin-top: 0.2rem;">
                                            <?php foreach ($choices as $type => $dishId):
                $stmtDish = $pdo->prepare("SELECT nom FROM dishes WHERE id = ?");
                $stmtDish->execute([$dishId]);
                $dishParams = $stmtDish->fetch();
                $dishName = $dishParams ? $dishParams['nom'] : "Plat #$dishId";
?>
                                                <li><?php echo ucfirst($type); ?> : <?php echo htmlspecialchars($dishName); ?></li>
                                            <?php
            endforeach; ?>
                                        </ul>
                                    <?php
        endif; ?>
                                </li>
                            <?php
    endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <?php
endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../composants/footer.php'; ?>
