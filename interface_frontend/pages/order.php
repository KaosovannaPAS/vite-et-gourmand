<?php
session_start();
include __DIR__ . '/../../noyau_backend/configuration/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: /interface_frontend/pages/login.php');
    exit;
}

$user = $_SESSION['user'];

// Récupération des données (soit du POST précédent, soit du GET)
$menu_id = $_POST['menu_id'] ?? $_GET['menu_id'] ?? null;
$quantite = $_POST['quantite'] ?? 1;
// Convertir les choix en tableau si c'est déjà un tableau (POST menu-detail), ou decoder si JSON (POST order)
$choices = $_POST['choices'] ?? [];

if (is_string($choices)) {
    $choices = json_decode($choices, true);
}

// Variables pour l'affichage
$total_previsionnel = 0;
$menu = null;

if ($menu_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
        $stmt->execute([$menu_id]);
        $menu = $stmt->fetch();

        if ($menu) {
            $total_previsionnel = $menu['prix'] * $quantite;
        }
    }
    catch (PDOException $e) {
        die("Erreur BDD");
    }
}

$error = '';

// =========================================================
// TRAITEMENT DU FORMULAIRE DE VALIDATION COMMANDE
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'validate_order') {

    $date_livraison = $_POST['date_livraison'];
    $heure_livraison = $_POST['heure_livraison'];
    $lieu_livraison = htmlspecialchars($_POST['lieu']);
    // On reprend les valeurs des champs cachés
    $quantite = intval($_POST['quantite']);
    $menu_id = intval($_POST['menu_id']);
    // Choices est une chaîne JSON ici car passé en hidden
    $choices_json = $_POST['choices_json'];

    // Validation
    if (!$menu) {
        $error = "Menu invalide.";
    }
    elseif ($quantite < $menu['min_personnes']) {
        $error = "Le minimum de commande est de " . $menu['min_personnes'] . " personnes.";
    }
    else {
        // Calcul Frais
        $frais_livraison = 5.00;
        $distance = intval($_POST['distance']);
        if (strtolower(trim($lieu_livraison)) !== 'bordeaux' && !strpos(strtolower($lieu_livraison), 'bordeaux')) {
            $frais_livraison += ($distance * 0.59);
        }

        $prix_menu = $menu['prix'];
        // Réduction 10% si > min + 5 (Règle métier)
        if ($quantite >= ($menu['min_personnes'] + 5)) {
            $prix_menu *= 0.9;
        }

        $total = ($prix_menu * $quantite) + $frais_livraison;

        try {
            $pdo->beginTransaction();

            // 1. Création Commande
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, date_livraison, heure_livraison, lieu_livraison, prix_total, statut) VALUES (?, ?, ?, ?, ?, 'en_attente')");
            $stmt->execute([$user['id'], $date_livraison, $heure_livraison, $lieu_livraison, $total]);
            $order_id = $pdo->lastInsertId();

            // 2. Ajout Item avec Choix
            $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, menu_id, quantite, prix_unitaire, choices) VALUES (?, ?, ?, ?, ?)");
            $stmtItem->execute([$order_id, $menu['id'], $quantite, $prix_menu, $choices_json]);

            // 3. Mise à jour Stock
            $stmtStock = $pdo->prepare("UPDATE menus SET stock = stock - 1 WHERE id = ?");
            $stmtStock->execute([$menu['id']]);

            $pdo->commit();

            header('Location: /interface_frontend/pages/dashboard.php?success=1');
            exit;

        }
        catch (Exception $e) {
            $pdo->rollBack();
            $error = "Erreur technique : " . $e->getMessage();
        }
    }
}

include __DIR__ . '/../composants/header.php';
?>

<div class="container" style="padding: 4rem 0; max-width: 800px;">
    <h2 style="text-align: center; margin-bottom: 2rem;">Finaliser votre commande</h2>

    <?php if ($error): ?>
        <div style="background: #fab1a0; color: #c0392b; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
            <?php echo $error; ?>
        </div>
    <?php
endif; ?>

    <div style="display: flex; gap: 2rem;">
        <div style="flex: 2;">
            <form method="POST" style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <input type="hidden" name="action" value="validate_order">
                <input type="hidden" name="menu_id" value="<?php echo htmlspecialchars($menu_id); ?>">
                <input type="hidden" name="quantite" value="<?php echo htmlspecialchars($quantite); ?>">
                <!-- Encode choices back to JSON for the hidden field -->
                <input type="hidden" name="choices_json" value="<?php echo htmlspecialchars(json_encode($choices)); ?>">
                
                <h3 style="margin-bottom: 1rem;">1. Coordonnées</h3>
                <div style="background: #f1f2f6; padding: 1rem; border-radius: 5px; margin-bottom: 1.5rem;">
                    <p><strong>Nom :</strong> <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?></p>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <h3 style="margin-bottom: 1rem;">2. Livraison</h3>
                <div style="margin-bottom: 1rem;">
                    <label for="date_livraison">Date souhaitée</label>
                    <input type="date" id="date_livraison" name="date_livraison" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label for="heure_livraison">Heure souhaitée</label>
                    <input type="time" id="heure_livraison" name="heure_livraison" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label for="lieu">Lieu de livraison</label>
                    <input type="text" id="lieu" name="lieu" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;" value="<?php echo htmlspecialchars($user['adresse'] ?? ''); ?>">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label for="distance">Distance depuis Bordeaux (km)</label>
                    <input type="number" id="distance" name="distance" value="0" min="0" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                    <small>Sera vérifié lors de la validation.</small>
                </div>

                <h3 style="margin-bottom: 1rem; margin-top: 2rem;">3. Récapitulatif</h3>
                <?php if ($menu): ?>
                    <div style="background: #e1b12c33; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                        <strong>Menu : <?php echo htmlspecialchars($menu['titre']); ?></strong>
                        <p><?php echo htmlspecialchars($menu['description']); ?></p>
                        <p><strong>Quantité :</strong> <?php echo $quantite; ?> personnes</p>
                        
                        <?php if (!empty($choices)): ?>
                            <div style="margin-top: 0.5rem; font-size: 0.9rem;">
                                <strong>Vos choix :</strong>
                                <ul>
                                    <?php
        // Fetch dish names for display if possible, or just IDs for MVP
        // ideally we'd query to get names, but for MVP let's assume we trust the flow
        // or fetch them here.
        foreach ($choices as $type => $dishId) {
            echo "<li>" . ucfirst($type) . " (ID: $dishId)</li>";
        }
?>
                                </ul>
                            </div>
                        <?php
    endif; ?>
                    </div>
                <?php
else: ?>
                    <p>Aucun menu sélectionné.</p>
                <?php
endif; ?>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Valider la commande</button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
