document.addEventListener('DOMContentLoaded', async () => {
    const orderForm = document.getElementById('order-form');
    const orderApp = document.getElementById('order-app');
    const authAlert = document.getElementById('auth-alert');
    const errorAlert = document.getElementById('error-alert');

    // Retrieve URL params
    const urlParams = new URLSearchParams(window.location.search);
    const menuId = urlParams.get('menu_id');
    const quantite = urlParams.get('quantite') || 1;
    let choicesStr = urlParams.get('choices');
    let choices = {};
    if (choicesStr) {
        try { choices = JSON.parse(decodeURIComponent(choicesStr)); } catch (e) { }
    }

    if (!menuId) {
        window.location.href = '/interface_frontend/pages/menus.html';
        return;
    }

    // Auth check
    let user = null;
    try {
        const authRes = await fetch('/backend_prod/api/v1/auth/me.php');
        const authData = await authRes.json();

        if (!authData.logged_in) {
            authAlert.style.display = 'block';
            return;
        }
        user = authData.user;
        orderApp.style.display = 'block';

        // Pre-fill user data
        document.getElementById('user-name').textContent = `${user.nom} ${user.prenom}`;
        document.getElementById('user-email').textContent = user.email;
        if (user.adresse) {
            document.getElementById('lieu').value = user.adresse;
        }

    } catch (e) { console.error('Erreur Auth:', e); return; }

    // Fetch menu details for preview
    try {
        const menuRes = await fetch(`/backend_prod/api/v1/menus.php?id=${menuId}`);
        const menuData = await menuRes.json();

        if (menuData.error || !menuData.id) {
            window.location.href = '/interface_frontend/pages/menus.html';
            return;
        }

        document.getElementById('menu-preview-title').textContent = menuData.titre;
        document.getElementById('menu-preview-desc').textContent = menuData.description || '';
        document.getElementById('menu-preview-qty').textContent = quantite;

        let choicesHtml = '';
        if (Object.keys(choices).length > 0) {
            choicesHtml += '<strong>Vos choix :</strong><ul>';
            for (const [type, dishId] of Object.entries(choices)) {
                choicesHtml += `<li>${type} (ID: ${dishId})</li>`;
            }
            choicesHtml += '</ul>';
        }
        document.getElementById('menu-preview-choices').innerHTML = choicesHtml;

    } catch (e) {
        console.error('Erreur Menu Fetch:', e);
    }

    // Submit Order
    if (orderForm) {
        orderForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorAlert.style.display = 'none';

            const payload = {
                menu_id: menuId,
                quantite: quantite,
                date_livraison: document.getElementById('date_livraison').value,
                heure_livraison: document.getElementById('heure_livraison').value,
                lieu: document.getElementById('lieu').value,
                distance: document.getElementById('distance').value,
                choices: choices
            };

            try {
                const response = await fetch('/backend_prod/api/v1/order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await response.json();

                if (result.success && result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    errorAlert.textContent = result.error || 'Erreur lors de la validation.';
                    errorAlert.style.display = 'block';
                }
            } catch (err) {
                console.error("Erreur serveur :", err);
                errorAlert.textContent = 'Erreur réseau. Veuillez réessayer.';
                errorAlert.style.display = 'block';
            }
        });
    }

});
