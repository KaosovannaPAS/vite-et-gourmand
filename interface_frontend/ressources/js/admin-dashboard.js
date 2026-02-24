document.addEventListener('DOMContentLoaded', async () => {
    // Auth Check : Admin Only
    try {
        const authRes = await fetch('/Vite-et-gourmand/noyau_backend/api/v1/auth/me.php');
        const authData = await authRes.json();

        if (!authData.logged_in || authData.user.role !== 'admin') {
            window.location.href = '/Vite-et-gourmand/interface_frontend/pages/login.html';
            return;
        }

        // Populate user info
        const user = authData.user;
        document.getElementById('admin-name').textContent = `Admin (${user.prenom})`;
        document.getElementById('admin-avatar').textContent = user.prenom.charAt(0).toUpperCase() + user.nom.charAt(0).toUpperCase();

    } catch (e) {
        console.error('Auth error:', e);
        window.location.href = '/Vite-et-gourmand/interface_frontend/pages/login.html';
        return;
    }

    // Load Stats
    const API_URL = '/Vite-et-gourmand/noyau_backend/api/v1/stats.php';
    fetch(API_URL)
        .then(res => res.json())
        .then(data => {
            if (data.error) return alert("Erreur: " + data.error);

            document.getElementById('stat-orders').textContent = data.active_orders;
            const rev = parseFloat(data.revenue_today || 0).toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });
            document.getElementById('stat-revenue').textContent = rev;
            document.getElementById('stat-reviews').textContent = data.pending_reviews;

            const tbody = document.getElementById('table-orders');
            tbody.innerHTML = '';

            if (!data.recent_orders || data.recent_orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Aucune commande récente.</td></tr>';
                return;
            }

            data.recent_orders.forEach(o => {
                let statusClass = 'warning';
                let statusText = o.statut;
                if (o.statut === 'terminee') { statusClass = 'success'; statusText = 'Terminée'; }
                else if (o.statut === 'annullee') { statusClass = 'danger'; statusText = 'Annulée'; }
                else if (o.statut === 'en_preparation') { statusClass = 'info'; statusText = 'En préparation'; }
                else { statusText = 'En attente'; }

                const dateLiv = new Date(o.date_livraison).toLocaleDateString('fr-FR');
                const heureLiv = o.heure_livraison.substring(0, 5);

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>#${o.id}</td>
                    <td>${o.prenom} ${o.nom}</td>
                    <td>${dateLiv} - ${heureLiv}</td>
                    <td>${parseFloat(o.prix_total).toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' })}</td>
                    <td><span class="status ${statusClass}">${statusText}</span></td>
                    <td><a href="/Vite-et-gourmand/interface_frontend/admin/commandes.html?id=${o.id}" class="action-btn">Voir</a></td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => console.error("API error", err));
});
