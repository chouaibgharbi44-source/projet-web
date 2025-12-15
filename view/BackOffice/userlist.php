<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - CampusConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style-gestion.css">
    <style>
        /* Additional inline styles for enhancement */
        .filter-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
            opacity: 0;
            animation: fadeInUp 0.8s ease-out 0.7s forwards;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--ink-700);
            font-size: 0.9rem;
        }

        .filter-group select {
            padding: 0.7rem 2.5rem 0.7rem 1rem;
            border: 2px solid var(--rose-200);
            border-radius: 12px;
            background: var(--white);
            color: var(--ink-700);
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ff4d8d' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
        }

        .filter-group select:hover {
            border-color: var(--rose-400);
            background-color: var(--rose-50);
        }

        .filter-group select:focus {
            outline: none;
            border-color: var(--rose-600);
            box-shadow: 0 0 0 4px rgba(255, 77, 141, 0.15);
            transform: scale(1.02);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .action-btn {
            padding: 0.5rem 0.8rem;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-family: 'Poppins', sans-serif;
        }

        .btn-edit {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .btn-edit:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        }

        .btn-delete {
            background: linear-gradient(135deg, #f44336, #da190b);
            color: white;
            box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
        }

        .btn-delete:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(244, 67, 54, 0.4);
        }

        .btn-view {
            background: linear-gradient(135deg, #2196F3, #0b7dda);
            color: white;
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        }

        .btn-view:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(33, 150, 243, 0.4);
        }

        .user-type-badge {
            display: inline-block;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-student {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .badge-teacher {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }

        .badge-admin {
            background: linear-gradient(135deg, #ffd89b, #19547b);
            color: white;
        }

        .sidebar-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .sidebar-links li a {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            color: var(--ink-500);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-links li a:hover {
            background: var(--rose-50);
            color: var(--rose-600);
            transform: translateX(8px);
        }

        .sidebar-links li a i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid var(--rose-200);
            border-top-color: var(--rose-600);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .header-btn {
            padding: 0.7rem 1.5rem;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .btn-add-user {
            background: linear-gradient(135deg, var(--rose-700), var(--rose-500));
            color: white;
            box-shadow: var(--shadow-strong);
        }

        .btn-add-user:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 20px 40px rgba(255, 77, 141, 0.35);
        }

        .btn-export {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-export:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .table-header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .results-count {
            color: var(--ink-500);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group select {
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
            }

            .table-header-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="https://placehold.co/120x120/ff6fb1/ffffff?text=CC" alt="Logo" class="logo-img">
            </div>
            <h1 class="brand-title">CAMPUS CONNECT</h1>
            <p class="brand-subtitle">Votre université, unie</p>
            
            <p class="nav-title">NAVIGATION</p>
            <ul class="sidebar-links">
                <li><a href="index1.php"><i class="fas fa-home"></i> Accueil</a></li>
                <li><a href="#" class="active"><i class="fas fa-users"></i> Gestion Utilisateurs</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            </ul>

            <p class="nav-title" style="margin-top: 2rem;">MATIÈRES</p>
            <ul class="subjects-list">
                <li class="subject-item">Mathématiques</li>
                <li class="subject-item">Programmation</li>
                <li class="subject-item">Examens</li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="page-header">
                <h2 class="page-title">Gestion des Utilisateurs</h2>
                <p class="page-subtitle">Plateforme Sociale Universitaire - Campus Connect</p>
                
                
            </header>

            <!-- Stats Grid -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">
                        <i class="fas fa-users" style="font-size: 1.5rem; opacity: 0.3; margin-right: 0.3rem;"></i>
                        <?= $totalUsers ?? 150 ?>
                    </div>
                    <p class="stat-label">Total Utilisateurs</p>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        <i class="fas fa-user-graduate" style="font-size: 1.5rem; opacity: 0.3; margin-right: 0.3rem;"></i>
                        <?= $totalStudents ?? 120 ?>
                    </div>
                    <p class="stat-label">Étudiants</p>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        <i class="fas fa-chalkboard-teacher" style="font-size: 1.5rem; opacity: 0.3; margin-right: 0.3rem;"></i>
                        <?= $totalTeachers ?? 25 ?>
                    </div>
                    <p class="stat-label">Professeurs</p>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        <i class="fas fa-user-shield" style="font-size: 1.5rem; opacity: 0.3; margin-right: 0.3rem;"></i>
                        <?= $totalAdmins ?? 5 ?>
                    </div>
                    <p class="stat-label">Administrateurs</p>
                </div>
            </section>

            <!-- Table Section -->
            <section class="table-section">
                <div class="table-header-actions">
                    <h3 class="section-heading">Liste des Utilisateurs</h3>
                    <span class="results-count">
                        <i class="fas fa-list"></i>
                        Affichage de <strong id="resultCount">0</strong> utilisateurs
                    </span>
                </div>
                
                <!-- Filter and Sort Controls -->
                <div class="filter-controls">
                    <div class="filter-group">
                        <label for="typeFilter">
                            <i class="fas fa-filter"></i>
                            Filtrer par type:
                        </label>
                        <select id="typeFilter">
                            <option value="all">Tous les types</option>
                            <option value="student">Étudiants</option>
                            <option value="teacher">Professeurs</option>
                            <option value="admin">Administrateurs</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="sortSelect">
                            <i class="fas fa-sort"></i>
                            Trier par:
                        </label>
                        <select id="sortSelect">
                            <option value="default">Par défaut</option>
                            <option value="name-asc">Nom (A-Z)</option>
                            <option value="name-desc">Nom (Z-A)</option>
                            <option value="date-newest">Plus récent</option>
                            <option value="date-oldest">Plus ancien</option>
                            <option value="id-asc">Student ID (↑)</option>
                            <option value="id-desc">Student ID (↓)</option>
                        </select>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="🔍 Rechercher par nom, email ou student ID..." class="search-input">
                    <button type="button" class="search-btn">
                        <i class="fas fa-search"></i>
                        Rechercher
                    </button>
                </div>
                
                <!-- Table -->
                <div class="table-wrapper">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-id-card"></i> Student ID</th>
                                <th><i class="fas fa-user"></i> Nom Complet</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-tag"></i> Type</th>
                                <th><i class="fas fa-calendar"></i> Date</th>
                                <th><i class="fas fa-heart"></i> Intérêts</th>
                                <th><i class="fas fa-building"></i> Département</th>
                                <th><i class="fas fa-phone"></i> Téléphone</th>
                                <th><i class="fas fa-graduation-cap"></i> Année</th>
                                <th><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <tr class="empty-row">
                                <td colspan="10" style="text-align: center; padding: 3rem;">
                                    <div class="loading-spinner" style="margin: 0 auto 1rem;"></div>
                                    <p class="empty-message">Chargement des données...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Sample data for demonstration (replace with actual PHP data)
        const sampleUsers = [
            {
                studentId: 'STU001',
                name: 'Ahmed Ben Salem',
                email: 'ahmed.salem@campus.tn',
                type: 'student',
                date: '2024-01-15',
                interests: 'Web Dev, AI',
                department: 'Informatique',
                phone: '+216 98 765 432',
                year: '3ème année'
            },
            {
                studentId: 'TCH001',
                name: 'Dr. Sarah Mansouri',
                email: 'sarah.mansouri@campus.tn',
                type: 'teacher',
                date: '2023-09-01',
                interests: 'Machine Learning',
                department: 'Informatique',
                phone: '+216 71 234 567',
                year: 'N/A'
            },
            {
                studentId: 'ADM001',
                name: 'Mohamed Trabelsi',
                email: 'mohamed.trabelsi@campus.tn',
                type: 'admin',
                date: '2023-08-01',
                interests: 'Administration',
                department: 'Administration',
                phone: '+216 71 111 222',
                year: 'N/A'
            }
        ];

        function renderUsers(users) {
            const tbody = document.getElementById('usersTableBody');
            const resultCount = document.getElementById('resultCount');
            
            if (users.length === 0) {
                tbody.innerHTML = `
                    <tr class="empty-row">
                        <td colspan="10" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: var(--ink-300); margin-bottom: 1rem;"></i>
                            <p class="empty-message">Aucun utilisateur trouvé</p>
                        </td>
                    </tr>
                `;
                resultCount.textContent = '0';
                return;
            }

            tbody.innerHTML = users.map(user => `
                <tr>
                    <td><strong>${user.studentId}</strong></td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>
                        <span class="user-type-badge badge-${user.type}">
                            ${user.type === 'student' ? 'Étudiant' : user.type === 'teacher' ? 'Professeur' : 'Admin'}
                        </span>
                    </td>
                    <td>${user.date}</td>
                    <td>${user.interests}</td>
                    <td>${user.department}</td>
                    <td>${user.phone}</td>
                    <td>${user.year}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn btn-view" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn btn-edit" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn btn-delete" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
            
            resultCount.textContent = users.length;
        }

        // Initialize with sample data
        setTimeout(() => renderUsers(sampleUsers), 1000);

        // Search functionality
        document.querySelector('.search-btn').addEventListener('click', function() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const filtered = sampleUsers.filter(user => 
                user.name.toLowerCase().includes(searchTerm) ||
                user.email.toLowerCase().includes(searchTerm) ||
                user.studentId.toLowerCase().includes(searchTerm)
            );
            renderUsers(filtered);
        });

        // Filter functionality
        document.getElementById('typeFilter').addEventListener('change', function() {
            const filterValue = this.value;
            if (filterValue === 'all') {
                renderUsers(sampleUsers);
            } else {
                const filtered = sampleUsers.filter(user => user.type === filterValue);
                renderUsers(filtered);
            }
        });

        // Sort functionality
        document.getElementById('sortSelect').addEventListener('change', function() {
            const sortValue = this.value;
            let sorted = [...sampleUsers];
            
            switch(sortValue) {
                case 'name-asc':
                    sorted.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'name-desc':
                    sorted.sort((a, b) => b.name.localeCompare(a.name));
                    break;
                case 'date-newest':
                    sorted.sort((a, b) => new Date(b.date) - new Date(a.date));
                    break;
                case 'date-oldest':
                    sorted.sort((a, b) => new Date(a.date) - new Date(b.date));
                    break;
                case 'id-asc':
                    sorted.sort((a, b) => a.studentId.localeCompare(b.studentId));
                    break;
                case 'id-desc':
                    sorted.sort((a, b) => b.studentId.localeCompare(a.studentId));
                    break;
            }
            
            renderUsers(sorted);
        });
    </script>
    <script src="js/gestion.js"></script>
</body>
</html>