<?php
session_start();
include '../../noyau_backend/configuration/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employe') {
    header('Location: /');
    exit;
}

$menu = null;
$error = '';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM menus WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $menu = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $prix = floatval($_POST['prix']);
    $min_personnes = intval($_POST['min_personnes']);
    $stock = intval($_POST['stock']);
    $theme = htmlspecialchars($_POST['theme']);
    $regime = htmlspecialchars($_POST['regime']);
    $image_url = htmlspecialchars($_POST['image_url']);

    if ($menu) {
        // Update
        $stmt = $pdo->prepare("UPDATE menus SET titre=?, description=?, prix=?, min_personnes=?, stock=?, theme=?, regime=?, image_url=? WHERE id=?");
        $stmt->execute([$titre, $description, $prix, $min_personnes, $stock, $theme, $regime, $image_url, $menu['id']]);
    }
    else {
        // Create
        $stmt = $pdo->prepare("INSERT INTO menus (titre, description, prix, min_personnes, stock, theme, regime, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $prix, $min_personnes, $stock, $theme, $regime, $image_url]);
    }

    header('Location: /interface_frontend/employe/menus_edit.php');
    exit;
}

include '../includes/header.php';
?>

<div class="container" style="padding: 4rem 0; max-width: 600px;">
    <h2><?php echo $menu ? 'Modifier' : 'Ajouter'; ?> un Menu</h2>
    
    <form method="POST" style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div style="margin-bottom: 1rem;">
            <label>Titre</label>
            <input type="text" name="titre" value="<?php echo $menu['titre'] ?? ''; ?>" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
        </div>
        <div style="margin-bottom: 1rem;">
            <label>Description</label>
            <textarea name="description" rows="3" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;"><?php echo $menu['description'] ?? ''; ?></textarea>
        </div>
        <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
            <div style="flex: 1;">
                <label>Prix (€)</label>
                <input type="number" step="0.01" name="prix" value="<?php echo $menu['prix'] ?? ''; ?>" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div style="flex: 1;">
                <label>Min. Personnes</label>
                <input type="number" name="min_personnes" value="<?php echo $menu['min_personnes'] ?? 1; ?>" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            <div style="flex: 1;">
                <label>Stock</label>
                <input type="number" name="stock" value="<?php echo $menu['stock'] ?? 10; ?>" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
            </div>
        </div>
        <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
            <div style="flex: 1;">
                <label>Thème</label>
                <select name="theme" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="Classique">Classique</option>
                    <option value="Noel">Noël</option>
                    <option value="Paques">Pâques</option>
                    <option value="Evenement">Événement</option>
                </select>
            </div>
            <div style="flex: 1;">
                <label>Régime</label>
                <select name="regime" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="classique">Classique</option>
                    <option value="vegetarien">Végétarien</option>
                    <option value="vegan">Vegan</option>
                </select>
            </div>
        </div>
        <div style="margin-bottom: 1rem;">
            <label>URL Image</label>
            <input type="text" name="image_url" value="<?php echo $menu['image_url'] ?? ''; ?>" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Enregistrer</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
