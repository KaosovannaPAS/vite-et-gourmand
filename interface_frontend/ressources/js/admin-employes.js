const API_URL = '/Vite-et-gourmand/noyau_backend/api/v1/users.php';
let MY_ID = null;

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
        MY_ID = user.id;
        document.getElementById('admin-name').textContent = `Admin (${user.prenom})`;
        document.getElementById('admin-avatar').textContent = user.prenom.charAt(0).toUpperCase() + user.nom.charAt(0).toUpperCase();

        loadUsers();
    } catch (e) {
        console.error('Auth error:', e);
        window.location.href = '/Vite-et-gourmand/interface_frontend/pages/login.html';
    }

    document.getElementById('add-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const payload = {
            prenom: document.getElementById('f_prenom').value.trim(),
            nom: document.getElementById('f_nom').value.trim(),
            email: document.getElementById('f_email').value.trim(),
            password: document.getElementById('f_password').value.trim(),
            telephone: document.getElementById('f_telephone').value.trim()
        };

        fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(r => r.json())
            .then(data => {
                if (data.error) alert(data.error);
                else {
                    closeModal();
                    loadUsers();
                }
            })
            .catch(err => alert("Erreur serveur"));
    });
});

function loadUsers() {
    fetch(API_URL)
        .then(res => res.json())
        .then(data => {
            if (data.error) return alert("Erreur: " + data.error);
            const tbody = document.getElementById('table-employes');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">Aucun employé trouvé.</td></tr>';
                return;
            }

            data.forEach(u => {
                const tr = document.createElement('tr');

                let actionHtml = '';
                if (u.id !== MY_ID) {
                    actionHtml = `<button class="action-btn btn-danger" onclick="deleteUser(${u.id})"><i class="fas fa-trash"></i></button>`;
                } else {
                    actionHtml = `<span style="color:#aaa;font-size:12px;">Vous-même</span>`;
                }

                tr.innerHTML = `
                    <td>#${u.id}</td>
                    <td>${u.prenom} ${u.nom}</td>
                    <td>${u.email}</td>
                    <td>${u.telephone || '-'}</td>
                    <td><span style="background:${u.role === 'admin' ? '#ffeaa7' : '#dfe6e9'}; padding:4px 8px; border-radius:4px; font-size:11px; font-weight:bold;">${u.role.toUpperCase()}</span></td>
                    <td>${actionHtml}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => console.error("API error", err));
}

window.deleteUser = function (id) {
    if (!confirm("Êtes-vous sûr de vouloir supprimer cet employé ?")) return;
    fetch(API_URL + '?id=' + id, { method: 'DELETE' })
        .then(r => r.json())
        .then(data => {
            if (data.error) alert(data.error);
            else {
                loadUsers();
            }
        })
        .catch(err => alert("Erreur serveur"));
};

window.openModal = function () { document.getElementById('add-modal').style.display = 'flex'; };
window.closeModal = function () { document.getElementById('add-modal').style.display = 'none'; document.getElementById('add-form').reset(); };
