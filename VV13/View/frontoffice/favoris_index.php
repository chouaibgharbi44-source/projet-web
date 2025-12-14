<!DOCTYPE html>
<html>
<head>
    <title>Mes Favoris - Campus Connect</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <link rel="stylesheet" type="text/css" href="View/assets/frontoffice.css?v=3" />
</head>
<body>

<header class="header">
    <div class="header-inner">
        <div class="logo">CAMPUS CONNECT</div>
        <nav class="navbar">
            <a href="index.php" class="nav-link">Accueil</a>
            <a href="index.php?entity=ressource" class="nav-link">Ressources</a>
            <a href="index.php?entity=ressource&action=favoris" class="nav-link active">❤️ Mes favoris</a>
        </nav>
    </div>
</header>

<section class="shared-content">
    <h3>❤️ Mes Ressources Favoris</h3>
    <?php
    $user_id = 1;
    $favoris = $ressourceModel->getFavorisParUser($user_id);
    ?>
    <?php if (!empty($favoris)): ?>
        <div class="cards-container">
            <?php foreach ($favoris as $res): ?>
                <div class="content-card">
                    <div class="card-badge"><?= htmlspecialchars($res['type_ressource'] ?? 'Ressource') ?></div>
                    <h4 class="card-title"><?= htmlspecialchars($res['titre']) ?></h4>
                    <p class="card-desc"><?= htmlspecialchars($res['description']) ?></p>
                    <ul class="card-meta">
                        <li><span>👤</span> <?= htmlspecialchars($res['auteur']) ?></li>
                        <li><span>❤️</span> <?= $res['nb_favoris'] ?> utilisateurs</li>
                    </ul>
                    <div class="card-actions">
                        <a href="<?= htmlspecialchars($res['url']) ?>" target="_blank" class="card-link">📄 Ouvrir</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="empty-state">Aucune ressource en favoris. Cliquez sur ❤️ sur une ressource pour la sauvegarder !</p>
    <?php endif; ?>
</section>

</body>
</html>