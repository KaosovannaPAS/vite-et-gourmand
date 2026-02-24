document.addEventListener('DOMContentLoaded', async () => {
    const reviewForm = document.getElementById('review-form');
    const msgAlert = document.getElementById('msg-alert');
    const authAlert = document.getElementById('auth-alert');
    const formContainer = document.getElementById('form-container');

    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');

    if (!orderId) {
        window.location.href = '/interface_frontend/pages/dashboard.html';
        return;
    }

    document.getElementById('order-id-display').textContent = orderId;

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
        formContainer.style.display = 'block';
    } catch (e) { console.error('Erreur Auth:', e); return; }

    if (reviewForm) {
        reviewForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            msgAlert.style.display = 'none';

            const payload = {
                order_id: orderId,
                note: document.getElementById('note').value,
                comment: document.getElementById('comment').value
            };

            try {
                const response = await fetch('/backend_prod/api/v1/user/review.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await response.json();

                if (result.success) {
                    msgAlert.style.background = '#55efc4';
                    msgAlert.style.color = '#00b894';
                    msgAlert.innerHTML = `${result.message}<br><a href="/interface_frontend/pages/dashboard.html" class="btn" style="margin-top: 1rem; display: inline-block; background: white; color: #00b894;">Retour au tableau de bord</a>`;
                    msgAlert.style.display = 'block';
                    formContainer.style.display = 'none';
                } else {
                    msgAlert.style.background = '#fab1a0';
                    msgAlert.style.color = '#c0392b';
                    msgAlert.textContent = result.error || "Erreur lors de l'enregistrement.";
                    msgAlert.style.display = 'block';
                }
            } catch (err) {
                console.error("Erreur req AJAX :", err);
                msgAlert.style.background = '#fab1a0';
                msgAlert.style.color = '#c0392b';
                msgAlert.textContent = "Le serveur est injoignable.";
                msgAlert.style.display = 'block';
            }
        });
    }
});
