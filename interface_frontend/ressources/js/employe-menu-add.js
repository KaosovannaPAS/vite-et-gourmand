document.addEventListener('DOMContentLoaded', async () => {
    // Auth Check
    const authRes = await fetch('/noyau_backend/api/v1/auth/me.php');
    const authData = await authRes.json();
    if (!authData.logged_in || (authData.user.role !== 'employe' && authData.user.role !== 'admin')) {
        window.location.href = '/index.html';
        return;
    }

    const form = document.getElementById('menu-add-form');
    const urlParams = new URLSearchParams(window.location.search);
    const menuId = urlParams.get('id');

    if (menuId) {
        document.getElementById('page-title').textContent = 'Modifier un Menu';
        // Fetch existing
        try {
            const res = await fetch(\`/noyau_backend/api/v1/menus.php?id=\${menuId}\`);
            const menu = await res.json();
            if(!menu.error) {
                form.titre.value = menu.titre || '';
                form.description.value = menu.description || '';
                form.prix.value = menu.prix || '';
                form.min_personnes.value = menu.min_personnes || 1;
                form.stock.value = menu.stock || 10;
                form.theme.value = menu.theme || 'Classique';
                form.regime.value = menu.regime || 'classique';
                form.image_url.value = menu.image_url || '';
            }
        } catch(e) {
            console.error('Failed to load menu details', e);
        }
    } else {
        document.getElementById('page-title').textContent = 'Ajouter un Menu';
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const payload = {
            titre: form.titre.value,
            description: form.description.value,
            prix: parseFloat(form.prix.value),
            min_personnes: parseInt(form.min_personnes.value, 10),
            stock: parseInt(form.stock.value, 10),
            theme: form.theme.value,
            regime: form.regime.value,
            image_url: form.image_url.value
        };

        try {
            const endpoint = menuId 
                ? \`/noyau_backend/api/v1/menus.php?id=\${menuId}\` 
                : '/noyau_backend/api/v1/menus.php';
                
            const method = menuId ? 'PUT' : 'POST';

            const res = await fetch(endpoint, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                window.location.href = '/interface_frontend/employe/menus_edit.html';
            } else {
                alert('Erreur lors de l\\'enregistrement du menu.');
            }
        } catch (err) {
            console.error(err);
            alert('Erreur réseau');
        }
    });
});
