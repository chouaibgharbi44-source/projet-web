<?php if (!isset($telechargements)) { header('Location: index.php?area=admin'); exit; } ?>
<!DOCTYPE html>
<html>
<head>
    <title>Téléchargements — Campus Connect</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #1e293b;
            display: flex;
            min-height: 100vh;
        }

        /* === Sidebar gauche (rose vif, fixe) === */
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
            display: flex;
            flex-direction: column;
        }

        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-nav li {
            margin-bottom: 12px;
        }

        .sidebar-nav a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 8px;
            transition: background 0.3s;
            font-weight: 500;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar-nav a i {
            width: 20px;
            text-align: center;
        }

        /* === Contenu principal === */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 40px 30px;
            overflow-y: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2rem;
            color: #ff6faa;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        th {
            text-align: left;
            padding: 14px 16px;
            font-weight: 600;
            color: #475569;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .back-button {
            display: inline-block;
            background: #ff6faa;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: opacity 0.3s;
            margin-top: 20px;
        }

        .back-button:hover {
            opacity: 0.9;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
                padding: 15px 10px;
            }
            .sidebar .logo {
                font-size: 1.1rem;
                justify-content: center;
            }
            .sidebar-nav a {
                padding: 10px;
                justify-content: center;
                font-size: 0.85rem;
            }
            .sidebar-nav a span {
                display: none;
            }
            .main-content {
                margin-left: 80px;
                padding: 20px;
            }
            .header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar gauche -->
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

<!-- Contenu principal -->
<div class="main-content">
    <div class="header">
        <h1><i class="fas fa-download"></i> Téléchargements</h1>
        <p>Historique des téléchargements par les utilisateurs</p>
    </div>

    <div class="card">
        <?php if (empty($telechargements)): ?>
            <p style="text-align:center; color:#94a3b8; padding:40px; font-style:italic;">
                Aucun téléchargement pour le moment.
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Ressource</th>
                        <th>Auteur</th>
                        <th>Matière</th>
                        <th>Utilisateur</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($telechargements as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['titre'] ?? '') ?></td>
                        <td><?= htmlspecialchars($t['auteur'] ?? '') ?></td>
                        <td><?= htmlspecialchars($t['nom_matiere'] ?? '') ?></td>
                        <td>Utilisateur <?= (int)($t['user_id'] ?? 0) ?></td>
                        <td><?= htmlspecialchars($t['created_at'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <a href="index.php?area=admin" class="back-button">
        Retour au Tableau de Bord
    </a>
</div>

</body>
</html>