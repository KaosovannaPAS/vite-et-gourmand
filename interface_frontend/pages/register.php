<?php
session_start();
// ============================================
// PARTIE NOYAU (BACK-END) : TRAITEMENT INSCRIPTION
// ============================================
include '../../noyau_backend/configuration/db.php';

if (isset($_SESSION['user'])) {
    header('Location: /interface_frontend/pages/login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);

    // Validation Mot de passe (10 chars, 1 Special, 1 Maj, 1 Min, 1 Chiffre)
    if (strlen($password) < 10 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[^a-zA-Z0-9]/', $password)) {
        $error = "Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
    }
    elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    }
    else {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé.";
            }
            else {
                // Création du compte
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, telephone, adresse, role) VALUES (?, ?, ?, ?, ?, ?, 'user')");

                if ($stmt->execute([$nom, $prenom, $email, $hash, $telephone, $adresse])) {
                    $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                // TODO: Envoyer mail de bienvenue
                }
                else {
                    $error = "Une erreur est survenue lors de l'inscription.";
                }
            }
        }
        catch (PDOException $e) {
            $error = "Erreur base de données : " . $e->getMessage();
        }
    }
}

include '../composants/header.php';
?>

<!-- ============================================
     PARTIE INTERFACE (FRONT-END) : FORMULAIRE D'INSCRIPTION
     ============================================ -->

<div class="container" style="padding: 4rem 0; max-width: 600px;">
    <h2 style="text-align: center; margin-bottom: 2rem;">Créer un compte</h2>
    
    <?php if ($error): ?>
        <div style="background: #fab1a0; color: #c0392b; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
            <?php echo $error; ?>
        </div>
    <?php
endif; ?>
    
    <?php if ($success): ?>
        <div style="background: #b8e994; color: #218c74; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
            <?php echo $success; ?>
        </div>
    <?php
endif; ?>

    <p style="text-align: center; margin-top: 1rem;">
        Déjà un compte ? <a href="/interface_frontend/pages/login.php" style="color: var(--primary-color);">Se connecter</a>
    </p>

    <form method="POST" style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;" value="<?php echo isset($_POST['nom']) ? $_POST['nom'] : ''; ?>">
            </div>
            <div style="flex: 1;">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;" value="<?php echo isset($_POST['prenom']) ? $_POST['prenom'] : ''; ?>">
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="telephone">Téléphone (GSM)</label>
            <input type="tel" id="telephone" name="telephone" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;" value="<?php echo isset($_POST['telephone']) ? $_POST['telephone'] : ''; ?>">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="adresse">Adresse Postale</label>
            <textarea id="adresse" name="adresse" required rows="3" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;"><?php echo isset($_POST['adresse']) ? $_POST['adresse'] : ''; ?></textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="password">Mot de passe</label>
            <p style="font-size: 0.8rem; color: #666; margin-bottom: 0.5rem;">10 caractères min., 1 Maj, 1 Min, 1 Chiffre, 1 Spécial</p>
            <input type="password" id="password" name="password" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px;">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">S'inscrire</button>
    </form>
</div>

<?php include '../composants/footer.php'; ?>
