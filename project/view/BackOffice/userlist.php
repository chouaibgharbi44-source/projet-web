<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - CampusConnect</title>
    <link rel="stylesheet" href="../public/css/style-gestion.css">
</head>
<body>
    <div class="app-container">

         
        <aside class="sidebar">
            <div class="logo">
                <img src="../public/images/logo.png" alt="Logo" class="logo-img">
                <span class="logo-text">Campus Connect Logo</span>
            </div>
            <h1 class="brand-title">CAMPUS CONNECT</h1>
            <p class="brand-subtitle">Your University United</p>
            <a href="../public/logout.php" class="logout-btn">Déconnexion</a>
            <p class="nav-title">SUJETS</p>
            <ul class="subjects-list">
                <li class="subject-item">Mathématiques</li>
                <li class="subject-item">Programmation</li>
                <li class="subject-item">Examens</li>
            </ul>
        </aside>

         
        <main class="main-content">
            <header class="page-header">
                <h2 class="page-title">Gestion des Utilisateurs</h2>
                <p class="page-subtitle">Plateforme Sociale Universitaire </p>
            </header>

             
            <section class="stats-grid">
                <div class="stat-card"><h3 class="stat-number"><?= $totalUsers ?></h3><p>Total Utilisateurs</p></div>
                <div class="stat-card"><h3 class="stat-number"><?= $totalStudents ?></h3><p>Étudiants</p></div>
                <div class="stat-card"><h3 class="stat-number"><?= $totalTeachers ?></h3><p>Professeurs</p></div>
            </section>

             
            <section class="table-section">
    
    
    <section class="table-section">
    <h3 class="section-heading">Liste des Utilisateurs</h3>
    
    <!-- ADD THIS: Filter and Sort Controls -->
    <div style="display: flex; gap: 15px; margin-bottom: 15px; align-items: center;">
        <div>
            <label for="typeFilter" style="margin-right: 8px; font-weight: 500;">Filtrer par type:</label>
            <select id="typeFilter" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                <option value="all">Tous</option>
                <option value="student">Étudiants</option>
                <option value="teacher">Professeurs</option>
                <option value="admin">Administrateurs</option>
            </select>
        </div>
        
        <div>
            <label for="sortSelect" style="margin-right: 8px; font-weight: 500;">Trier par:</label>
            <select id="sortSelect" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
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
    
    <!-- Existing search bar -->
    <div class="search-bar">
        <input type="text" placeholder="Rechercher par nom, email ou student ID..." class="search-input">
        <button type="button" class="search-btn">Rechercher</button>
    </div>
    
    <!-- Rest of your table... -->
</section>

    
    
    <div class="table-wrapper">
        <table class="users-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Nom Complet</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Intérêts</th>
                    <th>Département</th>
                    <th>Téléphone</th>
                    <th>Année</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Will be populated by JavaScript -->
                <tr class="empty-row">
                    <td colspan="10">Chargement...</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
     <script src="js/gestion.js"></script>
</body>
</html>