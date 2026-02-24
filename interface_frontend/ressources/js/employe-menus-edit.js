document.addEventListener('DOMContentLoaded', async () => {
    // Auth Check
    const authRes = await fetch('/backend_prod/api/v1/auth/me.php');
    const authData = await authRes.json();
    if (!authData.logged_in || (authData.user.role !== 'employe' && authData.user.role !== 'admin')) {
        window.location.href = '/index.html';
        return;
    }

    const grid = document.getElementById('menus-grid');

    async function loadMenus() {
        try {
            // Fetch all menus using the 'all=true' flag for admins/employes
            const res = await fetch('/backend_prod/api/v1/menus.php?all=true');
            const menus = await res.json();

            grid.innerHTML = '';
            menus.forEach(menu => {
                const card = document.createElement('div');
                card.style.background = 'white';
                card.style.borderRadius = '10px';
                card.style.overflow = 'hidden';
                card.style.boxShadow = '0 2px 10px rgba(0,0,0,0.05)';

                let img = menu.image_url ? (menu.image_url.startsWith('http') || menu.image_url.startsWith('/') ? menu.image_url : BASE_URL + '/' + menu.image_url) : 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80';

                card.innerHTML = `
                    <img src="\${img}" style="width: 100%; height: 200px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80'">
                    <div style="padding: 1.5rem;">
                        <h3 style="margin-top:0;">\${menu.titre}</h3>
                        <p style="font-weight: bold;">\${menu.prix} €</p>
                        <p>Stock: \${menu.stock}</p>
                        <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                            <a href="/interface_frontend/employe/menu_add.html?id=\${menu.id}" class="btn" style="background: #74b9ff; color: white;">Modifier</a>
                            <button class="btn delete-menu" data-id="\${menu.id}" style="background: #ff7675; color: white; border:none; cursor:pointer;">Supprimer</button>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });

            // Delete logic
            document.querySelectorAll('.delete-menu').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    if (confirm('Supprimer ce menu ?')) {
                        const id = e.target.getAttribute('data-id');
                        try {
                            const delRes = await fetch(\`/backend_prod/api/v1/menus.php?id=\${id}\`, { method: 'DELETE' });
                            if (delRes.ok) {
                                loadMenus();
                            } else {
                                alert('Erreur lors de la suppression');
                            }
                        } catch(err) {
                            console.error(err);
                        }
                    }
                });
            });

        } catch (e) {
            console.error('Failed to load menus', e);
        }
    }

    loadMenus();
});
