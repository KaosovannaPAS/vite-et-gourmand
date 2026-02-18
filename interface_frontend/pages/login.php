<?php
// ============================================
// ============================================
// PARTIE NOYAU (BACK-END) : TRAITEMENT AUTHENTIFICATION
// ============================================
session_start();
include __DIR__ . '/../../noyau_backend/configuration/db.php';

if (isset($_SESSION['user'])) {
    header('Location: /pages/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            // Redirection selon le rôle
            if ($user['role'] === 'admin') {
                header('Location: /interface_frontend/admin/dashboard.php');
            }
            elseif ($user['role'] === 'employe') {
                header('Location: /interface_frontend/employe/dashboard.php');
            }
            else {
                header('Location: /interface_frontend/pages/dashboard.php');
            }
            exit;
        }
        else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
    catch (PDOException $e) {
        $error = "Erreur de connexion.";
    }
}

include __DIR__ . '/../composants/header.php';
?>

<!-- ============================================
     PARTIE INTERFACE (FRONT-END) : FORMULAIRE DE CONNEXION (STYLE CONTACT)
     ============================================ -->
<div class="container" style="padding: 4rem 0; max-width: 1200px;">
    <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; text-transform: uppercase; letter-spacing: 2px;">Espace Client</h2>

    <?php if ($error): ?>
        <div style="background: #fff5f5; color: #c0392b; padding: 1.5rem; border-radius: 5px; margin-bottom: 2rem; font-weight: bold; text-align: center; border-left: 5px solid #c0392b;">
            <i class="fas fa-exclamation-circle" style="margin-right: 10px;"></i> <?php echo $error; ?>
        </div>
    <?php
endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 5rem; align-items: start;">
        
        <!-- Informations de Bienvenue (Style Coordonnées Contact) -->
        <div style="background: #fdfdfd; padding: 2.5rem; border-radius: 5px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 5px solid var(--secondary-color);">
            <h3 style="margin-bottom: 2rem; color: var(--primary-color); font-size: 1.8rem;">Bienvenue</h3>
            <p style="margin-bottom: 1.5rem; font-size: 1.1rem; line-height: 1.6;">
                Connectez-vous pour accéder à vos services privilégiés :
            </p>
            <ul style="list-style: none; padding: 0; font-size: 1.1rem;">
                <li style="margin-bottom: 1rem;"><i class="fas fa-history" style="color: var(--secondary-color); width: 25px;"></i> Historique de vos commandes</li>
                <li style="margin-bottom: 1rem;"><i class="fas fa-truck" style="color: var(--secondary-color); width: 25px;"></i> Suivi de livraison en temps réel</li>
                <li style="margin-bottom: 1rem;"><i class="fas fa-star" style="color: var(--secondary-color); width: 25px;"></i> Gestion de vos menus favoris</li>
                <li style="margin-bottom: 1rem;"><i class="fas fa-user-edit" style="color: var(--secondary-color); width: 25px;"></i> Modification de votre profil</li>
            </ul>

            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #eee;">
                <p style="font-size: 1.1rem;">Pas encore membre ?</p>
                <a href="/interface_frontend/pages/register.php" class="btn btn-primary" style="display: inline-block; margin-top: 1rem; text-decoration: none; text-align: center;">Créer un compte prestige</a>
            </div>
        </div>

        <!-- Formulaire de Connexion (Style Formulaire Contact) -->
        <form method="POST" style="background: var(--primary-color); padding: 3rem; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); border: 3px solid var(--secondary-color);">
            <h3 style="margin-bottom: 2rem; color: var(--secondary-color); font-size: 2rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.6); text-align: center;">Connexion</h3>
            
            <div style="margin-bottom: 1.5rem;">
                <label for="email" style="font-weight: bold; display: block; margin-bottom: 0.8rem; color: #000000; font-size: 1.1rem;">Email <span style="color: var(--secondary-color);">*</span></label>
                <input type="email" id="email" name="email" required class="form-control" style="width: 100%; padding: 12px; font-size: 1.1rem;" placeholder="votre@email.com">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="password" style="font-weight: bold; display: block; margin-bottom: 0.8rem; color: #000000; font-size: 1.1rem;">Mot de passe <span style="color: var(--secondary-color);">*</span></label>
                <input type="password" id="password" name="password" required class="form-control" style="width: 100%; padding: 12px; font-size: 1.1rem;" placeholder="••••••••">
                <div style="text-align: right; margin-top: 0.5rem;">
                    <a href="/interface_frontend/pages/forgot-password.php" style="color: var(--secondary-color); font-size: 0.9rem; text-decoration: none;">Mot de passe oublié ?</a>
                </div>
            </div>

            <button type="submit" class="btn btn-secondary" style="width: 100%; font-size: 1.3rem; font-weight: bold; padding: 20px; text-transform: uppercase; letter-spacing: 1px; margin-top: 1rem;">Se connecter</button>

            <div style="text-align: center; margin-top: 2rem; border-top: 1px solid rgba(0,0,0,0.1); padding-top: 1.5rem;">
                <p style="color: #000; opacity: 0.8;">Accès réservé aux clients et collaborateurs de Vite & Gourmand.</p>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../composants/footer.php'; ?>
