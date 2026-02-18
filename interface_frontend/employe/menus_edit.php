<?php
session_start();
include '../../noyau_backend/configuration/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employe') {
    header('Location: /');
    exit;
}

// Suppression
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM menus WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: /interface_frontend/employe/menus_edit.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM menus ORDER BY created_at DESC");
$menus = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container" style="padding: 4rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Gestion des Menus</h2>
        <a href="/interface_frontend/employe/menu_add.php" class="btn btn-primary">+ Nouveau Menu</a>
        <a href="/interface_frontend/employe/dashboard.php" class="btn" style="background: #eee;">Retour Commandes</a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem;">
        <?php foreach ($menus as $menu): ?>
            <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <img src="<?php echo $menu['image_url'] ?? 'https://via.placeholder.com/300x200'; ?>" style="width: 100%; height: 200px; object-fit: cover;">
                <div style="padding: 1.5rem;">
                    <h3><?php echo htmlspecialchars($menu['titre']); ?></h3>
                    <p style="font-weight: bold;"><?php echo $menu['prix']; ?> €</p>
                    <p>Stock: <?php echo $menu['stock']; ?></p>
                    <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                        <a href="/interface_frontend/employe/menu_add.php?id=<?php echo $menu['id']; ?>" class="btn" style="background: #74b9ff; color: white;">Modifier</a>
                        <a href="?delete=<?php echo $menu['id']; ?>" class="btn" style="background: #ff7675; color: white;" onclick="return confirm('Supprimer ce menu ?');">Supprimer</a>
                    </div>
                </div>
            </div>
        <?php
endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
