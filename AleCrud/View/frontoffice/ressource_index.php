<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Campus Connect - Ressources Pédagogiques</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <link rel="stylesheet" type="text/css" href="View/assets/frontoffice.css?v=3" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<?php
$selectedId = null;
if (isset($selectedMatiere) && isset($selectedMatiere['id'])) {
    $selectedId = (int)$selectedMatiere['id'];
}
?>

<header class="header">
    <div class="header-inner">
        <div class="logo">CAMPUS CONNECT</div>
        <nav class="navbar">
            <a href="index.php" class="nav-link">Accueil</a>
            <a href="index.php?entity=ressource" class="nav-link active">Ressources</a>
            <a href="#" class="nav-link">Événements</a>
            <a href="#" class="nav-link">Messages</a>
            <a href="#" class="nav-link">Groupes</a>
            <a href="#" class="nav-link">Profil</a>
        </nav>
        <div class="admin-button">
            <a href="index.php?area=admin" class="pulse">Espace Admin</a>
        </div>
    </div>
</header>

<section class="hero-section">
    <div class="hero-content">
        <p class="hero-eyebrow">Ressources pédagogiques</p>
        <h1><?php echo $selectedMatiere ? 'Matière : ' . htmlspecialchars($selectedMatiere['nom_matiere']) : 'Choisissez une matière pour explorer les ressources'; ?></h1>
        <p class="hero-subtext">
            <?php if ($selectedMatiere): ?>
                Toutes les ressources partagées pour cette matière sont listées ci-dessous.
            <?php else: ?>
                Parcourez les matières disponibles, puis découvrez les supports, tutoriels et documents associés.
            <?php endif; ?>
        </p>
        <?php if ($selectedMatiere): ?>
            <a class="hero-cta" href="#ressources">Voir les ressources</a>
        <?php endif; ?>
    </div>
</section>

<section class="filter-panel">
    <div class="panel-header">
        <div>
            <p class="panel-eyebrow">Filtrer par matière</p>
            <h3>Construisez votre parcours d'apprentissage</h3>
            <p class="panel-sub">Chaque ressource est désormais reliée à une matière. Sélectionnez-en une pour afficher les contenus correspondants.</p>
        </div>
        <?php if ($selectedMatiere): ?>
            <a class="link-reset" href="index.php?entity=ressource">Réinitialiser le filtre</a>
        <?php endif; ?>
    </div>
    <?php if (!empty($matieres)): ?>
        <div class="matiere-pills">
            <?php foreach ($matieres as $matiere): ?>
                <?php $isActive = $selectedId === (int)$matiere['id']; ?>
                <a class="pill <?php echo $isActive ? 'active' : ''; ?>" href="index.php?entity=ressource&amp;matiere_id=<?php echo (int)$matiere['id']; ?>">
                    <span class="pill-title"><?php echo htmlspecialchars($matiere['nom_matiere']); ?></span>
                    <span class="pill-sub"><?php echo htmlspecialchars($matiere['niveau_difficulte'] ?? ''); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="empty-state">Aucune matière n'est disponible pour le moment.</p>
    <?php endif; ?>
</section>

<section class="section-with-form">
    <div class="hero-text">
        <h3>Partager une nouvelle ressource</h3>
        <p>Assignez chaque ressource à la matière concernée afin que la communauté s'y retrouve facilement.</p>
    </div>
    <button class="btn btn-add" onclick="document.getElementById('formModal').style.display='block';">➕ Ajouter une ressource</button>
</section>

<div id="formModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('formModal').style.display='none';">&times;</span>
        <h3>Ajouter une ressource</h3>
        <form method="post" action="index.php?entity=ressource&amp;action=store" onsubmit="return validateRessourceForm(this);">
            <label>Matière associée:<br />
                <select name="matiere_id" required>
                    <option value="">Sélectionnez une matière</option>
                    <?php foreach ($matieres as $matiere): ?>
                        <option value="<?php echo (int)$matiere['id']; ?>" <?php echo $selectedId === (int)$matiere['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($matiere['nom_matiere']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label><br />
            <label>Titre:<br /><input type="text" name="titre" placeholder="Ex: Introduction à la Programmation" required /></label><br />
            <label>Description:<br /><textarea name="description" rows="4" placeholder="Décrivez la ressource..." required></textarea></label><br />
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

<section id="ressources" class="shared-content">
    <?php if ($selectedMatiere): ?>
        <h3><?php echo htmlspecialchars($selectedMatiere['nom_matiere']); ?></h3>
        <p class="section-desc">Ressources partagées pour cette matière. <?php echo empty($ressources) ? 'Soyez la première personne à contribuer !' : 'Total : ' . count($ressources) . ' ressource(s).'; ?></p>
        <?php if (!empty($ressources)): ?>
            <div class="cards-container">
                <?php foreach ($ressources as $res): ?>
                    <?php
                    $typeLabel = isset($res['type_ressource']) && trim($res['type_ressource']) !== '' ? $res['type_ressource'] : 'Ressource';
                    $dateLabel = 'Date inconnue';
                    if (!empty($res['date_ajout'])) {
                        $timestamp = strtotime($res['date_ajout']);
                        if ($timestamp !== false) {
                            $dateLabel = date('d/m/Y', $timestamp);
                        }
                    }
                    ?>
                    <div class="content-card">
                        <div class="card-badge"><?php echo htmlspecialchars($typeLabel); ?></div>
                        <h4 class="card-title"><?php echo htmlspecialchars($res['titre']); ?></h4>
                        <p class="card-desc"><?php echo htmlspecialchars($res['description']); ?></p>
                        <ul class="card-meta">
                            <li><span>👤</span> <?php echo htmlspecialchars($res['auteur']); ?></li>
                            <li><span>🗓</span> <?php echo htmlspecialchars($dateLabel); ?></li>
                        </ul>
                        <div class="card-actions">
                            <a href="<?php echo htmlspecialchars($res['url']); ?>" target="_blank" class="card-link">📄 Ouvrir la ressource</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="empty-state">Aucune ressource pour l'instant. Partagez vos supports via le bouton ci-dessus.</p>
        <?php endif; ?>
    <?php else: ?>
        <h3>Ressources par matière</h3>
        <p class="section-desc">Sélectionnez une matière pour afficher ses ressources dédiées.</p>
        <div class="empty-state large">Aucune matière n'est sélectionnée.</div>
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

<!-- Validation JavaScript intégrée -->
<script type="text/javascript">
function validateRessourceForm(form) {
    const titre = form.titre.value.trim();
    const description = form.description.value.trim();

    let errors = [];

    // Validation : titre doit avoir au moins 1 minuscule ET 1 majuscule
    if (!/[a-z]/.test(titre) || !/[A-Z]/.test(titre)) {
        errors.push("Le titre doit contenir au moins une lettre minuscule et une lettre majuscule.");
    }

    // Validation : description > 20 caractères
    if (description.length <= 20) {
        errors.push("La description doit contenir plus de 20 caractères.");
    }

    if (errors.length > 0) {
        alert("Erreurs de validation :\n\n• " + errors.join("\n• "));
        return false;
    }

    return true;
}
</script>

</body>
</html>
