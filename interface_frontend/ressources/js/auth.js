document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('/noyau_backend/api/v1/auth/me.php');
        const authData = await response.json();
        const authMenuContainer = document.getElementById('auth-menu');

        if (!authMenuContainer) return; // Si la page n'a pas de menu nav

        if (authData.logged_in) {
            let dashboardLink = '/interface_frontend/pages/dashboard.html';
            let dashboardText = 'Mon Compte';

            if (authData.user.role === 'admin') {
                dashboardLink = '/interface_frontend/admin/dashboard.html';
                dashboardText = 'Espace Admin';
            } else if (authData.user.role === 'employe') {
                dashboardLink = '/interface_frontend/employe/dashboard.html';
                dashboardText = 'Espace Employé';
            }

            authMenuContainer.innerHTML = `
                <li><a href="${dashboardLink}">${dashboardText}</a></li>
                <li><a href="/noyau_backend/api/v1/auth/logout.php" class="btn btn-primary" id="logout-btn">Déconnexion</a></li>
            `;

            // On peut sécuriser la déconnexion via fetch plus tard
        } else {
            authMenuContainer.innerHTML = `
                <li><a href="/interface_frontend/pages/login.html" class="btn btn-primary">Connexion</a></li>
            `;
        }
    } catch (e) {
        console.error("Erreur d'authentification:", e);
    }
});
