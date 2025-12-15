<!DOCTYPE html>
<html>
<head>
    <title>Mes Favoris - Campus Connect</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <link rel="stylesheet" type="text/css" href="View/assets/frontoffice.css?v=3" />

    <style>
        /* ===== Scroll Animations ===== */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.9s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .content-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .content-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>

<header class="header">
    <div class="header-inner">
        <div class="logo animate-on-scroll">CAMPUS CONNECT</div>
        <nav class="navbar animate-on-scroll">
            <a href="index.php" class="nav-link">Accueil</a>
            <a href="index.php?entity=ressource" class="nav-link">Ressources</a>
            <a href="index.php?entity=ressource&action=favoris" class="nav-link active">❤️ Mes favoris</a>
        </nav>
    </div>
</header>

<section class="shared-content animate-on-scroll">
    <h3 class="animate-on-scroll">❤️ Mes Ressources Favoris</h3>

    <?php
    $user_id = 1;
    $favoris = $ressourceModel->getFavorisParUser($user_id);
    ?>

    <?php if (!empty($favoris)): ?>
        <div class="cards-container">
            <?php foreach ($favoris as $index => $res): ?>
                <div class="content-card">
                    <div class="card-badge">
                        <?= htmlspecialchars($res['type_ressource'] ?? 'Ressource') ?>
                    </div>
                    <h4 class="card-title"><?= htmlspecialchars($res['titre']) ?></h4>
                    <p class="card-desc"><?= htmlspecialchars($res['description']) ?></p>
                    <ul class="card-meta">
                        <li><span>👤</span> <?= htmlspecialchars($res['auteur']) ?></li>
                        <li><span>❤️</span> <?= $res['nb_favoris'] ?> utilisateurs</li>
                    </ul>
                    <div class="card-actions">
                        <a href="<?= htmlspecialchars($res['url']) ?>" target="_blank" class="card-link">
                            📄 Ouvrir
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="empty-state animate-on-scroll">
            Aucune ressource en favoris. Cliquez sur ❤️ sur une ressource pour la sauvegarder !
        </p>
    <?php endif; ?>
</section>

<!-- ===== Scroll Observer ===== -->
<script>
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');

            if (entry.target.classList.contains('cards-container')) {
                entry.target.querySelectorAll('.content-card').forEach((card, i) => {
                    card.style.transitionDelay = `${i * 0.08}s`;
                    card.classList.add('visible');
                });
            }
        }
    });
}, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
});

document.querySelectorAll(
    '.animate-on-scroll, .content-card, .cards-container'
).forEach(el => observer.observe(el));
</script>

</body>
</html>
