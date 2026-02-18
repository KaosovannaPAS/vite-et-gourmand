<?php
include __DIR__ . '/../../noyau_backend/configuration/db.php';
include __DIR__ . '/../composants/header.php';
?>

<div class="container" style="padding: 4rem 0; line-height: 1.6;">
    <h2 style="color: var(--primary-color); border-bottom: 2px solid var(--secondary-color); padding-bottom: 1rem; margin-bottom: 2rem;">Conditions Générales de Vente</h2>
    
    <section style="margin-bottom: 3rem;">
        <h3 style="color: var(--secondary-color);">1. Prêt de matériel</h3>
        <p>Dans le cadre de certaines prestations, du matériel (vaisselle, contenants, matériel de service) peut être prêté au client.</p>
        <p>Ce matériel demeure la propriété exclusive de <strong>Vite & Gourmand</strong>. Il doit être restitué dans son état d'origine dès la fin de la prestation ou selon les modalités convenues.</p>
    </section>

    <section style="margin-bottom: 3rem; background: rgba(0,0,0,0.05); padding: 2rem; border-radius: 5px; border-left: 5px solid var(--secondary-color);">
        <h3 style="color: var(--secondary-color);">2. Restitution et Frais de non-restitution</h3>
        <p>Dès que le statut de la commande passe en <strong>"En attente du retour de matériel"</strong>, le client dispose d'un délai de <strong>10 jours ouvrés</strong> pour restituer l'intégralité du matériel prêté.</p>
        <p>Passé ce délai, et sans retour de la part du client, une facturation forfaitaire de <strong>600,00 € TTC</strong> sera automatiquement appliquée au titre des frais de remplacement et de préjudice d'immobilisation.</p>
        <p><strong>Modalités de retour :</strong> Le client doit impérativement prendre contact avec la société (par mail ou téléphone) pour organiser la restitution du matériel.</p>
    </section>

    <section>
        <h3 style="color: var(--secondary-color);">3. Responsabilité</h3>
        <p>Le client est responsable du matériel dès sa mise à disposition et jusqu'à sa restitution effective entre les mains d'un membre de l'équipe Vite & Gourmand.</p>
    </section>
</div>

<?php include __DIR__ . '/../composants/footer.php'; ?>
