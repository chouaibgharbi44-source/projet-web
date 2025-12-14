<?php if (!isset($mesTelechargements)) { header('Location: index.php?entity=ressource'); exit; } ?>
<!DOCTYPE html>
<html>
<head>
    <title>Mes Téléchargements - Campus Connect</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <link rel="stylesheet" type="text/css" href="View/assets/frontoffice.css?v=3" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff0f5;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 1.1rem;
        }

        .section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 30px;
            margin-bottom: 40px;
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title i {
            color: #e74c3c;
            font-size: 1.6rem;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 20px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .card-type {
            display: inline-block;
            background: #ff6faa;
            color: white;
            font-size: 0.85rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 10px 0;
            word-break: break-word;
        }
        .card-desc {
            color: #666;
            margin: 10px 0;
            font-size: 0.9rem;
            word-break: break-word;
        }
        .card-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 0.85rem;
            color: #999;
        }
        .card-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .card-meta i {
            font-size: 0.9rem;
        }
        .card-actions {
            margin-top: 15px;
        }
        .card-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ff6faa;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: background 0.2s;
        }
        .card-btn:hover {
            background: #ff5fa2;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
            font-style: italic;
            background: #f9f9f9;
            border-radius: 10px;
            margin-top: 15px;
        }
        .empty-state i {
            font-size: 2.5rem;
            margin-bottom: 12px;
            color: #ddd;
        }

        @media (max-width: 768px) {
            body { padding: 15px; }
            .section-title { font-size: 1.3rem; }
            .card-title { font-size: 1.1rem; }
            .card-meta { font-size: 0.8rem; }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="header-inner">
        <div class="logo">CAMPUS CONNECT</div>
        <nav class="navbar">
            <a href="index.php" class="nav-link">Accueil</a>
            <a href="index.php?entity=ressource" class="nav-link">Ressources</a>
        </nav>
        <div class="admin-button">
        </div>
    </div>
</header>

<div class="container">

    <!-- SECTION MES TÉLÉCHARGEMENTS -->
    <div class="section">
        <h2 class="section-title"><i class="fas fa-download"></i> 📥 Mes Téléchargements</h2>
        <?php if (empty($mesTelechargements)): ?>
            <div class="empty-state">
                <i class="fas fa-cloud-download-alt"></i>
                <p>Vous n'avez téléchargé aucune ressource.</p>
                <a href="index.php?entity=ressource" class="card-btn" style="margin-top: 10px;">
                    <i class="fas fa-search"></i> Parcourir les ressources
                </a>
            </div>
        <?php else: ?>
            <p style="margin-bottom: 20px; text-align: center; font-weight: 600; color: #666;">
                <strong><?= count($mesTelechargements) ?> ressource(s) téléchargée(s)</strong>
            </p>
            <div class="content-grid">
                <?php foreach ($mesTelechargements as $res): ?>
                    <div class="card">
                        <div class="card-type"><?= htmlspecialchars($res['type_ressource'] ?? 'Document') ?></div>
                        <h3 class="card-title"><?= htmlspecialchars($res['titre']) ?></h3>
                        <p class="card-desc"><?= htmlspecialchars($res['description']) ?></p>
                        <div class="card-meta">
                            <span><i class="fas fa-user"></i> <?= htmlspecialchars($res['auteur']) ?></span>
                            <span><i class="fas fa-download"></i> <?= $res['nb_telechargements'] ?> téléchargements</span>
                        </div>
                        <div class="card-actions">
                            <a href="<?= htmlspecialchars($res['url']) ?>" target="_blank" class="card-btn">
                                <i class="fas fa-file"></i> Ouvrir
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col">
            <h4>Campus Connect</h4>
            <p>Votre université, unie</p>
        </div>
        <div class="footer-col">
            <h4>Notre Contact</h4>
            <p>Email: compus@gmail.com</p>
            <p>Facebook: Compus Connect</p>
            <p>LinkedIn: Compus Connect</p>
            <p>Tel: +21655678904</p>
        </div>
        <div class="footer-col">
            <h4>Pages</h4>
            <a href="index.php">Accueil</a><br />
            <a href="index.php?entity=ressource">Ressources</a><br />
            <a href="#">Événements</a>
        </div>
        <div class="footer-col">
            <h4>Communauté</h4>
            <a href="#">Messages</a><br />
            <a href="#">Groupes</a><br />
            <a href="#">Profil</a>
        </div>
    </div>
    <div class="footer-copy">© 2025 - Campus Connect. Tous droits réservés.</div>
</footer>

</body>
</html>