document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('register-form');
    const messageAlert = document.getElementById('message-alert');

    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            messageAlert.style.display = 'none';

            const formData = new FormData(registerForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/noyau_backend/api/v1/auth/register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (response.ok && result.success) {
                    messageAlert.style.background = '#b8e994';
                    messageAlert.style.color = '#218c74';
                    messageAlert.textContent = result.message;
                    messageAlert.style.display = 'block';
                    registerForm.reset();
                    // Optional redirect after delay
                    setTimeout(() => window.location.href = '/interface_frontend/pages/login.html', 2000);
                } else {
                    messageAlert.style.background = '#fab1a0';
                    messageAlert.style.color = '#c0392b';
                    messageAlert.textContent = result.message || "Erreur lors de l'inscription.";
                    messageAlert.style.display = 'block';
                }
            } catch (err) {
                console.error("Erreur req AJAX :", err);
                messageAlert.style.background = '#fab1a0';
                messageAlert.style.color = '#c0392b';
                messageAlert.textContent = "Le serveur est injoignable.";
                messageAlert.style.display = 'block';
            }
        });
    }
});
