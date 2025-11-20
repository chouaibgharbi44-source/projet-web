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
                <h3 class="section-heading">Liste des Utilisateurs</h3>
                <div class="search-bar">
                    <input type="text" placeholder="Rechercher par nom, email ou student ID..." class="search-input">
                    <button class="search-btn">Rechercher</button>
                </div>
                <div class="table-wrapper">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Student ID</th><th>Nom Complet</th><th>Email</th><th>Type</th>
                                <th>Année</th><th>Intérêts</th><th>Département</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr class="empty-row"><td colspan="8"><p class="empty-message">Aucun utilisateur pour le moment. Créez le premier profil !</p></td></tr>
                            <?php else: foreach ($users as $u): ?>
                                <tr>
                                    <td><?= htmlspecialchars($u['student_id'] ?? $u['id'] ?? '') ?></td>
                                    <td><?= htmlspecialchars(($u['first_name']??'').' '.($u['last_name']??'')) ?></td>
                                    <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                                    <td><?= ($u['user_type']??$u['type']??'') === 'teacher' ? 'Professeur' : 'Étudiant' ?></td>
                                    <td><?= htmlspecialchars($u['year'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($u['interests'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($u['department'] ?? '-') ?></td>
                                    <td class="actions">
                                        <a href="../public/edit_user.php?id=<?= $u['id'] ?>">Éditer</a>|
                                        <a href="../public/delete.php?id=<?= $u['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
    <script src="../public/js/gestion.js"></script>
</body>
</html>