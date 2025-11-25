<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Campus Connect - Ressources Pédagogiques</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <link rel="stylesheet" type="text/css" href="View/assets/frontoffice.css" />
    <script type="text/javascript" src="View/assets/validation.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<!-- Header & Navigation -->
<header class="header">
    <div class="header-inner">
        <div class="logo">CAMPUS CONNECT</div>
        <nav class="navbar">
            <a href="index.php" class="nav-link">Accueil</a>
            <a href="index.php?entity=ressource" class="nav-link active">Ressources</a>
            <a href="#" class="nav-link">Evenements</a>
            <a href="#" class="nav-link">Messages</a>
            <a href="#" class="nav-link">Groupes</a>
            <a href="#" class="nav-link">Profil</a>
        </nav>
        <div class="admin-button">
            <a href="index.php?entity=ressource&area=admin" class="pulse">Admin</a>
        </div>
    </div>
</header>

<!-- Hero Section 1 -->
<section class="hero-section">
    <div class="hero-content">
        <h2>Ressources pédagogiques partagées par la communauté</h2>
    </div>
</section>

<!-- Hero Section 2 & Add Form -->
<section class="section-with-form">
    <div class="hero-text">
        <h3>Tous Les Contenus Partagés</h3>
        <p>Ressources pédagogiques partagées par la communauté</p>
    </div>
    <button class="btn btn-add" onclick="document.getElementById('formModal').style.display='block';">➕ Ajouter une ressource</button>
</section>

<!-- Stats / Charts -->
<section class="stats-grid">
    <div class="chart-card">
        <div class="chart-title">📊 Répartition par type</div>
        <div class="chart-sub">Distribution des ressources par type (animée)</div>
        <div class="chart-wrapper"><canvas id="typeChart" class="chart-canvas"></canvas></div>
        <div class="chart-legend" id="typeLegend"></div>
    </div>

    <div class="chart-card">
        <div class="chart-title">✨ Auteurs les plus actifs</div>
        <div class="chart-sub">Top auteurs par nombre de ressources</div>
        <div class="chart-wrapper"><canvas id="authorChart" class="chart-canvas"></canvas></div>
        <div class="chart-legend" id="authorLegend"></div>
    </div>
</section>

<!-- Modal Form -->
<div id="formModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('formModal').style.display='none';">&times;</span>
        <h3>Ajouter une ressource</h3>
        <form method="post" action="index.php?entity=ressource&action=store" onsubmit="return validateRessourceForm(this);">
            <label>Titre:<br /><input type="text" name="titre" placeholder="Ex: Introduction à la Programmation" /></label><br />
            <label>Description:<br /><textarea name="description" rows="4" placeholder="Décrivez la ressource..."></textarea></label><br />
            <label>Type de ressource:<br /><input type="text" name="type_ressource" placeholder="Ex: PDF, Tutoriel, Document" /></label><br />
            <label>URL:<br /><input type="text" name="url" placeholder="https://..." /></label><br />
            <label>Auteur:<br /><input type="text" name="auteur" placeholder="Votre nom" /></label><br />
            <label>Date d'ajout:<br /><input type="text" name="date_ajout" value="<?php echo date('Y-m-d H:i:s'); ?>" /></label><br />
            <div class="modal-actions">
                <button class="btn" type="submit">➕ Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- Shared Content Cards Section -->
<section class="shared-content">
    <h3>Tous Les Contenus Partagés</h3>
    <p class="section-desc">Ressources pédagogiques partagées par la communauté</p>
    <div class="cards-container">
        <?php if (!empty($ressources)) : ?>
            <?php foreach ($ressources as $res) : ?>
                <!-- Card -->
                <div class="content-card">
                    <div class="card-user">👤 <?php echo htmlspecialchars($res['auteur']); ?></div>
                    <h4 class="card-title"><?php echo htmlspecialchars($res['titre']); ?></h4>
                    <p class="card-desc"><?php echo htmlspecialchars($res['description']); ?></p>
                    <p style="font-size: 0.9em; color: #666;">Type: <strong><?php echo htmlspecialchars($res['type_ressource']); ?></strong></p>
                    <div class="card-actions">
                        <a href="<?php echo htmlspecialchars($res['url']); ?>" target="_blank" class="card-link">📄 Accéder</a>
                        <button class="btn-comment">Commenter</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune ressource trouvée.</p>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
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
            <h4>Pages</h4>
            <a href="#">Messages</a><br />
            <a href="#">Groupes</a><br />
            <a href="#">Profil</a>
        </div>
    </div>
    <div class="footer-copy">© 2025 - Campus Connect. Tous droits réservés.</div>
</footer>

<script type="text/javascript">
// Close modal when clicking outside
window.onclick = function(event) {
    var modal = document.getElementById('formModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<!-- Chart.js CDN + init -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<?php
// Prepare data for charts
$typeCounts = [];
$authorCounts = [];
if (!empty($ressources)) {
        foreach ($ressources as $r) {
                $t = trim($r['type_ressource']) !== '' ? $r['type_ressource'] : 'Autre';
                $a = trim($r['auteur']) !== '' ? $r['auteur'] : 'Anonyme';
                if (!isset($typeCounts[$t])) $typeCounts[$t] = 0;
                $typeCounts[$t]++;
                if (!isset($authorCounts[$a])) $authorCounts[$a] = 0;
                $authorCounts[$a]++;
        }
}

// Keep top authors only
arsort($authorCounts);
$authorCounts = array_slice($authorCounts, 0, 6, true);

$typeLabels = array_keys($typeCounts);
$typeValues = array_values($typeCounts);
$authorLabels = array_keys($authorCounts);
$authorValues = array_values($authorCounts);
?>

<script>
    (function(){
        const typeLabels = <?php echo json_encode($typeLabels ?: ['Autre']); ?>;
        const typeValues = <?php echo json_encode($typeValues ?: [1]); ?>;
        const authorLabels = <?php echo json_encode($authorLabels ?: []); ?>;
        const authorValues = <?php echo json_encode($authorValues ?: []); ?>;

        // Palette oriented towards dark purple / black
        const palette = ['#0b0b0b', '#2b0736', '#7b2da8', '#b56fe8', '#3b0f5a', '#4a0078'];

        // Type chart (doughnut)
        const typeCtx = document.getElementById('typeChart');
        if (typeCtx) {
            const typeChart = new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: typeLabels,
                    datasets: [{
                        data: typeValues,
                        backgroundColor: palette.slice(0, typeLabels.length),
                        borderColor: '#0b0210',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { display: false }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 900,
                        easing: 'easeOutCirc'
                    }
                }
            });

            // Render simple legend
            const legendContainer = document.getElementById('typeLegend');
            if (legendContainer) {
                typeLabels.forEach((label, idx) => {
                    const sw = document.createElement('span');
                    sw.className = 'legend-item';
                    sw.innerHTML = `<b style="display:inline-block;width:12px;height:12px;border-radius:3px;margin-right:8px;background:${palette[idx]}"></b>${label} — ${typeValues[idx]}`;
                    legendContainer.appendChild(sw);
                });
            }
        }

        // Author chart (horizontal bar)
        const authorCtx = document.getElementById('authorChart');
        if (authorCtx) {
            const authorChart = new Chart(authorCtx, {
                type: 'bar',
                data: {
                    labels: authorLabels,
                    datasets: [{
                        label: 'Ressources',
                        data: authorValues,
                        backgroundColor: palette.slice(1, 1 + authorLabels.length).map(c => c + 'cc'),
                        borderColor: palette.slice(1, 1 + authorLabels.length),
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#ddd' } },
                        y: { grid: { display: false }, ticks: { color: '#ddd' } }
                    },
                    animation: { duration: 900, easing: 'easeOutQuart' }
                }
            });

            const authorLegend = document.getElementById('authorLegend');
            if (authorLegend) {
                authorLabels.forEach((label, idx) => {
                    const el = document.createElement('span');
                    el.className = 'legend-item';
                    el.innerHTML = `<span class="legend-swatch" style="background:${palette[(idx+1)%palette.length]}"></span> ${label} — ${authorValues[idx]}`;
                    authorLegend.appendChild(el);
                });
            }
        }
    })();
</script>

</body>
</html>
