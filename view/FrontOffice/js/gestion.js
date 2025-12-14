const API = '/project/controllers/api_users.php';

document.addEventListener('DOMContentLoaded', () => {
    console.log('=== Script loaded ===');
    
    const tbody = document.querySelector('.users-table tbody');
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');
    const typeFilter = document.getElementById('typeFilter');
    const sortSelect = document.getElementById('sortSelect');
    
    console.log('tbody:', tbody);
    console.log('searchBtn:', searchBtn);
    console.log('searchInput:', searchInput);
    console.log('typeFilter:', typeFilter);
    console.log('sortSelect:', sortSelect);

    let users = [];
    let filteredUsers = [];

    // Fetch users from API
    async function fetchUsers() {
        console.log('Fetching users from:', API);
        try {
            const res = await fetch(`${API}?action=getAll`);
            const json = await res.json();
            console.log('API Response:', json);

            if (json.success && Array.isArray(json.data)) {
                users = json.data;
                console.log('Users loaded:', users.length);
                applyFiltersAndSort(); // Use filter function instead of direct render
            } else {
                console.error('API returned no data');
                alert('Impossible de charger les utilisateurs.');
            }
        } catch (err) {
            console.error('Erreur fetchUsers:', err);
            alert('Erreur réseau lors du chargement des utilisateurs.');
        }
    }

    // Apply filters and sorting
    function applyFiltersAndSort() {
        let result = [...users];

        // Apply type filter
        if (typeFilter && typeFilter.value !== 'all') {
            result = result.filter(u => (u.type || '') === typeFilter.value);
        }

        // Apply search filter
        const searchTerm = searchInput.value.trim().toLowerCase();
        if (searchTerm) {
            result = result.filter(u => {
                const fullName = (u.full_name || '').toLowerCase();
                const email = (u.email || '').toLowerCase();
                const studentId = (u.student_id || '').toLowerCase();
                const phone = (u.phone || '').toLowerCase();

                return fullName.includes(searchTerm) || 
                       email.includes(searchTerm) || 
                       studentId.includes(searchTerm) || 
                       phone.includes(searchTerm);
            });
        }

        // Apply sorting
        if (sortSelect && sortSelect.value !== 'default') {
            result.sort((a, b) => {
                switch(sortSelect.value) {
                    case 'name-asc':
                        return (a.full_name || '').localeCompare(b.full_name || '');
                    case 'name-desc':
                        return (b.full_name || '').localeCompare(a.full_name || '');
                    case 'date-newest':
                        return new Date(b.created_at) - new Date(a.created_at);
                    case 'date-oldest':
                        return new Date(a.created_at) - new Date(b.created_at);
                    case 'id-asc':
                        return (a.student_id || '').localeCompare(b.student_id || '');
                    case 'id-desc':
                        return (b.student_id || '').localeCompare(a.student_id || '');
                    default:
                        return 0;
                }
            });
        }

        filteredUsers = result;
        renderUsers(result);
    }
    function renderUsers(list) {
        console.log('Rendering', list.length, 'users');
        tbody.innerHTML = '';
        
        if (!list || list.length === 0) {
            tbody.innerHTML = `<tr class="empty-row"><td colspan="10">Aucun utilisateur trouvé.</td></tr>`;
            return;
        }

        list.forEach(u => {
            const tr = document.createElement('tr');
            
            // Split full_name into first and last
            const nameParts = (u.full_name || '').split(' ');
            const firstName = nameParts[0] || '';
            const lastName = nameParts.slice(1).join(' ') || '';
            
            tr.innerHTML = `
                <td>${escapeHtml(u.student_id || '-')}</td>
                <td>
                    <a href="show_user.php?id=${u.id}">
                        ${escapeHtml(u.full_name || firstName + ' ' + lastName)}
                    </a>
                </td>
                <td>${escapeHtml(u.email || '')}</td>
                <td>
                    <span class="badge badge-${u.type || 'unknown'}">
                        ${formatUserType(u.type)}
                    </span>
                </td>
                <td>${u.created_at ? new Date(u.created_at).toLocaleDateString('fr-FR') : '-'}</td>
                <td>${escapeHtml(u.interests || '-')}</td>
                <td>${escapeHtml(u.department || '-')}</td>
                <td>${escapeHtml(u.phone || '-')}</td>
                <td>${escapeHtml(u.year || '-')}</td>
                <td class="actions">
                    <button class="action-btn edit-btn" data-action="edit" data-id="${u.id}">
                        Modifier
                    </button>
                    <button class="action-btn delete-btn" data-action="delete" data-id="${u.id}">
                        Supprimer
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Format user type
    function formatUserType(type) {
        switch (type) {  // FIXED: removed extra parenthesis
            case 'student': return 'Étudiant';
            case 'teacher': return 'Professeur';
            case 'admin': return 'Administrateur';
            default: return 'Inconnu';
        }
    }

    // SEARCH FUNCTIONALITY
    if (searchBtn && searchInput) {
        searchBtn.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('=== SEARCH CLICKED ===');
            
            const term = searchInput.value.trim().toLowerCase();
            console.log('Search term:', term);

            if (!term) {
                console.log('Empty search, applying filters');
                applyFiltersAndSort();
                return;
            }

            // Check for exact match first (for redirect)
            const exactMatches = users.filter(u => 
                (u.student_id || '').toLowerCase() === term || 
                (u.email || '').toLowerCase() === term
            );

            if (exactMatches.length === 1) {
                console.log('Exact match! Redirecting to user:', exactMatches[0].id);
                window.location.href = `show_user.php?id=${exactMatches[0].id}`;
                return;
            }

            // Otherwise apply filters
            applyFiltersAndSort();
        });

        // Enter key support
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBtn.click();
            }
        });
    } else {
        console.error('Search elements not found!');
    }

    // Type filter change event
    if (typeFilter) {
        typeFilter.addEventListener('change', () => {
            console.log('Type filter changed:', typeFilter.value);
            applyFiltersAndSort();
        });
    }

    // Sort change event
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            console.log('Sort changed:', sortSelect.value);
            applyFiltersAndSort();
        });
    }

    // Handle edit and delete buttons
    tbody.addEventListener('click', async (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;

        const action = btn.dataset.action;
        const id = btn.dataset.id;

        console.log('Button clicked:', action, 'ID:', id);

        if (action === 'edit') {
            // Redirect to edit page
            window.location.href = `edit_user.php?id=${id}`;
        }

        if (action === 'delete') {
            // OPTION 1: Use delete.php (uncomment to use)
            // if (confirm('Supprimer définitivement cet utilisateur ?')) {
            //     window.location.href = `delete.php?id=${id}`;
            // }

            // OPTION 2: Use API (current - no page reload, faster)
            if (!confirm('Supprimer définitivement cet utilisateur ?')) return;

            console.log('Deleting user:', id);

            const formData = new FormData();
            formData.append('id', id);

            try {
                const res = await fetch(`${API}?action=delete`, {
                    method: 'POST',
                    body: formData
                });
                const json = await res.json();
                console.log('Delete response:', json);

                if (json.success) {
    alert('Utilisateur supprimé avec succès');
                    await fetchUsers(); // Reload the table
                } else {
                    alert(json.message || 'Erreur lors de la suppression.');
                }
            } catch (err) {
                console.error('Error deleting user:', err);
                alert('Erreur réseau lors de la suppression.');
            }
        }
    });

    // Initial load
    console.log('Calling fetchUsers...');
    fetchUsers();
});