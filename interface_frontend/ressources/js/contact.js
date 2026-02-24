document.addEventListener('DOMContentLoaded', async () => {
    const contactForm = document.getElementById('contact-form');
    const objetSelect = document.getElementById('objet');
    const devisFields = document.getElementById('devis-fields');
    const messageAlert = document.getElementById('message-alert');

    const toggleDevisFields = (val) => {
        if (val === 'devis') {
            devisFields.style.display = 'block';
        } else {
            devisFields.style.display = 'none';
        }
    };

    objetSelect.addEventListener('change', (e) => toggleDevisFields(e.target.value));

    // Pre-fill from URL params
    const urlParams = new URLSearchParams(window.location.search);
    const sujet = urlParams.get('sujet');
    const menuTitle = urlParams.get('menu');
    const guests = urlParams.get('guests');

    if (sujet === 'Devis') {
        objetSelect.value = 'devis';
        if (menuTitle) {
            document.getElementById('message').value = `Bonjour,\n\nJe souhaiterais obtenir un devis pour le menu : ${menuTitle}.\n`;
        }
        if (guests) {
            document.getElementById('convives').value = guests;
        }
    }
    toggleDevisFields(objetSelect.value);

    // Pre-fill user data if logged in
    try {
        const authRes = await fetch('/backend_prod/api/v1/auth/me.php');
        const authData = await authRes.json();
        if (authData.logged_in && authData.user) {
            document.getElementById('nom').value = `${authData.user.nom} ${authData.user.prenom}`.trim();
            document.getElementById('email').value = authData.user.email;
        }
    } catch (e) { console.error('Erreur pré-remplissage auth:', e); }

    // Form submission
    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            messageAlert.style.display = 'none';

            const formData = new FormData(contactForm);
            const data = Object.fromEntries(formData.entries());

            // Check required fields manually just in case
            if (!data.nom || !data.email || !data.message) {
                messageAlert.style.background = '#fab1a0';
                messageAlert.style.color = '#c0392b';
                messageAlert.textContent = "Veuillez remplir les champs obligatoires.";
                messageAlert.style.display = 'block';
                return;
            }

            try {
                const response = await fetch('/backend_prod/api/v1/contact.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (response.ok && result.success) {
                    messageAlert.style.background = '#b8e994';
                    messageAlert.style.color = '#218c74';
                    messageAlert.textContent = result.message;
                    messageAlert.style.display = 'block';
                    contactForm.reset();
                    toggleDevisFields(objetSelect.value); // Hide devis fields if it reset to default
                } else {
                    messageAlert.style.background = '#fab1a0';
                    messageAlert.style.color = '#c0392b';
                    messageAlert.textContent = result.message || "Erreur lors de l'envoi.";
                    messageAlert.style.display = 'block';
                }
            } catch (err) {
                console.error("Erreur req AJAX :", err);
                messageAlert.style.background = '#fab1a0';
                messageAlert.style.color = '#c0392b';
                messageAlert.textContent = "Le serveur est injoignable.";
                messageAlert.style.display = 'block';
            }
        });
    }
});
