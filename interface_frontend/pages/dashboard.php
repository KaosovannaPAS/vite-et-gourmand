<?php
session_start();
include __DIR__ . '/../../noyau_backend/configuration/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: /interface_frontend/pages/login.php');
    exit;
}

$user = $_SESSION['user'];

if ($user['role'] === 'admin') {
    header('Location: /interface_frontend/admin/dashboard.php');
    exit;
}
elseif ($user['role'] === 'employe') {
    header('Location: /interface_frontend/employe/dashboard.php');
    exit;
}

// Récupération des commandes
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user['id']]);
$orders = $stmt->fetchAll();

include __DIR__ . '/../composants/header.php';
?>

<div class="container" style="padding: 4rem 0;">
    <h2 style="margin-bottom: 2rem;">Mon Espace - Bonjour <?php echo htmlspecialchars($user['prenom']); ?></h2>
    
    <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
        
        <!-- Sidebar Menu Espace -->
        <aside style="width: 250px; background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); height: fit-content;">
            <ul style="list-style: none; padding: 0;">
                <li style="margin-bottom: 1rem;"><a href="#" style="font-weight: bold; color: var(--primary-color);">Mes Commandes</a></li>
                <li style="margin-bottom: 1rem;"><a href="/interface_frontend/pages/profile.php" style="color: #666;">Mes Informations</a></li>
                <?php if ($user['role'] === 'employe'): ?>
                    <li style="margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1rem;"><a href="/interface_frontend/employe/dashboard.php" style="color: var(--secondary-color); font-weight: bold;">Accès Employé</a></li>
                <?php
endif; ?>
                <?php if ($user['role'] === 'admin'): ?>
                    <li style="margin-top: 2rem; border-top: 1px solid #eee; padding-top: 1rem;"><a href="/interface_frontend/admin/dashboard.php" style="color: var(--secondary-color); font-weight: bold;">Accès Admin</a></li>
                <?php
endif; ?>
                <li><a href="/interface_frontend/pages/logout.php" style="color: #c0392b;">Déconnexion</a></li>
            </ul>
        </aside>

        <!-- Contenu Principal : Liste des commandes -->
        <section style="flex: 1;">
            <h3 style="margin-bottom: 1.5rem;">Historique de vos commandes</h3>
            
            <?php if (count($orders) > 0): ?>
                <div style="display: grid; gap: 1.5rem;">
                    <?php foreach ($orders as $order): ?>
                        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                            
                            <div>
                                <h4 style="margin: 0; color: var(--secondary-color);">Commande #<?php echo $order['id']; ?></h4>
                                <p style="font-size: 0.9rem; color: #888;">Du <?php echo date('d/m/Y', strtotime($order['created_at'])); ?></p>
                                <p style="margin-top: 0.5rem;">
                                    <strong>Statut : </strong> 
                                    <span style="padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; background: #dfe6e9; color: #2d3436;">
                                        <?php echo ucfirst(str_replace('_', ' ', $order['statut'])); ?>
                                    </span>
                                </p>

                                <!-- Détails de la commande -->
                                <div style="margin-top: 1rem; padding-top: 0.5rem; border-top: 1px dashed #eee;">
                                    <?php
        $stmtItems = $pdo->prepare("SELECT oi.*, m.titre 
                                                                FROM order_items oi 
                                                                JOIN menus m ON oi.menu_id = m.id 
                                                                WHERE oi.order_id = ?");
        $stmtItems->execute([$order['id']]);
        $items = $stmtItems->fetchAll();

        foreach ($items as $item):
            $choices = json_decode($item['choices'] ?? '[]', true);
?>
                                        <p style="margin: 0; font-weight: bold;"><?php echo htmlspecialchars($item['titre']); ?> x<?php echo $item['quantite']; ?></p>
                                        <?php if (!empty($choices)): ?>
                                            <ul style="font-size: 0.85rem; color: #666; margin: 0.2rem 0 0.5rem 1rem; padding: 0;">
                                                <?php foreach ($choices as $type => $dishId):
                    // Pour l'affichage, on pourrait récupérer le nom du plat si on l'avait stocké ou via une requête.
                    // MVP : On affiche juste le type pour l'instant ou on fait une requête (coûteux dans une boucle).
                    // On va essayer de récuperer le nom via une requête simple pour faire propre.
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
                                    <?php
        endforeach; ?>
                                </div>
                            </div>

                            <div style="text-align: right;">
                                <p style="font-size: 1.2rem; font-weight: bold; color: var(--primary-color);"><?php echo $order['prix_total']; ?> €</p>
                                <p style="font-size: 0.9rem;">
                                    Livraison le <?php echo date('d/m/Y', strtotime($order['date_livraison'])); ?> <br>
                                    à <?php echo date('H:i', strtotime($order['heure_livraison'])); ?>
                                </p>
                            </div>
                            
                            <div style="width: 100%; margin-top: 1rem; border-top: 1px solid #eee; padding-top: 1rem; text-align: right;">
                                <?php if ($order['statut'] === 'en_attente'): ?>
                                    <a href="#" class="btn" style="background: #fab1a0; color: #c0392b; font-size: 0.8rem;">Annuler</a>
                                    <a href="#" class="btn" style="background: #74b9ff; color: white; font-size: 0.8rem;">Modifier</a>
                                <?php
        endif; ?>
                                
                                <?php if ($order['statut'] === 'terminee'): ?>
                                    <a href="/interface_frontend/pages/review.php?order_id=<?php echo $order['id']; ?>" class="btn btn-primary" style="font-size: 0.8rem;">Laisser un avis</a>
                                <?php
        endif; ?>
                            </div>
                        </div>
                    <?php
    endforeach; ?>
                </div>
            <?php
else: ?>
                <div style="text-align: center; padding: 4rem; background: white; border-radius: 10px;">
                    <p>Vous n'avez passé aucune commande pour le moment.</p>
                    <a href="/interface_frontend/pages/profile.php" class="btn btn-primary" style="margin-top: 1rem;">Modifier mes informations</a>
                </div>
            <?php
endif; ?>
        </section>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
