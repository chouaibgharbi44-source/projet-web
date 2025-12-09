<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Gestion Ressources</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        /* MAINTENANCE DU STYLE EXISTANT */
        .topbar.admin {
            background: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
        }
        .admin-button a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            background: #3498db;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .list-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 20px;
        }
        .matiere-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .matiere-table th, .matiere-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .matiere-table th {
            background: #f8fafc;
            font-weight: bold;
        }
        .actions a, .filter-form .btn {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
        }
        .actions a:hover, .filter-form .btn:hover {
            background: #2980b9;
        }
        .filter-form {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .filter-form select {
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .filter-form .ghost {
            background: #e0e0e0;
            color: #333;
        }
    </style>
</head>
<body>

<!-- SIDEBAR ROSE -->
<div class="sidebar">
    <div class="logo">CAMPUS CONNECT</div>
    <ul class="sidebar-nav">
        <li><a href="index.php?area=admin">📊 Tableau de Bord</a></li>
        <li><a href="index.php?entity=matiere&area=admin">📚 Matières</a></li>
        <li><a href="index.php?entity=ressource&area=admin">📄 Ressources</a></li>
        <li><a href="index.php?entity=ressource&action=favoris&area=admin">❤️ Favoris</a></li>
        <li><a href="index.php?entity=ressource&action=telechargements&area=admin">📥 Téléchargements</a></li>
        <li><a href="index.php?area=admin&action=logs">📋 Historique</a></li>
    </ul>
</div>

<!-- CONTENU PRINCIPAL -->
<div class="main-content">
    <div class="topbar admin">
        <h1>Backoffice - Ressources</h1>
        <div class="admin-button">
            <a href="index.php">Frontoffice</a>
            <a href="index.php?area=admin">Accueil Admin</a>
        </div>
    </div>

    <div class="container">
        <div class="list-card full">
            <p class="actions"><a class="btn" href="index.php?entity=ressource&action=add&area=admin">Ajouter une ressource</a></p>
            <?php if (!empty($matieres)) : ?>
                <form method="get" action="index.php" class="filter-form" style="margin-bottom:12px;">
                    <input type="hidden" name="entity" value="ressource" />
                    <input type="hidden" name="area" value="admin" />
                    <label>Filter par matière:
                        <select name="matiere_id" onchange="this.form.submit()">
                            <option value="">Toutes les matières</option>
                            <?php foreach ($matieres as $m): ?>
                                <option value="<?php echo (int)$m['id']; ?>" <?php echo isset($selectedMatiere) && $selectedMatiere && $selectedMatiere['id'] == $m['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['nom_matiere']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <?php if (!empty($selectedMatiere)) : ?>
                        <a href="index.php?entity=ressource&area=admin" class="btn ghost">Réinitialiser</a>
                    <?php endif; ?>
                </form>
            <?php endif; ?>

            <!-- backoffice: no stats here (clean admin list) -->
            <?php if (!empty($selectedMatiere)) : ?>
                <div style="margin-bottom:12px; font-weight:600; color:var(--ink-700);">Ressources pour la matière: <?php echo htmlspecialchars($selectedMatiere['nom_matiere']); ?> (<?php echo count($ressources); ?>)</div>
            <?php endif; ?>
            <table border="1" class="matiere-table">
                <tr>
                    <th>ID</th>
                    <th>Matière</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Auteur</th>
                    <th>URL</th>
                    <th>Date d'ajout</th>
                    <th>Actions</th>
                </tr>
                <?php if (!empty($ressources)) : ?>
                    <?php foreach ($ressources as $r) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($r['id']); ?></td>
                            <td><?php echo htmlspecialchars($r['nom_matiere'] ?? '—'); ?></td>
                            <td><?php echo htmlspecialchars($r['titre']); ?></td>
                            <td><?php echo htmlspecialchars(substr($r['description'], 0, 50) . '...'); ?></td>
                            <td><?php echo htmlspecialchars($r['type_ressource']); ?></td>
                            <td><?php echo htmlspecialchars($r['auteur']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($r['url']); ?>" target="_blank">Lien</a></td>
                            <td><?php echo htmlspecialchars($r['date_ajout']); ?></td>
                            <td>
                                <a href="index.php?entity=ressource&action=edit&id=<?php echo $r['id']; ?>&area=admin">Modifier</a> |
                                <a href="index.php?entity=ressource&action=delete&id=<?php echo $r['id']; ?>&area=admin" onclick="return confirm('Supprimer ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9">Aucune ressource trouvée.</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
<!-- Charts removed from admin view per request -->