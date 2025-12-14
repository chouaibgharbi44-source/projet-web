<?php if (!isset($mesPartages) || !isset($mesFavoris) || !isset($mesTelechargements)) { header('Location: index.php?entity=ressource'); exit; } ?>
<!DOCTYPE html>
<html>
<head>
    <title>Mes Activités - Campus Connect</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <link rel="stylesheet" type="text/css" href="View/assets/frontoffice.css?v=3" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            max-width: 1400px;
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

        /* ✅ GRID POUR LES 3 SECTIONS CÔTE À CÔTE */
        .activities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 30px;
        }
        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title i {
            color: #e74c3c;
            font-size: 1.4rem;
        }

        .content-grid {
            display: grid;
            gap: 15px;
            margin-top: 15px;
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
            background: #ffebf0;
            color: #e74c3c;
            font-size: 0.85rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .card-title {
            font-size: 1.15rem;
            font-weight: bold;
            margin: 10px 0;
            word-break: break-word;
        }
        .card-desc {
            color: #666;
            margin: 8px 0;
            font-size: 0.85rem;
            word-break: break-word;
        }
        .card-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 0.8rem;
            color: #999;
        }
        .card-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .card-actions {
            margin-top: 12px;
        }
        .card-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ff6faa;
            color: white;
            padding: 6px 14px;
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
            padding: 30px 10px;
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

        @media (max-width: 1200px) {
            .activities-grid {
                grid-template-columns: 1fr;
            }
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
            <a href="index.php?entity=ressource&action=activites" class="nav-link active">Mes Activites</a>
        </nav>
        <div class="admin-button">
            <a href="index.php?area=admin" class="pulse">Espace Admin</a>
        </div>
    </div>
</header>

<div class="container">

    <!-- ✅ GRID DES 3 SECTIONS -->
    <div class="activities-grid">

        <!-- SECTION 1 : MES FAVORIS -->
        <div class="section">
            <h2 class="section-title"><i class="fas fa-heart"></i> ❤️ Mes Favoris</h2>
            <?php if (empty($mesFavoris)): ?>
                <div class="empty-state">
                    <i class="far fa-heart"></i>
                    <p>Aucune ressource en favoris</p>
                </div>
            <?php else: ?>
                <div class="content-grid">
                    <?php foreach ($mesFavoris as $res): ?>
                        <div class="card">
                            <div class="card-type"><?= htmlspecialchars($res['type_ressource'] ?? 'Document') ?></div>
                            <h3 class="card-title"><?= htmlspecialchars($res['titre']) ?></h3>
                            <p class="card-desc"><?= htmlspecialchars($res['description']) ?></p>
                            <div class="card-meta">
                                <span><i class="fas fa-user"></i> <?= htmlspecialchars($res['auteur']) ?></span>
                                <span><i class="fas fa-heart"></i> <?= $res['nb_favoris'] ?></span>
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

        <!-- SECTION 2 : MES TÉLÉCHARGEMENTS -->
        <div class="section">
            <h2 class="section-title"><i class="fas fa-download"></i> 📥 Téléchargements</h2>
            <?php if (empty($mesTelechargements)): ?>
                <div class="empty-state">
                    <i class="fas fa-cloud-download-alt"></i>
                    <p>Aucun téléchargement</p>
                </div>
            <?php else: ?>
                <div class="content-grid">
                    <?php foreach ($mesTelechargements as $res): ?>
                        <div class="card">
                            <div class="card-type"><?= htmlspecialchars($res['type_ressource'] ?? 'Document') ?></div>
                            <h3 class="card-title"><?= htmlspecialchars($res['titre']) ?></h3>
                            <p class="card-desc"><?= htmlspecialchars($res['description']) ?></p>
                            <div class="card-meta">
                                <span><i class="fas fa-user"></i> <?= htmlspecialchars($res['auteur']) ?></span>
                                <span><i class="fas fa-download"></i> <?= $res['nb_telechargements'] ?></span>
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

        <!-- SECTION 3 : MES PARTAGES -->
        <div class="section">
            <h2 class="section-title"><i class="fas fa-folder"></i> 📁 Mes Partages</h2>
            <?php if (empty($mesPartages)): ?>
                <div class="empty-state">
                    <i class="far fa-folder-open"></i>
                    <p>Aucun partage</p>
                </div>
            <?php else: ?>
                <div class="content-grid">
                    <?php foreach ($mesPartages as $res): ?>
                        <div class="card">
                            <div class "card-type"><?= htmlspecialchars($res['type_ressource'] ?? 'Document') ?></div>
                            <h3 class="card-title"><?= htmlspecialchars($res['titre']) ?></h3>
                            <p class="card-desc"><?= htmlspecialchars($res['description']) ?></p>
                            <div class="card-meta">
                                <span><i class="fas fa-user"></i> <?= htmlspecialchars($res['auteur']) ?></span>
                                <span><i class="fas fa-book"></i> <?= htmlspecialchars($res['nom_matiere']) ?></span>
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