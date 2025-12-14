<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Gestion Matières</title>
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
        .actions a {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
        }
        .actions a:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>

<!-- SIDEBAR ROSE -->
<div class="sidebar">
    <div class="logo">CAMPUS CONNECT</div>
    <ul class="sidebar-nav">
        <li><a href="../view/BackOffice/index1.php"> Acceuil</a></li>
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
        <h1>Backoffice - Matières</h1>
        <div class="admin-button">
            <a href="index.php">Frontoffice</a>
            <a href="index.php?entity=ressource&area=admin">Gérer Ressources</a>
        </div>
    </div>

    <div class="container">
        <div class="list-card full">
            <p class="actions"><a class="btn" href="index.php?action=add&area=admin">Ajouter une matière</a></p>
            <table border="1" class="matiere-table">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Date d'ajout</th>
                    <th>Niveau</th>
                    <th>Actions</th>
                </tr>
                <?php if (!empty($matieres)) : ?>
                    <?php foreach ($matieres as $m) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($m['id']); ?></td>
                            <td><?php echo htmlspecialchars($m['nom_matiere']); ?></td>
                            <td><?php echo htmlspecialchars($m['titre']); ?></td>
                            <td><?php echo htmlspecialchars($m['description']); ?></td>
                            <td><?php echo htmlspecialchars($m['date_ajout']); ?></td>
                            <td><?php echo htmlspecialchars($m['niveau_difficulte']); ?></td>
                            <td>
                                <a href="index.php?entity=ressource&area=admin&matiere_id=<?php echo (int)$m['id']; ?>">Voir les ressources</a> |
                                <a href="index.php?action=edit&id=<?php echo $m['id']; ?>&area=admin">Modifier</a> |
                                <a href="index.php?entity=matiere&action=delete&id=<?= (int)$m['id'] ?>&area=admin" 
   onclick="return confirm('Supprimer définitivement ?');">
    Supprimer
</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">Aucune matière trouvée.</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>