document.addEventListener('DOMContentLoaded', async () => {
    const ordersContainer = document.getElementById('orders-container');
    const noOrdersMsg = document.getElementById('no-orders-msg');

    // Auth Check
    let user = null;
    try {
        const authRes = await fetch('/Vite-et-gourmand/noyau_backend/api/v1/auth/me.php');
        const authData = await authRes.json();

        if (!authData.logged_in) {
            window.location.href = '/Vite-et-gourmand/interface_frontend/pages/login.html';
            return;
        }
        user = authData.user;

        // Redirection based on role
        if (user.role === 'admin') {
            window.location.href = '/Vite-et-gourmand/interface_frontend/admin/dashboard.html';
            return;
        } else if (user.role === 'employe') {
            // Pas de dashboard_employe.html encore géré mais pour la logique
            window.location.href = '/Vite-et-gourmand/interface_frontend/employe/dashboard.html';
            return;
        }

        document.getElementById('user-firstname').textContent = user.prenom;

    } catch (e) {
        console.error("Auth error", e);
        window.location.href = '/Vite-et-gourmand/interface_frontend/pages/login.html';
        return;
    }

    // Load Orders
    if (ordersContainer) {
        try {
            const resp = await fetch('/Vite-et-gourmand/noyau_backend/api/v1/user/orders.php');
            const data = await resp.json();

            if (data.success && data.orders.length > 0) {
                noOrdersMsg.style.display = 'none';

                let html = '';
                data.orders.forEach(order => {
                    const orderDate = new Date(order.created_at).toLocaleDateString('fr-FR');
                    const statusClass = "padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; background: #dfe6e9; color: #2d3436;";
                    const statusText = order.statut.replace('_', ' ');

                    let itemsHtml = '';
                    order.items.forEach(item => {
                        itemsHtml += `<p style="margin: 0; font-weight: bold;">${item.titre} x${item.quantite}</p>`;
                        if (item.choices_names && Object.keys(item.choices_names).length > 0) {
                            itemsHtml += `<ul style="font-size: 0.85rem; color: #666; margin: 0.2rem 0 0.5rem 1rem; padding: 0;">`;
                            for (const [type, name] of Object.entries(item.choices_names)) {
                                itemsHtml += `<li>${type.charAt(0).toUpperCase() + type.slice(1)} : ${name}</li>`;
                            }
                            itemsHtml += `</ul>`;
                        }
                    });

                    let actionsHtml = '';
                    if (order.statut === 'en_attente') {
                        actionsHtml = `
                            <a href="#" class="btn" style="background: #fab1a0; color: #c0392b; font-size: 0.8rem;">Annuler</a>
                            <a href="#" class="btn" style="background: #74b9ff; color: white; font-size: 0.8rem;">Modifier</a>
                        `;
                    } else if (order.statut === 'terminee') {
                        actionsHtml = `<a href="/Vite-et-gourmand/interface_frontend/pages/review.html?order_id=${order.id}" class="btn btn-primary" style="font-size: 0.8rem;">Laisser un avis</a>`;
                    }

                    html += `
                        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                            <div>
                                <h4 style="margin: 0; color: var(--secondary-color);">Commande #${order.id}</h4>
                                <p style="font-size: 0.9rem; color: #888;">Du ${orderDate}</p>
                                <p style="margin-top: 0.5rem;">
                                    <strong>Statut : </strong> 
                                    <span style="${statusClass}">${statusText.charAt(0).toUpperCase() + statusText.slice(1)}</span>
                                </p>
                                <div style="margin-top: 1rem; padding-top: 0.5rem; border-top: 1px dashed #eee;">
                                    ${itemsHtml}
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-size: 1.2rem; font-weight: bold; color: var(--primary-color);">${order.prix_total} €</p>
                                <p style="font-size: 0.9rem;">
                                    Livraison le ${new Date(order.date_livraison).toLocaleDateString('fr-FR')} <br>
                                    à ${order.heure_livraison.substring(0, 5)}
                                </p>
                            </div>
                            <div style="width: 100%; margin-top: 1rem; border-top: 1px solid #eee; padding-top: 1rem; text-align: right;">
                                ${actionsHtml}
                            </div>
                        </div>
                    `;
                });

                ordersContainer.innerHTML = html;
            } else {
                ordersContainer.innerHTML = '';
                noOrdersMsg.style.display = 'block';
            }
        } catch (err) {
            console.error("Erreur chargement commandes :", err);
        }
    }
});
