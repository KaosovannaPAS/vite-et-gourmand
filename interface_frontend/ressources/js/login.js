document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const errorBox = document.getElementById('login-error');
    const errorMsg = document.getElementById('error-message');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorBox.style.display = 'none';

            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/Vite-et-gourmand/noyau_backend/api/v1/auth/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    window.location.href = result.redirect;
                } else {
                    errorMsg.textContent = result.message || "Erreur lors de la connexion.";
                    errorBox.style.display = 'block';
                }
            } catch (err) {
                console.error("Erreur req AJAX :", err);
                errorMsg.textContent = "Serveur injoignable. Veuillez réessayer.";
                errorBox.style.display = 'block';
            }
        });
    }
});
