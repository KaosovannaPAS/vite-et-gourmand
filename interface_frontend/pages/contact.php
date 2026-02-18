<?php
session_start();
include __DIR__ . '/../../noyau_backend/configuration/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $objet = htmlspecialchars($_POST['objet'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Champs devis
    $devisInfo = "";
    if ($objet === 'devis') {
        $date = htmlspecialchars($_POST['date_event'] ?? '');
        $convives = htmlspecialchars($_POST['convives'] ?? '');
        $type = htmlspecialchars($_POST['type_event'] ?? '');
        $budget = htmlspecialchars($_POST['budget'] ?? '');

        $devisInfo = "\n\n--- Détails Devis ---\nDate: $date\nConvives: $convives\nType: $type\nBudget: $budget €";
    }

    if (empty($nom) || empty($email) || empty($message)) {
        $error = "Veuillez remplir les champs obligatoires.";
    }
    else {
        // Simulation d'envoi de mail
        // mail('contact@vite-et-gourmand.fr', "Contact: $objet", $message . $devisInfo, "From: $email");
        $success = "Votre demande ($objet) a bien été envoyée ! Une réponse vous sera apportée sous 24h.";
    }
}

include __DIR__ . '/../composants/header.php';
?>

<div class="container" style="padding: 4rem 0; max-width: 1200px;">
    <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem; text-transform: uppercase; letter-spacing: 2px;">Nous Contacter</h2>

    <?php if ($success): ?>
        <div style="background: #b8e994; color: #218c74; padding: 1.5rem; border-radius: 5px; margin-bottom: 2rem; font-weight: bold; text-align: center;">
            <?php echo $success; ?>
        </div>
    <?php
endif; ?>

    <?php if ($error): ?>
        <div style="background: #fab1a0; color: #c0392b; padding: 1.5rem; border-radius: 5px; margin-bottom: 2rem; font-weight: bold; text-align: center;">
            <?php echo $error; ?>
        </div>
    <?php
endif; ?>

<?php
// Pre-remplissage depuis GET (venant de menu-detail.php)
$getUrl = $_GET['sujet'] ?? '';
$getMenu = $_GET['menu'] ?? '';
$getGuests = $_GET['guests'] ?? '';

$defaultObjet = 'renseignement';
$defaultMessage = '';

if ($getUrl === 'Devis') {
    $defaultObjet = 'devis';
    if ($getMenu) {
        $defaultMessage = "Bonjour,\n\nJe souhaiterais obtenir un devis pour le menu : " . $getMenu . ".\n";
    }
}
?>

    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 5rem; align-items: start;">
        
        <!-- Informations de Contact -->
        <div style="background: #fdfdfd; padding: 2.5rem; border-radius: 5px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border-left: 5px solid var(--secondary-color);">
            <h3 style="margin-bottom: 2rem; color: var(--primary-color); font-size: 1.8rem;">Nos Coordonnées</h3>
            <p style="margin-bottom: 1.5rem; font-size: 1.1rem;">
                <strong style="display: block; margin-bottom: 0.5rem;"><i class="fas fa-map-marker-alt" style="color: var(--secondary-color); width: 25px;"></i> Adresse :</strong>
                12 Quai des Chartrons<br>
                33000 Bordeaux, France
            </p>
            <p style="margin-bottom: 1.5rem; font-size: 1.1rem;">
                <strong style="display: block; margin-bottom: 0.5rem;"><i class="fas fa-phone" style="color: var(--secondary-color); width: 25px;"></i> Téléphone :</strong>
                <a href="tel:+33556000000" style="color: var(--text-dark); text-decoration: none; font-weight: bold;">05 56 00 00 00</a>
            </p>
            <p style="margin-bottom: 3rem; font-size: 1.1rem;">
                <strong style="display: block; margin-bottom: 0.5rem;"><i class="fas fa-envelope" style="color: var(--secondary-color); width: 25px;"></i> Email :</strong>
                <a href="mailto:contact@vite-et-gourmand.fr" style="color: var(--text-dark); text-decoration: none; font-weight: bold;">contact@vite-et-gourmand.fr</a>
            </p>

            <h3 style="margin-bottom: 1.5rem; color: var(--primary-color); font-size: 1.5rem;">Nos Horaires</h3>
            <ul style="list-style: none; padding: 0; font-size: 1.1rem;">
                <li style="margin-bottom: 0.8rem; display: flex; justify-content: space-between;"><span>Lundi :</span> <em style="color: #999;">Fermé</em></li>
                <li style="margin-bottom: 0.8rem; display: flex; justify-content: space-between;"><span>Mardi - Samedi :</span> <strong>10h00 - 19h00</strong></li>
                <li style="margin-bottom: 0.8rem; display: flex; justify-content: space-between;"><span>Dimanche :</span> <em style="color: #999;">Fermé</em></li>
            </ul>
        </div>

        <!-- Formulaire -->
        <form method="POST" style="background: var(--primary-color); padding: 3rem; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); border: 3px solid var(--secondary-color);">
            <h3 style="margin-bottom: 2rem; color: var(--secondary-color); font-size: 2rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.6); text-align: center;">Demande de Devis & Contact</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="margin-bottom: 1.5rem;">
                    <label for="nom" style="font-weight: bold; display: block; margin-bottom: 0.8rem; color: #000000; font-size: 1.1rem;">Nom & Prénom <span style="color: var(--secondary-color);">*</span></label>
                    <input type="text" id="nom" name="nom" required class="form-control" style="width: 100%; padding: 12px; font-size: 1.1rem;" value="<?php echo isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['nom'] . ' ' . $_SESSION['user']['prenom']) : ''; ?>">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label for="email" style="font-weight: bold; display: block; margin-bottom: 0.8rem; color: #000000; font-size: 1.1rem;">Email <span style="color: var(--secondary-color);">*</span></label>
                    <input type="email" id="email" name="email" required class="form-control" style="width: 100%; padding: 12px; font-size: 1.1rem;" value="<?php echo isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['email']) : ''; ?>">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="telephone" style="font-weight: bold; display: block; margin-bottom: 0.8rem; color: #000000; font-size: 1.1rem;">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" class="form-control" style="width: 100%; padding: 12px; font-size: 1.1rem;">
            </div>

            <div style="margin-bottom: 2rem;">
                <label for="objet" style="font-weight: bold; display: block; margin-bottom: 0.8rem; color: #000000; font-size: 1.1rem;">Objet de votre demande <span style="color: var(--secondary-color);">*</span></label>
                <select id="objet" name="objet" required class="form-control" style="width: 100%; padding: 12px; font-size: 1.1rem;" onchange="toggleDevisFields(this.value)">
                    <option value="renseignement" <?php echo($defaultObjet == 'renseignement') ? 'selected' : ''; ?>>Demande de Renseignement</option>
                    <option value="devis" <?php echo($defaultObjet == 'devis') ? 'selected' : ''; ?>>Demande de Devis (Prestation sur mesure)</option>
                    <option value="partenariat">Partenariat / Presse</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <!-- Champs Spécifiques Devis (Masqués par défaut) -->
            <div id="devis-fields" style="display: none; border: 2px dashed var(--secondary-color); padding: 2rem; margin-bottom: 2rem; background: rgba(0,0,0,0.15); border-radius: 5px;">
                <h4 style="margin-top: 0; color: var(--secondary-color); margin-bottom: 1.5rem; font-size: 1.3rem; border-bottom: 1px solid var(--secondary-color); padding-bottom: 0.5rem;">Détails de l'événement</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div style="margin-bottom: 1rem;">
                        <label for="date_event" style="color: #000000; font-weight: bold; font-size: 1.1rem;">Date prévue</label>
                        <input type="date" id="date_event" name="date_event" class="form-control" style="width: 100%; padding: 10px;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label for="convives" style="color: #000000; font-weight: bold; font-size: 1.1rem;">Nb de convives</label>
                        <input type="number" id="convives" name="convives" placeholder="ex: 50" class="form-control" style="width: 100%; padding: 10px;" value="<?php echo htmlspecialchars($getGuests); ?>">
                    </div>
                </div>
                <div style="margin-bottom: 1rem; margin-top: 1rem;">
                    <label for="type_event" style="color: #000000; font-weight: bold; font-size: 1.1rem;">Type d'événement</label>
                    <select id="type_event" name="type_event" class="form-control" style="width: 100%; padding: 10px;">
                        <option value="">-- Sélectionner --</option>
                        <option value="mariage">Mariage</option>
                        <option value="entreprise">Séminaire / Entreprise</option>
                        <option value="anniversaire">Anniversaire</option>
                        <option value="cocktail">Cocktail Dinatoire</option>
                    </select>
                </div>
                <div style="margin-bottom: 0.5rem;">
                     <label for="budget" style="color: #000000; font-weight: bold; font-size: 1.1rem;">Budget estimé (€)</label>
                     <input type="number" id="budget" name="budget" placeholder="ex: 2000" class="form-control" style="width: 100%; padding: 10px;">
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label for="message" style="font-weight: bold; display: block; margin-bottom: 0.8rem; color: #000000; font-size: 1.1rem;">Message / Détails <span style="color: var(--secondary-color);">*</span></label>
                <textarea id="message" name="message" required rows="8" class="form-control" style="width: 100%; font-family: inherit; font-size: 1.1rem;"><?php echo htmlspecialchars($defaultMessage); ?></textarea>
            </div>

            <button type="submit" class="btn btn-secondary" style="width: 100%; font-size: 1.3rem; font-weight: bold; padding: 20px; text-transform: uppercase; letter-spacing: 1px;">Envoyer ma demande</button>
        </form>
    </div>
    </div>

    <script>
    function toggleDevisFields(val) {
        const fields = document.getElementById('devis-fields');
        if (val === 'devis') {
            fields.style.display = 'block';
        } else {
            fields.style.display = 'none';
        }
    }
    
    // Init on load based on default selection
    document.addEventListener('DOMContentLoaded', () => {
        toggleDevisFields(document.getElementById('objet').value);
    });
    </script>
</div>

<?php include '../composants/footer.php'; ?>
