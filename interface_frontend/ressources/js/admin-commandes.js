const API_URL = '/noyau_backend/api/v1/orders.php';

document.addEventListener('DOMContentLoaded', async () => {
    // Auth Check : Admin Only
    try {
        const authRes = await fetch('/noyau_backend/api/v1/auth/me.php');
        const authData = await authRes.json();

        if (!authData.logged_in || authData.user.role !== 'admin') {
            window.location.href = '/interface_frontend/pages/login.html';
            return;
        }

        // Populate user info
        const user = authData.user;
        document.getElementById('admin-name').textContent = `Admin (${user.prenom})`;
        document.getElementById('admin-avatar').textContent = user.prenom.charAt(0).toUpperCase() + user.nom.charAt(0).toUpperCase();

        loadOrders();
    } catch (e) {
        console.error('Auth error:', e);
        window.location.href = '/interface_frontend/pages/login.html';
    }
});

function loadOrders() {
    fetch(API_URL)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('table-orders');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Aucune commande trouvée.</td></tr>';
                return;
            }

            data.forEach(o => {
                const createDate = new Date(o.created_at).toLocaleDateString('fr-FR');
                const livDate = new Date(o.date_livraison).toLocaleDateString('fr-FR');
                const livTime = o.heure_livraison.substring(0, 5);
                const total = parseFloat(o.prix_total).toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });

                // Select setup
                const statuses = [
                    { val: 'en_attente', label: 'En attente' },
                    { val: 'en_preparation', label: 'En préparation' },
                    { val: 'terminee', label: 'Terminée' },
                    { val: 'annullee', label: 'Annulée' }
                ];
                let selectHtml = `<select class="status-select status-${o.statut}" onchange="changeStatus(${o.id}, this)">`;
                statuses.forEach(s => {
                    const sel = s.val === o.statut ? 'selected' : '';
                    selectHtml += `<option value="${s.val}" ${sel}>${s.label}</option>`;
                });
                selectHtml += `</select>`;

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong>#${o.id}</strong></td>
                    <td>${o.prenom} ${o.nom}</td>
                    <td>${createDate}</td>
                    <td>${livDate} à ${livTime}</td>
                    <td>${o.lieu_livraison.replace('Bordeaux', 'BDX')}</td>
                    <td style="font-weight:bold;">${total}</td>
                    <td>${selectHtml}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => console.error("API error", err));
}

window.changeStatus = function (orderId, selectEl) {
    const newStatus = selectEl.value;
    selectEl.className = 'status-select status-' + newStatus;

    fetch(API_URL + '?id=' + orderId, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ statut: newStatus })
    })
        .then(res => res.json())
        .then(data => {
            if (data.message !== "Statut mis à jour") {
                alert("Erreur lors de la mise à jour");
                loadOrders(); // Revert
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erreur réseau");
            loadOrders();
        });
};
