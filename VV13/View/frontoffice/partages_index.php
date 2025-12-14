<?php if (!isset($ressources)) { header('Location: index.php?entity=ressource'); exit; } ?>
<!DOCTYPE html>
<html>
<head>
    <title>Mes Ressources Partagées - Campus Connect</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <link rel="stylesheet" type="text/css" href="View/assets/frontoffice.css?v=3" />
    <style>
        .section {
            margin-top: 40px;
        }
        .section h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
        .content-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .card-desc {
            color: #555;
            margin: 10px 0;
        }
        .card-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #666;
        }
        .card-actions {
            margin-top: 15px;
        }
        .card-link {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: bold;
        }
        .card-link:hover {
            background: #2980b9;
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
            <a href="index.php?entity=ressource&action=partages" class="nav-link active">📁 Mes partages</a>
        
        </nav>
        <div class="admin-button">
        </div>
    </div>
</header>

<section class="hero-section">
    <div class="hero-content">
        <p class="hero-eyebrow">Ressources partagées</p>
        <h1>Mes Ressources Partagées</h1>
        <p class="hero-subtext">Voici toutes les ressources que vous avez partagées avec la communauté.</p>
    </div>
</section>

<section class="section">
    <h3>📁 Mes Ressources Partagées</h3>
    <?php if (empty($ressources)): ?>
        <div class="empty-state">
            <p>Vous n'avez partagé aucune ressource pour l'instant.</p>
            <a href="index.php?entity=ressource&action=add" class="card-link">➕ Ajouter une ressource</a>
        </div>
    <?php else: ?>
        <div class="cards-container">
            <?php foreach ($ressources as $res): ?>
                <div class="content-card">
                    <h4 class="card-title"><?= htmlspecialchars($res['titre']) ?></h4>
                    <p class="card-desc"><?= htmlspecialchars($res['description']) ?></p>
                    <div class="card-meta">
                        <span><strong>Matière :</strong> <?= htmlspecialchars($res['nom_matiere']) ?></span>
                        <span><strong>Auteur :</strong> <?= htmlspecialchars($res['auteur']) ?></span>
                    </div>
                    <div class="card-actions">
                        <a href="<?= htmlspecialchars($res['url']) ?>" target="_blank" class="card-link">📄 Ouvrir la ressource</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

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