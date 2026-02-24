document.addEventListener('DOMContentLoaded', async () => {
    // Auth Check
    const authRes = await fetch('/Vite-et-gourmand/noyau_backend/api/v1/auth/me.php');
    const authData = await authRes.json();
    if (!authData.logged_in || (authData.user.role !== 'employe' && authData.user.role !== 'admin')) {
        window.location.href = '/Vite-et-gourmand/index.html';
        return;
    }

    const tbody = document.getElementById('table-employes-orders');

    // Filtres
    const filtersContainer = document.getElementById('status-filters');
    const btnTous = document.getElementById('filter-tous');
    const btnAttente = document.getElementById('filter-attente');
    const btnPrep = document.getElementById('filter-prep');
    const btnLivraison = document.getElementById('filter-livraison');

    let currentFilter = ''; // empty means all
    let allOrders = [];

    async function loadOrders() {
        try {
            const res = await fetch('/Vite-et-gourmand/noyau_backend/api/v1/orders.php');
            allOrders = await res.json();
            renderOrders();
        } catch (e) {
            console.error('Failed to load orders', e);
        }
    }

    function renderOrders() {
        tbody.innerHTML = '';

        let filteredOrders = allOrders;
        if (currentFilter) {
            filteredOrders = allOrders.filter(o => o.statut === currentFilter);
        }

        filteredOrders.forEach(order => {
            const tr = document.createElement('tr');
            tr.style.borderBottom = '1px solid #eee';

            // Selected value helper
            const sel = (val) => order.statut === val ? 'selected' : '';

            tr.innerHTML = `
                <td style="padding: 1rem;">#${order.id}</td>
                <td style="padding: 1rem;">
                    ${order.nom} ${order.prenom}<br>
                    <small>${order.email}</small><br>
                    <small>${order.lieu_livraison || ''}</small>
                </td>
                <td style="padding: 1rem;">
                    ${order.date_livraison}<br>
                    ${order.heure_livraison}
                </td>
                <td style="padding: 1rem; font-weight: bold;">${order.prix_total} €</td>
                <td style="padding: 1rem;">
                    <select class="status-select" data-id="${order.id}" style="padding: 5px; border-radius: 5px; border: 1px solid #ddd;">
                        <option value="en_attente" ${sel('en_attente')}>En Attente</option>
                        <option value="accepte" ${sel('accepte')}>Accepté</option>
                        <option value="preparation" ${sel('preparation')}>Préparation</option>
                        <option value="livraison" ${sel('livraison')}>Livraison</option>
                        <option value="livre" ${sel('livre')}>Livré</option>
                        <option value="terminee" ${sel('terminee')}>Terminée</option>
                        <option value="retour_materiel_en_attente" ${sel('retour_materiel_en_attente')}>En attente du retour de matériel</option>
                        <option value="annulee" ${sel('annulee')}>Annulée</option>
                    </select>
                </td>
                <td style="padding: 1rem;">
                    <button class="btn btn-primary toggle-details" data-id="${order.id}" style="font-size: 0.8rem; cursor: pointer;">Détail</button>
                </td>
            `;

            // Detail row (using basic items for now as the full details need choices logic which is already returned in items by orders.php if it supports it, assuming it returns items if we pass the right flag, or we fetch them)
            // Note: the backend orders.php currently doesn't fetch order_items in getAll.
            // For the sake of extraction, we will just display a placeholder or fetch details on click in a real app.

            const detailTr = document.createElement('tr');
            detailTr.id = `details-${order.id}`;
            detailTr.style.display = 'none';
            detailTr.style.background = '#f9f9f9';
            // We just render a simple string with the json stringified items if available
            const itemsStr = order.items ? order.items.map(i => i.titre + ' x' + i.quantite).join(', ') : 'Détails non récupérés par l\'API (/api/v1/orders.php ne join pas les items dans le getAll).';

            detailTr.innerHTML = `
                <td colspan="6" style="padding: 1rem;">
                    <strong>Contenu de la commande :</strong>
                    <div style="margin-top: 0.5rem; font-size: 0.9rem; color: #555;">
                        ${itemsStr}
                    </div>
                </td>
            `;

            tbody.appendChild(tr);
            tbody.appendChild(detailTr);
        });

        // Event listeners for selects
        document.querySelectorAll('.status-select').forEach(sel => {
            sel.addEventListener('change', async (e) => {
                const orderId = e.target.getAttribute('data-id');
                const newStatus = e.target.value;
                try {
                    const res = await fetch(\`/Vite-et-gourmand/noyau_backend/api/v1/orders.php?id=\${orderId}\`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ statut: newStatus })
                    });
                    if (res.ok) {
                        alert('Statut mis à jour avec succès');
                        loadOrders();
                    } else {
                        alert('Erreur lors de la mise à jour du statut');
                    }
                } catch (err) {
                    console.error('Fetch error:', err);
                }
            });
        });

        // Event listeners for details toggles
        document.querySelectorAll('.toggle-details').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const orderId = e.target.getAttribute('data-id');
                const detailRow = document.getElementById(\`details-\${orderId}\`);
                detailRow.style.display = detailRow.style.display === 'none' ? 'table-row' : 'none';
            });
        });
    }

    // Filters logic
    function setActiveFilterBtn(btn) {
        [btnTous, btnAttente, btnPrep, btnLivraison].forEach(b => b.style.opacity = '0.5');
        btn.style.opacity = '1';
    }

    btnTous.addEventListener('click', (e) => { e.preventDefault(); currentFilter = ''; setActiveFilterBtn(btnTous); renderOrders(); });
    btnAttente.addEventListener('click', (e) => { e.preventDefault(); currentFilter = 'en_attente'; setActiveFilterBtn(btnAttente); renderOrders(); });
    btnPrep.addEventListener('click', (e) => { e.preventDefault(); currentFilter = 'preparation'; setActiveFilterBtn(btnPrep); renderOrders(); });
    btnLivraison.addEventListener('click', (e) => { e.preventDefault(); currentFilter = 'livraison'; setActiveFilterBtn(btnLivraison); renderOrders(); });

    // Initial load
    setActiveFilterBtn(btnTous);
    loadOrders();
});
