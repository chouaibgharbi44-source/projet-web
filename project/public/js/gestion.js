// public/js/gestion.js
// Note: pages using this file are located in /view/BackOffice,
// so the controller path is '../../controllers/UserController.php'.
// If you move files, adjust API accordingly.
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

  let users = [];

  async function fetchUsers() {
    try {
      const res = await fetch(`${API}?action=getAll`);
      const json = await res.json();
      if (json.success) {
        users = json.data;
        renderUsers(users);
        updateStats(users);
      } else {
        alert('Impossible de charger les utilisateurs');
      }
    } catch (err) {
      console.error(err);
      alert('Erreur réseau');
    }
  }

  function updateStats(list) {
    stats[0].textContent = list.length;
    const students = list.filter(u => (u.user_type || '').toLowerCase().includes('étudiant')).length;
    const profs = list.filter(u => (u.user_type || '').toLowerCase().includes('prof')).length;
    stats[1].textContent = students;
    stats[2].textContent = profs;
  }

  function renderUsers(list) {
    tbody.innerHTML = '';
    if (!list.length) {
      tbody.innerHTML = `<tr class="empty-row"><td colspan="9">Aucun utilisateur pour le moment. Créez le premier profil !</td></tr>`;
      return;
    }
    list.forEach(u => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${escapeHtml(u.student_id||'')}</td>
        <td>${escapeHtml((u.first_name||'') + ' ' + (u.last_name||''))}</td>
        <td>${escapeHtml(u.email||'')}</td>
        <td>${escapeHtml(u.user_type||'')}</td>
        <td>${escapeHtml(u.created_at ? new Date(u.created_at).getFullYear() : '')}</td>
        <td>${escapeHtml(u.interests||'')}</td>
        <td>${escapeHtml(u.department||'')}</td>
        <td>${escapeHtml(u.phone||'')}</td>
        <td>
          <button class="action-btn" data-action="edit" data-id="${u.id}">Modifier</button>
          <button class="action-btn" data-action="delete" data-id="${u.id}">Supprimer</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  }

  function escapeHtml(s){ return String(s || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c]); }

  searchBtn.addEventListener('click', () => {
    const term = (searchInput.value || '').trim().toLowerCase();
    const filtered = users.filter(u =>
      (u.first_name||'').toLowerCase().includes(term) ||
      (u.last_name||'').toLowerCase().includes(term) ||
      (u.email||'').toLowerCase().includes(term) ||
      (u.student_id||'').toLowerCase().includes(term)
    );
    renderUsers(filtered);
  });

  openAddBtn.addEventListener('click', () => openModal());

  function openModal(user = null) {
    userModal.classList.remove('hidden');
    if (user) {
      modalTitle.textContent = 'Modifier l\\'utilisateur';
      fillForm(user);
    } else {
      modalTitle.textContent = 'Ajouter un utilisateur';
      userForm.reset();
      userForm.querySelector('[name=id]').value = '';
      userForm.querySelector('[name=password]').required = true;
    }
  }
  cancelBtn.addEventListener('click', () => closeModal());
  function closeModal(){ userModal.classList.add('hidden'); userForm.reset(); }

  function fillForm(u) {
    userForm.querySelector('[name=id]').value = u.id;
    userForm.querySelector('[name=student_id]').value = u.student_id || '';
    userForm.querySelector('[name=first_name]').value = u.first_name || '';
    userForm.querySelector('[name=last_name]').value = u.last_name || '';
    userForm.querySelector('[name=email]').value = u.email || '';
    userForm.querySelector('[name=password]').required = false;
    userForm.querySelector('[name=user_type]').value = u.user_type || '';
    userForm.querySelector('[name=interests]').value = u.interests || '';
    userForm.querySelector('[name=department]').value = u.department || '';
    userForm.querySelector('[name=phone]').value = u.phone || '';
  }

  userForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = userForm.querySelector('[name=id]').value;
    const formData = new FormData(userForm);
    const action = id ? 'update' : 'create';
    if (id) formData.append('id', id);

    if (!formData.get('student_id') || !formData.get('first_name') || !formData.get('last_name') || !formData.get('email')) {
      alert('Remplissez les champs requis.');
      return;
    }
    if (!id && (!formData.get('password') || formData.get('password').length < 8)) {
      alert('Le mot de passe doit contenir minimum 8 caractères.');
      return;
    }

    try {
      const res = await fetch(`${API}?action=${action}`, { method: 'POST', body: formData });
      const json = await res.json();
      if (json.success) {
        await fetchUsers();
        closeModal();
      } else {
        alert(json.message || 'Erreur serveur');
      }
    } catch (err) {
      alert('Erreur réseau: ' + err.message);
    }
  });

  tbody.addEventListener('click', async (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    const action = btn.dataset.action;
    const id = btn.dataset.id;
    if (action === 'edit') {
      const res = await fetch(`${API}?action=get&id=${id}`);
      const json = await res.json();
      if (json.success) openModal(json.data);
      else alert('Impossible de récupérer l\\'utilisateur');
    }
    if (action === 'delete') {
      if (!confirm('Supprimer cet utilisateur ?')) return;
      const fd = new FormData();
      fd.append('id', id);
      const res = await fetch(`${API}?action=delete`, { method: 'POST', body: fd });
      const json = await res.json();
      if (json.success) {
        await fetchUsers();
      } else {
        alert(json.message || 'Erreur suppression');
      }
    }
  });

  fetchUsers();
});
