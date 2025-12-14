<?php if (!isset($favoris)) { header('Location: index.php?area=admin'); exit; } ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoris — Campus Connect</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #ffffffff 0%, #ffffffff 100%);
            color: #000000ff;
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
        }
        
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
            background: rgba(0, 0, 0, 0.2);
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-top: 20px;
        }
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, #ff6faa, #ff5fa2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 12px;
        }
        .header p {
            color: #000000ff;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .stats-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 14px 24px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 1.1rem;
            color: #ff6faa;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .stat-card i {
            font-size: 1.4rem;
        }

        .section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 32px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .section h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 24px;
            color: #f1f5f9;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section h2 i {
            color: #ff6faa;
            font-size: 1.6rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            padding: 16px 18px;
            font-weight: 700;
            color: #000000ff;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px 10px 0 0;
        }
        td {
            padding: 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        tr:last-child td {
            border-bottom: 0;
        }
        tr:hover {
            background: rgba(255, 255, 255, 0.03);
            transition: background 0.2s;
        }
        .empty {
            text-align: center;
            color: #000000ff;
            padding: 40px 20px;
            font-style: italic;
            font-size: 1.1rem;
        }
        .empty i {
            font-size: 3rem;
            margin-bottom: 16px;
            color: #000000ff;
        }

        .fav-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 16px;
            background: linear-gradient(90deg, #ff6faa, #ff5fa2);
            color: white;
            font-weight: 700;
            border-radius: 30px;
            font-size: 0.95rem;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(255, 111, 170, 0.3);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(90deg, #ff6faa, #ff5fa2);
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            width: fit-content;
            box-shadow: 0 6px 16px rgba(255, 111, 170, 0.4);
        }
        .back-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 111, 170, 0.6);
            background: linear-gradient(90deg, #ff5fa2, #ff4f92);
        }
        .back-link i {
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            body { padding: 15px; }
            .sidebar {
                width: 220px;
            }
            .main-content {
                margin-left: 220px;
            }
            .header h1 { font-size: 2rem; }
            .section { padding: 20px; }
            th, td { padding: 14px 10px; font-size: 0.9rem; }
            .stat-card { font-size: 1rem; padding: 12px 18px; }
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
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-heart"></i> Ressources Favorisées</h1>
            <p>Vue d'ensemble des contenus les plus appréciés par la communauté</p>
        </div>

        <!-- Stats -->
        <div class="stats-bar">
            <div class="stat-card">
                <i class="fas fa-heart"></i>
                <?= count($favoris) ?> ressource(s) aimée(s)
            </div>
        </div>

        <div class="section">
            <?php if (empty($favoris)): ?>
                <div class="empty">
                    <i class="fas fa-heart-broken"></i>
                    <p>Aucune ressource n'a été ajoutée en favoris pour le moment.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Matière</th>
                            <th>Auteur</th>
                            <th><i class="fas fa-heart"></i> Favoris</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($favoris as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['titre']) ?></td>
                            <td><?= htmlspecialchars($r['nom_matiere']) ?></td>
                            <td><?= htmlspecialchars($r['auteur']) ?></td>
                            <td><span class="fav-count"><i class="fas fa-heart"></i> <?= (int)$r['nb_favoris'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <a href="index.php?area=admin" class="back-link">
            <i class="fas fa-arrow-left"></i> Retour au Tableau de Bord
        </a>
    </div>
</div>

</body>
</html>