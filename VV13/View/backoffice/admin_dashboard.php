<?php
$isDashboard = !isset($_GET['action']);

$matiereModel = new Matiere();
$ressourceModel = new Ressource();

$matieres = $matiereModel->getAll();
$ressources = $ressourceModel->getAll();

// ✅ Récupérer les nouvelles statistiques
$totalDownloads = $ressourceModel->countTotalDownloads();
$totalFavorites = $ressourceModel->countTotalFavorites();
$topDownloads = $ressourceModel->getTopDownloads(5);
$topFavorites = $ressourceModel->getTopFavorites(5);

$stats = [
    'total_matieres' => count($matieres),
    'total_ressources' => count($ressources),
    'total_downloads' => $totalDownloads,
    'total_favorites' => $totalFavorites,
    'ressources_par_type' => $ressourceModel->countByType() ?: [['label' => 'Aucun', 'total' => 1]],
    'top_auteurs' => $ressourceModel->getTopAuteurs() ?: [['label' => 'Aucun', 'total' => 1]],
    'matieres_sans_ressource' => [],
    'activite_recente' => ['labels' => [], 'data' => []],
    'top_downloads' => $topDownloads,
    'top_favorites' => $topFavorites
];

// Matières sans ressource
$avecRessource = $ressourceModel->countByMatiere();
$idsAvecRessource = array_column($avecRessource, 'matiere_id');
$stats['matieres_sans_ressource'] = array_filter($matieres, function($m) use ($idsAvecRessource) {
    return !in_array($m['id'], $idsAvecRessource);
});

// Activité récente (optionnel)
$pdo = $matiereModel->getPdo();
$stmt = $pdo->prepare("
    SELECT DATE(created_at) as jour, COUNT(*) as nb
    FROM log
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY jour ASC
");
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$debut = new DateTime('-6 days');
$stat = [];
for ($i = 0; $i < 7; $i++) {
    $date = $debut->format('Y-m-d');
    $stat[$date] = 0;
    $debut->modify('+1 day');
}
foreach ($logs as $log) {
    $stat[$log['jour']] = (int)$log['nb'];
}
$stats['activite_recente'] = ['labels' => array_keys($stat), 'data' => array_values($stat)];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord - Backoffice</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
            background: #f0f2f5; 
            color: #1e293b; 
            padding: 20px; 
        }
        .container { 
            max-width: 1400px; 
            margin: 0 auto; 
        }
        .topbar { 
            background: #1e293b; 
            color: white; 
            padding: 1rem 2rem; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 12px;
            margin-bottom: 24px;
        }
        .sidebar { 
            width: 250px; 
            background: linear-gradient(135deg, #ff6faa 0%, #ff5fa2 100%); 
            color: white; 
            min-height: 100vh; 
            padding: 20px 0; 
            position: fixed; 
            left: 0; 
            top: 0; 
            box-shadow: 2px 0 10px rgba(0,0,0,0.15);
            z-index: 1000;
        }
        .sidebar .logo { 
            padding: 20px 30px; 
            font-weight: bold; 
            font-size: 1.4rem;
            border-bottom: 1px solid rgba(255,255,255,0.2); 
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar ul { 
            list-style: none; 
            padding: 0; 
            margin-top: 20px;
        }
        .sidebar li { 
            padding: 12px 30px; 
        }
        .sidebar a { 
            color: white; 
            text-decoration: none; 
            display: block; 
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            padding-left: 15px;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: rgba(255,255,255,0.15); 
            padding-left: 25px; 
            border-left: 3px solid #ff6b6b;
        }
        .main-content { 
            flex: 1; 
            padding: 20px; 
            margin-left: 270px; 
            background: #f0f2f5; 
        }
        .section-title { 
            font-size: 1.8rem; 
            font-weight: 700; 
            margin: 20px 0 30px; 
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section-title::before {
            content: "";
            display: inline-block;
            width: 8px;
            height: 30px;
            background: linear-gradient(to bottom, #ff6faa, #ff5fa2);
            border-radius: 4px;
        }
        .kpi-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
            gap: 20px; 
            margin-bottom: 40px; 
        }
        .kpi-card { 
            background: white; 
            padding: 24px; 
            border-radius: 20px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.08); 
            text-align: center; 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .kpi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
        .kpi-value { 
            font-size: 2.8rem; 
            font-weight: 800; 
            margin: 15px 0; 
            background: linear-gradient(45deg, #ff6faa, #ff5fa2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .kpi-label { 
            color: #475569; 
            font-size: 1.1rem; 
            font-weight: 600;
            margin-top: 8px;
        }

        /* Nouvelle section pour les tops */
        .tops-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
            margin-top: 30px;
        }
        .top-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 25px;
            transition: transform 0.3s ease;
        }
        .top-card:hover {
            transform: translateY(-3px);
        }
        .top-card h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .top-card h3 i {
            color: #ff6b6b;
        }
        .top-list {
            list-style: none;
            padding: 0;
        }
        .top-item {
            padding: 14px 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .top-item:last-child {
            border-bottom: none;
        }
        .top-rank {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #ff6faa;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .top-rank:nth-child(2) { background: #ff5fa2; }
        .top-rank:nth-child(3) { background: #ff4f92; }
        .top-item-content {
            flex: 1;
        }
        .top-item-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
            font-size: 1.05rem;
        }
        .top-item-meta {
            display: flex;
            justify-content: space-between;
            color: #64748b;
            font-size: 0.95rem;
        }
        .top-item-count {
            font-weight: bold;
            color: #ff6faa;
        }

        .orphelines { 
            background: white; 
            padding: 24px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            margin: 30px 0; 
        }
        .orphelines h2 { 
            font-size: 1.4rem; 
            color: #dc2626; 
            margin-bottom: 20px; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
        }
        .orphelines ul { 
            list-style: none; 
            padding: 0; 
        }
        .orphelines li { 
            padding: 14px 0; 
            border-bottom: 1px solid #eee; 
            font-size: 1.05rem;
            display: flex;
            justify-content: space-between;
        }
        .orphelines li:last-child { 
            border-bottom: none; 
        }

        .content-cards { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 20px; 
            margin-top: 30px; 
        }
        .card { 
            background: white; 
            padding: 25px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); 
            text-align: center; 
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #ff6faa;
        }
        .card-title { 
            font-size: 1.4rem; 
            font-weight: 700; 
            margin: 15px 0; 
            color: #1e293b;
        }
        .card-desc { 
            color: #64748b; 
            margin: 15px 0; 
            line-height: 1.6;
            font-size: 1.05rem;
        }
        .card-btn { 
            display: inline-block; 
            background: linear-gradient(45deg, #ff6faa, #ff5fa2);
            color: white; 
            padding: 12px 28px; 
            border-radius: 50px; 
            text-decoration: none; 
            font-weight: 600; 
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 111, 170, 0.4);
        }
        .card-btn:hover { 
            background: linear-gradient(45deg, #ff5fa2, #ff4f92);
            box-shadow: 0 6px 20px rgba(255, 111, 170, 0.6);
            transform: translateY(-2px);
        }

        @media (max-width: 1200px) {
            .main-content {
                margin-left: 260px;
            }
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            .sidebar .logo {
                justify-content: center;
                padding: 20px;
            }
            .sidebar .logo span {
                display: none;
            }
            .sidebar li {
                padding: 12px 10px;
                text-align: center;
            }
            .sidebar a span {
                display: none;
            }
            .sidebar a::before {
                content: attr(data-icon);
                font-size: 1.2rem;
                display: block;
            }
            .main-content {
                margin-left: 80px;
            }
            .tops-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .kpi-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            }
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            .topbar {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <i class="fas fa-graduation-cap"></i>
        <span>Campus Connect</span>
    </div>
    <ul>
        <li><a href="index.php?area=admin" class="<?= $isDashboard ? 'active' : '' ?>" data-icon="📊">📊 Tableau de Bord</a></li>
        <li><a href="index.php?entity=matiere&area=admin&action=list" data-icon="📚">📚 Matières</a></li>
        <li><a href="index.php?entity=ressource&area=admin&action=list" data-icon="📄">📄 Ressources</a></li>
        <li><a href="index.php?entity=ressource&area=admin&action=favoris" data-icon="❤️">❤️ Favoris</a></li>
        <li><a href="index.php?entity=ressource&area=admin&action=telechargements" data-icon="📥">📥 Téléchargements</a></li>
        <li><a href="index.php?area=admin&action=logs" data-icon="📋">📋 Historique</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="topbar">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard Administrateur</h1>
        <a href="index.php" style="background: #ff6faa; color: white; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-home"></i> Frontoffice
        </a>
    </div>

    <div class="container">
        <?php if (!$isDashboard): ?>
            <h2 class="section-title"><i class="fas fa-cogs"></i> Gestion du contenu</h2>
            <div class="content-cards">
                <div class="card">
                    <div class="card-icon">📚</div>
                    <h3 class="card-title">Matières</h3>
                    <p class="card-desc">Gérez toutes les matières pédagogiques du système</p>
                    <a href="index.php?entity=matiere&area=admin&action=list" class="card-btn">Accéder</a>
                </div>
                <div class="card">
                    <div class="card-icon">📄</div>
                    <h3 class="card-title">Ressources</h3>
                    <p class="card-desc">Gérez toutes les ressources partagées par les utilisateurs</p>
                    <a href="index.php?entity=ressource&area=admin&action=list" class="card-btn">Accéder</a>
                </div>
                <div class="card">
                    <div class="card-icon">📋</div>
                    <h3 class="card-title">Historique</h3>
                    <p class="card-desc">Consultez toutes les actions effectuées dans le système</p>
                    <a href="index.php?area=admin&action=logs" class="card-btn" style="background: #ff6b6b;">Voir l'historique</a>
                </div>
            </div>
        <?php else: ?>
            <h2 class="section-title"><i class="fas fa-chart-line"></i> Statistiques Globales</h2>
            
            <!-- KPI Cards -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-value"><?= $stats['total_matieres'] ?></div>
                    <div class="kpi-label">Matières</div>
                    <div class="kpi-change">+<?= count($stats['matieres_sans_ressource']) ?> sans ressource</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value"><?= $stats['total_ressources'] ?></div>
                    <div class="kpi-label">Ressources</div>
                    <div class="kpi-change">+0 cette semaine</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value"><?= $stats['total_downloads'] ?></div>
                    <div class="kpi-label">Téléchargements</div>
                    <div class="kpi-change">+0 cette semaine</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-value"><?= $stats['total_favorites'] ?></div>
                    <div class="kpi-label">Favoris</div>
                    <div class="kpi-change">+0 cette semaine</div>
                </div>
            </div>

            <!-- Top téléchargements et favoris -->
            <div class="tops-grid">
                <div class="top-card">
                    <h3><i class="fas fa-download"></i> 📥 Top Téléchargements</h3>
                    <ul class="top-list">
                        <?php if (!empty($stats['top_downloads'])): ?>
                            <?php foreach ($stats['top_downloads'] as $index => $res): ?>
                                <li class="top-item">
                                    <div class="top-rank"><?= $index + 1 ?></div>
                                    <div class="top-item-content">
                                        <div class="top-item-title"><?= htmlspecialchars($res['titre']) ?></div>
                                        <div class="top-item-meta">
                                            <span><i class="fas fa-user"></i> <?= htmlspecialchars($res['auteur']) ?> • <?= htmlspecialchars($res['nom_matiere']) ?></span>
                                            <span class="top-item-count"><i class="fas fa-download"></i> <?= $res['nb_telechargements'] ?></span>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li style="text-align: center; padding: 30px; color: #94a3b8;">Aucun téléchargement pour le moment</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="top-card">
                    <h3><i class="fas fa-heart"></i> ❤️ Top Favoris</h3>
                    <ul class="top-list">
                        <?php if (!empty($stats['top_favorites'])): ?>
                            <?php foreach ($stats['top_favorites'] as $index => $res): ?>
                                <li class="top-item">
                                    <div class="top-rank"><?= $index + 1 ?></div>
                                    <div class="top-item-content">
                                        <div class="top-item-title"><?= htmlspecialchars($res['titre']) ?></div>
                                        <div class="top-item-meta">
                                            <span><i class="fas fa-user"></i> <?= htmlspecialchars($res['auteur']) ?> • <?= htmlspecialchars($res['nom_matiere']) ?></span>
                                            <span class="top-item-count"><i class="fas fa-heart"></i> <?= $res['nb_favoris'] ?></span>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li style="text-align: center; padding: 30px; color: #94a3b8;">Aucun favori pour le moment</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <?php if (!empty($stats['matieres_sans_ressource'])): ?>
            <div class="orphelines">
                <h2><i class="fas fa-exclamation-triangle"></i> ⚠️ Matières sans ressource (<?= count($stats['matieres_sans_ressource']) ?>)</h2>
                <ul>
                    <?php foreach ($stats['matieres_sans_ressource'] as $m): ?>
                    <li>
                        <span><?= htmlspecialchars($m['nom_matiere']) ?></span>
                        <span style="color: #64748b;">(Niv. <?= $m['niveau_difficulte'] ?>)</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>