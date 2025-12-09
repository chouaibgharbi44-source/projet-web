<?php
// Protection : s'assurer que les données sont disponibles
if (!isset($logs_matieres, $logs_ressources)) {
    header('Location: index.php?area=admin');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Actions</title>
    <style>
        /* SIDEBAR ROSE */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #ff6faa 0%, #ff5fa2 100%);
            color: white;
            padding: 20px;
            z-index: 1000;
        }
        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-nav li {
            margin-bottom: 15px;
        }
        .sidebar-nav a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.3);
            padding-left: 20px;
            border-left: 3px solid white;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
            color: #333;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            text-align: center;
        }
        .filter-bar {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }
        .filter-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        #searchLogs {
            flex: 1;
            min-width: 250px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        #searchLogs:focus {
            border-color: #ff6faa;
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 111, 170, 0.2);
        }
        .filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .btn {
            display: inline-block;
            padding: 6px 14px;
            text-decoration: none;
            border-radius: 4px;
            background: #e0e0e0;
            color: #333;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #d0d0d0;
        }
        .btn.active {
            background: #ff6faa;
            color: white;
        }
        h3 {
            margin: 25px 0 15px;
            color: #ff6faa;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8fafc;
            font-weight: bold;
            color: #2c3e50;
        }
        /* Style pour les actions */
        .action {
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }
        .action.add {
            color: #27ae60; /* vert */
            background-color: #e8f5e9;
        }
        .action.edit {
            color: #d35400; /* orange */
            background-color: #fef9e7;
        }
        .action.delete {
            color: #e74c3c; /* rouge */
            background-color: #fadbd8;
        }

        /* Style pour les types d'utilisateurs */
        .user-type {
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            min-width: 90px;
            text-align: center;
        }
        .user-type.utilisateur {
            color: #2980b9; /* bleu */
            background-color: #e3f2fd;
        }
        .user-type.admin {
            color: #c0392b; /* rouge foncé */
            background-color: #fadbd8;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #ff6faa;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .back-link:hover {
            background: #ff5fa2;
        }
        .empty {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }
        
        .topbar {
            background: #1e293b;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 12px;
            margin-bottom: 24px;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            .sidebar .logo {
                justify-content: center;
                padding: 20px;
            }
            .sidebar .logo span {
                display: none;
            }
            .sidebar li {
                padding: 12px 10px;
                text-align: center;
            }
            .sidebar a span {
                display: none;
            }
            .sidebar a::before {
                content: attr(data-icon);
                font-size: 1.2rem;
                display: block;
            }
            .main-content {
                margin-left: 70px;
            }
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            .filter-controls {
                flex-direction: column;
                align-items: stretch;
            }
            #searchLogs {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- SIDEBAR ROSE -->
<div class="sidebar">
    <div class="logo">
        <i class="fas fa-graduation-cap"></i>
        <span>Campus Connect</span>
    </div>
    <ul class="sidebar-nav">
        <li><a href="index.php?area=admin" class="<?= !isset($_GET['action']) ? 'active' : '' ?>" data-icon="📊">📊 Tableau de Bord</a></li>
        <li><a href="index.php?entity=matiere&area=admin&action=list" data-icon="📚">📚 Matières</a></li>
        <li><a href="index.php?entity=ressource&area=admin&action=list" data-icon="📄">📄 Ressources</a></li>
        <li><a href="index.php?entity=ressource&area=admin&action=favoris" data-icon="❤️">❤️ Favoris</a></li>
        <li><a href="index.php?entity=ressource&area=admin&action=telechargements" data-icon="📥">📥 Téléchargements</a></li>
        <li><a href="index.php?area=admin&action=logs" class="active" data-icon="📋">📋 Historique</a></li>
    </ul>
</div>

<!-- CONTENU PRINCIPAL -->
<div class="main-content">
    <div class="topbar">
        <h1><i class="fas fa-history"></i> Historique des Actions</h1>
        <a href="index.php" style="background: #ff6faa; color: white; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-home"></i> Frontoffice
        </a>
    </div>

    <div class="container">
        <h2>📊 Historique des Actions</h2>

        <!-- Barre de filtre COMPLÈTE -->
        <div class="filter-bar">
            <div class="filter-controls">
                <input type="text" id="searchLogs" placeholder="Rechercher par titre..." 
                       value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                       style="flex:1; min-width:250px;">
                <div class="filter-buttons">
                    <a href="index.php?area=admin&action=logs<?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
                       class="btn <?= (!isset($_GET['filter']) || $_GET['filter'] === 'all') ? 'active' : '' ?>">Tous</a>
                    <a href="index.php?area=admin&action=logs&filter=add<?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
                       class="btn <?= ($_GET['filter'] ?? '') === 'add' ? 'active' : '' ?>">ADD</a>
                    <a href="index.php?area=admin&action=logs&filter=edit<?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
                       class="btn <?= ($_GET['filter'] ?? '') === 'edit' ? 'active' : '' ?>">EDIT</a>
                    <a href="index.php?area=admin&action=logs&filter=delete<?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>" 
                       class="btn <?= ($_GET['filter'] ?? '') === 'delete' ? 'active' : '' ?>">DELETE</a>
                </div>
            </div>
        </div>

        <!-- Matières -->
        <h3>📚 Actions sur les Matières</h3>
        <?php if (empty($logs_matieres)): ?>
            <p class="empty">Aucune action enregistrée.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Action</th>
                        <th>Fait par</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs_matieres as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['entity_title']) ?></td>
                        <td><span class="action <?= strtolower($log['action']) ?>"><?= ucfirst($log['action']) ?></span></td>
                        <td><span class="user-type <?= strtolower($log['user_type']) ?>"><?= htmlspecialchars($log['user_type']) ?></span></td>
                        <td><?= htmlspecialchars($log['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Ressources -->
        <h3>📄 Actions sur les Ressources</h3>
        <?php if (empty($logs_ressources)): ?>
            <p class="empty">Aucune action enregistrée.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Action</th>
                        <th>Fait par</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs_ressources as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['entity_title']) ?></td>
                        <td><span class="action <?= strtolower($log['action']) ?>"><?= ucfirst($log['action']) ?></span></td>
                        <td><span class="user-type <?= strtolower($log['user_type']) ?>"><?= htmlspecialchars($log['user_type']) ?></span></td>
                        <td><?= htmlspecialchars($log['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="index.php?area=admin" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour au Tableau de Bord
        </a>
    </div>
</div>

<script>

document.getElementById('searchLogs').addEventListener('input', function() {
    const search = this.value.trim();
    const currentUrl = new URL(window.location.href);
    
    
    const basePath = currentUrl.pathname.substring(0, currentUrl.pathname.lastIndexOf('/')) + '/';
    
   
    const filter = currentUrl.searchParams.get('filter') || 'all';
    
    const newUrl = new URL(basePath + 'index.php', window.location.origin);
    
    newUrl.searchParams.set('area', 'admin');
    newUrl.searchParams.set('action', 'logs');
    
    if (filter && filter !== 'all') {
        newUrl.searchParams.set('filter', filter);
    }
    
    if (search) {
        newUrl.searchParams.set('search', search);
    }
    
    // Redirection après un délai
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        window.location.href = newUrl.toString();
    }, 500);
});
</script>

</body>
</html>