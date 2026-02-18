<?php
session_start();
include '../../noyau_backend/configuration/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);

    // Hashage
    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, 'employe')");
        if ($stmt->execute([$nom, $prenom, $email, $hash])) {
            $success = "Compte employé créé avec succès. Un mail a été envoyé (simulé).";
        // Simulation envoi mail
        }
    }
    catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div class="container" style="padding: 4rem 0; max-width: 500px;">
    <h2>Ajouter un Employé</h2>
    
    <?php if ($success): ?>
        <div style="background: #b8e994; color: #218c74; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
            <?php echo $success; ?> <a href="/interface_frontend/admin/dashboard.php">Retour Dashboard</a>
        </div>
    <?php
endif; ?>

    <?php if ($error): ?>
        <div style="background: #fab1a0; color: #c0392b; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
            <?php echo $error; ?>
        </div>
    <?php
endif; ?>

    <form method="POST" style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div style="margin-bottom: 1rem;">
            <label>Nom</label>
            <input type="text" name="nom" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
        </div>
        <div style="margin-bottom: 1rem;">
            <label>Prénom</label>
            <input type="text" name="prenom" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
        </div>
        <div style="margin-bottom: 1rem;">
            <label>Email (Username)</label>
            <input type="email" name="email" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
        </div>
        <div style="margin-bottom: 1rem;">
            <label>Mot de passe</label>
            <input type="password" name="password" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Créer le compte</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
