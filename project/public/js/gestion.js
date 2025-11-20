

const API = '/controllers/UserController.php';

document.addEventListener('DOMContentLoaded', () => {
    const tbody = document.querySelector('.users-table tbody');
    const stats = document.querySelectorAll('.stat-number');
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');
    const openAddBtn = document.getElementById('openAddBtn');
    const userModal = document.getElementById('userModal');
    const userForm = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');
    const cancelBtn = document.getElementById('cancelBtn');
    const closeModalBtn = document.querySelector('.close-modal');

    let users = [];

    
    async function fetchUsers() {
        try {
            const res = await fetch(`${API}?action=getAll`);
            const json = await res.json();

            if (json.success && Array.isArray(json.data)) {
                users = json.data;
                renderUsers(users);
                updateStats(users);
            } else {
                alert('Impossible de charger les utilisateurs.');
            }
        } catch (err) {
            console.error('Erreur fetchUsers:', err);
            alert('Erreur réseau lors du chargement des utilisateurs.');
        }
    }

    
    function updateStats(list) {
        const total = list.length;
        const students = list.filter(u => u.user_type === 'student').length;
        const professors = list.filter(u => u.user_type === 'professor').length;
        const admins = list.filter(u => u.user_type === 'admin').length;

        
        if (stats[0]) stats[0].textContent = total;
        if (stats[1]) stats[1].textContent = students;
        if (stats[2]) stats[2].textContent = professors;
        if (stats[3]) stats[3].textContent = admins; 
    }

    
    function renderUsers(list) {
        tbody.innerHTML = '';
        if (!list || list.length === 0) {
            tbody.innerHTML = `<tr class="empty-row"><td colspan="10">Aucun utilisateur pour le moment.</td></tr>`;
            return;
        }

        list.forEach(u => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${escapeHtml(u.student_id || '-')}</td>
                <td>${escapeHtml(u.first_name || '')} ${escapeHtml(u.last_name || '')}</td>
                <td>${escapeHtml(u.email || '')}</td>
                <td>
                    <span class="badge badge-${u.user_type || 'unknown'}">
                        ${formatUserType(u.user_type)}
                    </span>
                </td>
                <td>${u.created_at ? new Date(u.created_at).toLocaleDateString('fr-FR') : '-'}</td>
                <td>${escapeHtml(u.interests || '-')}</td>
                <td>${escapeHtml(u.department || '-')}</td>
                <td>${escapeHtml(u.phone || '-')}</td>
                <td>${escapeHtml(u.year || '-')}</td>
                <td class="actions">
                    <button class="action-btn edit-btn" data-action="edit" data-id="${u.id}" title="Modifier">
                        Modifier
                    </button>
                    <button class="action-btn delete-btn" data-action="delete" data-id="${u.id}" title="Supprimer">
                        Supprimer
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatUserType(type) {
        switch (type)) {
            case 'student': return 'Étudiant';
            case 'professor': return 'Professeur';
            case 'admin': return 'Administrateur';
            default: return 'Inconnu';
        }
    }

    
    searchBtn.addEventListener('click', () => {
        const term = searchInput.value.trim().toLowerCase();
        const filtered = users.filter(u =>
            (u.first_name + ' ' + u.last_name).toLowerCase().includes(term) ||
            (u.email || '').toLowerCase().includes(term) ||
            (u.student_id || '').toLowerCase().includes(term) ||
            (u.phone || '').toLowerCase().includes(term)
        );
        renderUsers(filtered);
        updateStats(filtered);
    });

    
    function openModal(user = null) {
        userModal.classList.remove('hidden');
        if (user) {
            modalTitle.textContent = "Modifier l'utilisateur";
            fillForm(user);
            userForm.querySelector('[name="password"]').required = false;
            userForm.querySelector('[name="password"]').placeholder = "Laisser vide pour conserver";
        } else {
            modalTitle.textContent = "Ajouter un utilisateur";
            userForm.reset();
            userForm.querySelector('[name="id"]').value = '';
            userForm.querySelector('[name="password"]').required = true;
            userForm.querySelector('[name="password"]').placeholder = "Mot de passe (min. 8 caractères)";
        }
    }

    function closeModal() {
        userModal.classList.add('hidden');
        userForm.reset();
    }

    function fillForm(u) {
        userForm.querySelector('[name="id"]').value = u.id || '';
        userForm.querySelector('[name="student_id"]').value = u.student_id || '';
        userForm.querySelector('[name="first_name"]').value = u.first_name || '';
        userForm.querySelector('[name="last_name"]').value = u.last_name || '';
        userForm.querySelector('[name="email"]').value = u.email || '';
        userForm.querySelector('[name="user_type"]').value = u.user_type || 'student';
        userForm.querySelector('[name="interests"]').value = u.interests || '';
        userForm.querySelector('[name="department"]').value = u.department || '';
        userForm.querySelector('[name="phone"]').value = u.phone || '';
        userForm.querySelector('[name="year"]').value = u.year || '';
    }

    
    cancelBtn.addEventListener('click', closeModal);
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    userModal.addEventListener('click', (e) => {
        if (e.target === userModal) closeModal();
    });

    
    userForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const id = userForm.querySelector('[name="id"]').value;
        const password = userForm.querySelector('[name="password"]').value;

        
        if (!id && (!password || password.length < 8)) {
            alert('Le mot de passe doit contenir au moins 8 caractères.');
            return;
        }

        const formData = new FormData(userForm);
        if (id) formData.append('id', id);
        if (!password) formData.delete('password'); 

        const action = id ? 'update' : 'create';

        try {
            const res = await fetch(`${API}?action=${action}`, {
                method: 'POST',
                body: formData
            });
            const json = await res.json();

            if (json.success) {
                await fetchUsers();
                closeModal();
            } else {
                alert(json.message || 'Erreur lors de la sauvegarde.');
            }
        } catch (err) {
            console.error(err);
            alert('Erreur réseau.');
        }
    });

    
    tbody.addEventListener('click', async (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;

        const action = btn.dataset.action;
        const id = btn.dataset.id;

        if (action === 'edit') {
            try {
                const res = await fetch(`${API}?action=get&id=${id}`);
                const json = await res.json();
                if (json.success && json.data) {
                    openModal(json.data);
                } else {
                    alert('Impossible de récupérer les données.');
                }
            } catch (err) {
                alert('Erreur réseau.');
            }
        }

        if (action === 'delete') {
            if (!confirm('Supprimer définitivement cet utilisateur ?')) return;

            const formData = new FormData();
            formData.append('id', id);

            try {
                const res = await fetch(`${API}?action=delete`, {
                    method: 'POST',
                    body: formData
                });
                const json = await res.json();

                if (json.success) {
                    await fetchUsers();
                } else {
                    alert(json.message || 'Erreur lors de la suppression.');
                }
            } catch (err) {
                alert('Erreur réseau.');
            }
        }
    });

    
    openAddBtn.addEventListener('click', () => openModal());
    fetchUsers(); 
});