<?php
session_start();
include '../../noyau_backend/configuration/db.php';
include '../../noyau_backend/configuration/mongo.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /');
    exit;
}

// Stats venant de MySQL (chiffres clés)
$stmt = $pdo->query("SELECT COUNT(*) as nb_commandes FROM orders");
$nb_commandes = $stmt->fetch()['nb_commandes'];

$stmt = $pdo->query("SELECT SUM(prix_total) as ca_total FROM orders");
$ca_total = $stmt->fetch()['ca_total'];

$stmt = $pdo->query("SELECT COUNT(*) as nb_users FROM users WHERE role = 'user'");
$nb_users = $stmt->fetch()['nb_users'];

// Stats venant de MongoDB (Logs et Agrégation avancée - Simulation si pas de données)
// Dans une vraie app, on pousserait les commandes dans Mongo à chaque validation.
// Ici on va juste lire une collection 'stats' fictive ou afficher des données MySQL si Mongo vide.
$mongoStats = [];
if ($mongoManager) {
    try {
        // Exemple : Récupérer les 5 derniers logs
        $query = new MongoDB\Driver\Query([], ['sort' => ['created_at' => -1], 'limit' => 5]);
        $cursor = $mongoManager->executeQuery(MONGO_DB . '.logs', $query);
        $mongoStats = $cursor->toArray();
    }
    catch (Exception $e) {
        $mongoError = "Erreur lecture MongoDB: " . $e->getMessage();
    }
}

include '../composants/header.php';
?>

<div class="container" style="padding: 4rem 0;">
    <h2 style="margin-bottom: 2rem;">Espace Administrateur</h2>

    <!-- Cartes Stat -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center;">
            <h3 style="color: var(--secondary-color); font-size: 2.5rem; margin-bottom: 0.5rem;"><?php echo $nb_commandes; ?></h3>
            <p style="color: #666;">Commandes Totales</p>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center;">
            <h3 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 0.5rem;"><?php echo number_format($ca_total, 2); ?> €</h3>
            <p style="color: #666;">Chiffre d'Affaires</p>
        </div>
        <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center;">
            <h3 style="color: var(--secondary-color); font-size: 2.5rem; margin-bottom: 0.5rem;"><?php echo $nb_users; ?></h3>
            <p style="color: #666;">Clients Inscrits</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem;">
        <!-- Gestion des employés -->
        <section>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3>Gestion des Employés</h3>
                <a href="/interface_frontend/admin/employee_add.php" class="btn btn-primary">+ Nouvel Employé</a>
            </div>
            
            <?php
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'employe'");
$employes = $stmt->fetchAll();
?>
            
            <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border-radius: 10px; overflow: hidden;">
                <tbody>
                    <?php foreach ($employes as $emp): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 1rem;"><?php echo htmlspecialchars($emp['nom'] . ' ' . $emp['prenom']); ?></td>
                            <td style="padding: 1rem;"><?php echo htmlspecialchars($emp['email']); ?></td>
                            <td style="padding: 1rem; text-align: right;">
                                <a href="/interface_frontend/admin/employee_delete.php?id=<?php echo $emp['id']; ?>" style="color: #c0392b;" onclick="return confirm('Supprimer cet employé ?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php
endforeach; ?>
                    <?php if (count($employes) === 0): ?>
                        <tr><td colspan="3" style="padding: 1rem; text-align: center;">Aucun employé.</td></tr>
                    <?php
endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Stats Mongo / Graphique Placeholder -->
        <section>
            <h3>Analyses (MongoDB)</h3>
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <?php if (isset($mongoError)): ?>
                    <p style="color: #c0392b;"><?php echo $mongoError; ?></p>
                <?php
else: ?>
                    <p><strong>Derniers Logs Système :</strong></p>
                    <ul>
                        <?php foreach ($mongoStats as $log): ?>
                            <li><?php echo $log->message; ?> (<?php echo $log->date; ?>)</li>
                        <?php
    endforeach; ?>
                        <?php if (empty($mongoStats))
        echo "<li>Aucune donnée récente.</li>"; ?>
                    </ul>
                <?php
endif; ?>
                
                <div style="margin-top: 2rem; background: #fff; padding: 1rem; border: 1px solid #ddd;">
                     <!-- PARTIE INTERFACE (FRONT-END) : GRAPHIQUE Chart.js -->
                    <canvas id="statsChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div style="margin-top: 2rem;">
                 <a href="/interface_frontend/employe/dashboard.php" class="btn" style="background: var(--secondary-color); color: white; width: 100%; text-align: center; display: block;">Accéder à l'Espace Employé</a>
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ============================================
// ============================================
// PARTIE INTERFACE (FRONT-END) : APPEL AJAX & GRAPHIQUE
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    fetch('/noyau_backend/api/v1/stats.php')
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('statsChart').getContext('2d');
            
            // Préparation des données pour le graph (Ex: Commandes par statut)
            const labels = data.orders_status.map(item => item.statut);
            const values = data.orders_status.map(item => item.count);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Répartition des Commandes',
                        data: values,
                        backgroundColor: [
                            '#fab1a0', '#ffeaa7', '#74b9ff', '#55efc4', '#a29bfe', '#dfe6e9'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Statut des Commandes' }
                    }
                }
            });
        })
        .catch(err => console.error("Erreur chargement stats:", err));
});
</script>

<?php include '../composants/footer.php'; ?>
