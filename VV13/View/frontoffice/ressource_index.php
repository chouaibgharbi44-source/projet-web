<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Campus Connect - Ressources Pédagogiques</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <link rel="stylesheet" type="text/css" href="View/assets/frontoffice.css?v=3" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* Messages d'erreur dans la modale */
        .modal .error {
            color: #d32f2f;
            font-size: 0.85em;
            margin-top: 4px;
            display: block;
        }

        /* Suggestions de ressources similaires */
        .suggestions-section {
            margin-top: 40px;
            padding: 20px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }
        .suggestions-title {
            color: #3498db;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .suggestion-card {
            background: #f8fafc;
            padding: 15px;
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .suggestion-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .suggestion-card h4 {
            font-size: 1.1rem;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        .suggestion-card p {
            color: #64748b;
            font-size: 0.9rem;
            margin: 6px 0;
        }
        .suggestion-link {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            margin-top: 8px;
        }
        .suggestion-link:hover {
            background: #2980b9;
        }

        /* Notifications */
        #notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            max-width: 350px;
        }
        .notification {
            background: white;
            border-left: 4px solid #3498db;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            animation: slideIn 0.3s ease-out;
            position: relative;
        }
        .notification.success { border-left-color: #27ae60; }
        .notification.like { border-left-color: #e74c3c; }
        .notification.download { border-left-color: #3498db; }
        .notification.motivation { border-left-color: #9b59b6; background: #f9f0ff; }

        .notification h4 {
            margin: 0 0 8px;
            font-size: 1.1rem;
            color: #2c3e50;
        }
        .notification p {
            margin: 0;
            color: #7f8c8d;
            font-size: 0.95rem;
        }
        .notification a {
            display: inline-block;
            margin-top: 8px;
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        .notification a:hover {
            text-decoration: underline;
        }
        .notification .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #95a5a6;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* ✅ CHATBOT FLOTTANT */
        .chatbot-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }
        .chatbot-toggle {
            background: linear-gradient(135deg, #ff6faa 0%, #ff5fa2 100%);
            color: white;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(255, 111, 170, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.8rem;
            animation: pulse 2s infinite;
            border: none;
            outline: none;
        }
        .chatbot-toggle:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 111, 170, 0.6);
        }
        .chatbot-window {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 360px;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.25);
            overflow: hidden;
            display: none;
            animation: slideUp 0.4s ease-out;
            transform: translateY(20px);
            opacity: 0;
            background: white;
        }
        .chatbot-window.active {
            display: block;
            transform: translateY(0);
            opacity: 1;
        }
        .chatbot-header {
            background: linear-gradient(135deg, #ff6faa 0%, #ff5fa2 100%);
            color: white;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .chatbot-avatar {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: bold;
            color: #ff6faa;
        }
        .chatbot-title {
            font-size: 1.1rem;
            font-weight: 600;
        }
        .chatbot-status {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-top: 2px;
        }
        .chatbot-messages {
            height: 400px;
            overflow-y: auto;
            padding: 15px;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .message {
            max-width: 85%;
            padding: 12px 16px;
            border-radius: 18px;
            line-height: 1.5;
            animation: fadeIn 0.3s ease;
        }
        .bot-message {
            align-self: flex-start;
            background: white;
            border-bottom-left-radius: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .user-message {
            align-self: flex-end;
            background: #ffebee;
            color: #e91e63;
            border-bottom-right-radius: 6px;
        }
        .chatbot-input-container {
            padding: 12px 15px;
            background: white;
            border-top: 1px solid #eee;
        }
        .chatbot-input {
            display: flex;
            gap: 10px;
        }
        .chatbot-input input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        .chatbot-input input:focus {
            border-color: #ff6faa;
            box-shadow: 0 0 0 2px rgba(255, 111, 170, 0.2);
        }
        .chatbot-input button {
            background: #ff6faa;
            color: white;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .chatbot-input button:hover {
            background: #ff5fa2;
            transform: scale(1.05);
        }
        .chatbot-quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 0 15px 15px;
        }
        .quick-btn {
            background: #ffebee;
            border: 1px solid #ff6faa;
            color: #e91e63;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .quick-btn:hover {
            background: #ffccbc;
            border-color: #ff6faa;
            color: #ff6faa;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 111, 170, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(255, 111, 170, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 111, 170, 0); }
        }
        @keyframes slideUp {
            from { 
                transform: translateY(20px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Version mobile */
        @media (max-width: 768px) {
            .chatbot-window {
                width: 95%;
                right: 50%;
                transform: translateX(50%) translateY(20px);
            }
            .chatbot-window.active {
                transform: translateX(50%) translateY(0);
            }
        }
    </style>
</head>
<body>

<!-- Notifications -->
<div id="notification-container"></div>

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
            <a href="../view/FrontOffice/homepage.php" class="nav-link">Accueil</a>
            <a href="index.php" class="nav-link">matiéres</a>
            <a href="index.php?entity=ressource" class="nav-link active">Ressources</a>
            <a href="index.php?entity=ressource&action=favoris" class="nav-link">Mes favoris</a>
            <a href="index.php?entity=ressource&action=telechargements" class="nav-link">Mes téléchargements</a>
            <a href="index.php?entity=ressource&action=partages" class="nav-link">Mes partages</a>
        </nav>
        <div class="admin-button">
           
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
        <input type="text" id="searchMatiere" placeholder="Rechercher une matière..." style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; width: 200px;" />
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
        <form id="frontRessourceForm" method="post" action="index.php?entity=ressource&amp;action=store">
            <label>Matière associée:<br />
                <select name="matiere_id" id="modal-matiere_id" >
                    <option value="">Sélectionnez une matière</option>
                    <?php foreach ($matieres as $matiere): ?>
                        <option value="<?php echo (int)$matiere['id']; ?>" <?php echo $selectedId === (int)$matiere['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($matiere['nom_matiere']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="error" id="modal-error-matiere_id"></span>
            </label><br />

            <label>Titre:<br />
                <input type="text" name="titre" id="modal-titre" placeholder="Ex: Introduction à la Programmation" />
                <span class="error" id="modal-error-titre"></span>
            </label><br />

            <label>Description:<br />
                <textarea name="description" id="modal-description" rows="4" placeholder="Décrivez la ressource..."></textarea>
                <span class="error" id="modal-error-description"></span>
            </label><br />

            <label>Type de ressource:<br />
                <input type="text" name="type_ressource" placeholder="Ex: PDF, Tutoriel, Document" />
            </label><br />

            <label>URL:<br />
                <input type="text" name="url" id="modal-url" placeholder="https://..." />
                <span class="error" id="modal-error-url"></span>
            </label><br />

            <label>Auteur:<br />
                <input type="text" name="auteur" placeholder="Votre nom" />
            </label><br />

            <label>Date d'ajout:<br />
                <input type="text" name="date_ajout" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly />
            </label><br />

            <div class="modal-actions">
                <button class="btn" type="submit">➕ Ajouter</button>
            </div>
        </form>
    </div>
</div>

<section id="ressources" class="shared-content">
    <?php if ($selectedMatiere): ?>
        <h3><?= htmlspecialchars($selectedMatiere['nom_matiere']); ?></h3>
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
                        <div class="card-badge"><?= htmlspecialchars($typeLabel); ?></div>
                        <h4 class="card-title"><?= htmlspecialchars($res['titre']); ?></h4>
                        <p class="card-desc"><?= htmlspecialchars($res['description']); ?></p>
                        <ul class="card-meta">
                            <li><span>👤</span> <?= htmlspecialchars($res['auteur']); ?></li>
                            <li><span>🗓</span> <?= htmlspecialchars($dateLabel); ?></li>
                        </ul>
                        <div class="card-actions">
                            <a href="<?= htmlspecialchars($res['url']); ?>" target="_blank" class="card-link">📄 Ouvrir la ressource</a>
                            <?php
                            $user_id = 1;
                            $estFavori = $ressourceModel->estFavori($res['id'], $user_id);
                            $nbFavoris = $ressourceModel->getNbFavoris($res['id']);
                            $couleur = $estFavori ? '#e74c3c' : '#7f8c8d';
                            ?>
                            <a href="index.php?entity=ressource&action=toggle-favori&id=<?= $res['id'] ?>" 
                            style="display: inline-block; margin-top: 10px; color: <?= $couleur ?>; text-decoration: none;">
                                ❤️ J’aime (<?= $nbFavoris ?>)
                            </a>
                            <a href="index.php?entity=ressource&action=telecharger&id=<?= $res['id'] ?>" 
                               class="card-link" style="margin-top: 10px; display: inline-block;">
                                📥 Télécharger
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php
            $baseRessource = $ressources[0];
            $ressourcesSimilaires = $ressourceModel->getRessourcesSimilaires(
                $baseRessource['id'],
                $baseRessource['titre'],
                $baseRessource['description'],
                4
            );
            ?>
            <?php if (!empty($ressourcesSimilaires)): ?>
                <div class="suggestions-section">
                    <div class="suggestions-title">
                        <span>🤖</span> Ressources recommandées pour vous
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-top: 15px;">
                        <?php foreach ($ressourcesSimilaires as $sim): ?>
                            <div class="suggestion-card">
                                <h4><?= htmlspecialchars($sim['titre']) ?></h4>
                                <p><strong>Auteur :</strong> <?= htmlspecialchars($sim['auteur']) ?></p>
                                <p><strong>Matière :</strong> <?= htmlspecialchars($sim['nom_matiere']) ?></p>
                                <a href="<?= htmlspecialchars($sim['url']) ?>" target="_blank" class="suggestion-link">Ouvrir la ressource</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

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

<script type="text/javascript">
window.onclick = function(event) {
    const modal = document.getElementById('formModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};

document.getElementById('frontRessourceForm').addEventListener('submit', function(e) {
    let isValid = true;
    const matiereId = document.getElementById('modal-matiere_id').value;
    const errMatiere = document.getElementById('modal-error-matiere_id');
    if (matiereId === '') {
        errMatiere.textContent = 'Veuillez sélectionner une matière.';
        isValid = false;
    } else errMatiere.textContent = '';

    const titre = document.getElementById('modal-titre').value.trim();
    const errTitre = document.getElementById('modal-error-titre');
    if (titre === '') {
        errTitre.textContent = 'Le titre est obligatoire.';
        isValid = false;
    } else {
        const hasUpper = /[A-Z]/.test(titre);
        const hasLower = /[a-z]/.test(titre);
        if (!hasUpper || !hasLower) {
            errTitre.textContent = 'Doit contenir au moins une majuscule et une minuscule.';
            isValid = false;
        } else errTitre.textContent = '';
    }

    const desc = document.getElementById('modal-description').value;
    const errDesc = document.getElementById('modal-error-description');
    if (desc.length < 20) {
        errDesc.textContent = 'La description doit contenir au moins 20 caractères.';
        isValid = false;
    } else errDesc.textContent = '';

    const url = document.getElementById('modal-url').value.trim();
    const errUrl = document.getElementById('modal-error-url');
    if (url !== '' && !/^https?:\/\/.+/i.test(url)) {
        errUrl.textContent = 'URL invalide (doit commencer par http:// ou https://).';
        isValid = false;
    } else errUrl.textContent = '';

    if (!isValid) {
        e.preventDefault();
    }
});
</script>

<script>
document.getElementById('searchMatiere').addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    const pills = document.querySelectorAll('.matiere-pills .pill');
    pills.forEach(pill => {
        const titleElement = pill.querySelector('.pill-title');
        const title = titleElement ? titleElement.textContent.toLowerCase() : '';
        pill.style.display = title.includes(query) ? 'inline-block' : 'none';
    });
});
</script>

<!-- ✅ SCRIPT NOTIFICATIONS -->
<script>
let notificationId = 0;

function showNotification(title, message, type = 'success', link = null, linkText = 'Voir') {
    const container = document.getElementById('notification-container');
    const id = 'notif-' + (++notificationId);
    
    let icon = 'ℹ️';
    if (type === 'success') icon = '✅';
    if (type === 'like') icon = '❤️';
    if (type === 'download') icon = '📥';
    if (type === 'motivation') icon = '✨';

    let html = `
        <div id="${id}" class="notification ${type}">
            <button class="close-btn" onclick="hideNotification('${id}')">×</button>
            <h4>${icon} ${title}</h4>
            <p>${message}</p>
            ${link ? `<a href="${link}">${linkText}</a>` : ''}
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', html);
    setTimeout(() => hideNotification(id), 8000);
}

function hideNotification(id) {
    const notif = document.getElementById(id);
    if (notif) {
        notif.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(() => notif.remove(), 300);
    }
}

const style = document.createElement('style');
style.textContent = `
@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}`;
document.head.appendChild(style);

const motivationalMessages = [
    "Continue comme ça ! 🌟",
    "Tu progresses à chaque ressource ! 💪",
    "La connaissance, c'est le pouvoir ! 🔥",
    "Tu es sur la bonne voie ! 🚀",
    "Partager, c'est grandir ensemble ! 🤝",
    "Chaque ressource compte ! ✨"
];

function maybeShowMotivation() {
    if (Math.random() < 0.1) {
        const msg = motivationalMessages[Math.floor(Math.random() * motivationalMessages.length)];
        showNotification("Message de motivation", msg, "motivation");
    }
}

// ✅ AFFICHER LA NOTIFICATION SI ELLE EXISTE
<?php if (isset($notification)): ?>
document.addEventListener('DOMContentLoaded', function() {
    showNotification(
        <?= json_encode($notification['title']) ?>,
        <?= json_encode($notification['message']) ?>,
        <?= json_encode($notification['type']) ?>,
        <?= json_encode($notification['link']) ?>,
        <?= json_encode($notification['linkText']) ?>
    );
    <?php if (isset($showMotivation) && $showMotivation): ?>
    maybeShowMotivation();
    <?php endif; ?>
});
<?php endif; ?>
</script>

<!-- ✅ CHATBOT FLOTTANT -->
<div class="chatbot-widget">
    <button class="chatbot-toggle" id="chatbotToggle">🤖</button>
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <div class="chatbot-avatar">A</div>
            <div>
                <div class="chatbot-title">Assistant Campus Connect</div>
                <div class="chatbot-status">En ligne • Répond en quelques secondes</div>
            </div>
        </div>
        <div class="chatbot-messages" id="chatMessages">
            <!-- Messages dynamiques ici -->
        </div>
        <div class="chatbot-quick-actions">
            <button class="quick-btn" onclick="askQuestion('Comment ajouter une matière ?')">📚 Ajouter une matière</button>
            <button class="quick-btn" onclick="askQuestion('Comment filtrer les ressources ?')">🔍 Filtrer</button>
            <button class="quick-btn" onclick="askQuestion('Comment télécharger un fichier ?')">📥 Télécharger</button>
            <button class="quick-btn" onclick="askQuestion('Comment ajouter un favori ?')">❤️ Ajouter un favori</button>
        </div>
        <div class="chatbot-input-container">
            <div class="chatbot-input">
                <input type="text" id="userInput" placeholder="Posez votre question...">
                <button onclick="sendMessage()">➤</button>
            </div>
        </div>
    </div>
</div>

<script>
// ✅ SCRIPT CHATBOT FLOTTANT
let chatbotOpen = false;
const toggleBtn = document.getElementById('chatbotToggle');
const chatbotWindow = document.getElementById('chatbotWindow');
const chatMessages = document.getElementById('chatMessages');
const userInput = document.getElementById('userInput');

// Base de connaissances
const knowledgeBase = {
    "comment ajouter une matière": "Pour ajouter une matière :\n1. Cliquez sur le menu 'Matières' dans la barre de navigation\n2. Cliquez sur le bouton '➕ Ajouter une matière'\n3. Remplissez le formulaire avec le nom, le titre et le niveau de difficulté\n4. Validez en cliquant sur 'Ajouter'",
    "ajouter une matière": "Pour ajouter une matière :\n1. Cliquez sur le menu 'Matières' dans la barre de navigation\n2. Cliquez sur le bouton '➕ Ajouter une matière'\n3. Remplissez le formulaire avec le nom, le titre et le niveau de difficulté\n4. Validez en cliquant sur 'Ajouter'",
    "comment télécharger une ressource": "Pour télécharger une ressource :\n1. Cliquez sur le bouton 'Télécharger' sous la ressource\n2. La ressource sera ajoutée à vos téléchargements\n3. Vous pourrez la retrouver dans '📥 Mes téléchargements'",
    "télécharger une ressource": "Pour télécharger une ressource :\n1. Cliquez sur le bouton 'Télécharger' sous la ressource\n2. La ressource sera ajoutée à vos téléchargements\n3. Vous pourrez la retrouver dans '📥 Mes téléchargements'",
    "comment télécharger": "Pour télécharger une ressource :\n1. Cliquez sur le bouton 'Télécharger' sous la ressource\n2. La ressource sera ajoutée à vos téléchargements\n3. Vous pourrez la retrouver dans '📥 Mes téléchargements'",
    "télécharger": "Pour télécharger une ressource :\n1. Cliquez sur le bouton 'Télécharger' sous la ressource\n2. La ressource sera ajoutée à vos téléchargements\n3. Vous pourrez la retrouver dans '📥 Mes téléchargements'",
    "comment filtrer les ressources": "Pour filtrer les ressources :\n1. Utilisez la barre de recherche en haut de la section 'Filtrer par matière'\n2. Tapez le nom de la matière\n3. Les résultats s'afficheront automatiquement",
    "filtrer": "Pour filtrer les ressources :\n1. Utilisez la barre de recherche en haut de la section 'Filtrer par matière'\n2. Tapez le nom de la matière\n3. Les résultats s'afficheront automatiquement",
    "comment ajouter un favori": "Pour ajouter un favori :\n1. Cliquez sur le bouton 'J'aime' (❤️) sous la ressource\n2. La ressource sera ajoutée à vos favoris\n3. Vous pourrez la retrouver dans '❤️ Mes favoris'",
    "ajouter un favori": "Pour ajouter un favori :\n1. Cliquez sur le bouton 'J'aime' (❤️) sous la ressource\n2. La ressource sera ajoutée à vos favoris\n3. Vous pourrez la retrouver dans '❤️ Mes favoris'",
    "qu'est-ce que mes activités": "La section '👤 Mes activités' regroupe :\n• Vos ressources partagées\n• Vos favoris\n• Vos téléchargements\nCliquez sur '👤 Mes activités' dans le menu pour y accéder",
    "mes activités": "La section '👤 Mes activités' regroupe :\n• Vos ressources partagées\n• Vos favoris\n• Vos téléchargements\nCliquez sur '👤 Mes activités' dans le menu pour y accéder",
    "salut": "Bonjour ! Je suis l'Assistant Campus Connect. Comment puis-je vous aider aujourd'hui ? 😊",
    "bonjour": "Bonjour ! Je suis l'Assistant Campus Connect. Comment puis-je vous aider aujourd'hui ? 😊",
    "aide": "Je peux vous aider avec :\n• L'ajout de matières\n• Le filtrage des ressources\n• Le téléchargement de fichiers\n• La gestion des favoris\nPosez-moi simplement votre question !",
    "merci": "Avec plaisir ! N'hésitez pas à me poser d'autres questions. 😊",
    "remerciements": "Avec plaisir ! N'hésitez pas à me poser d'autres questions. 😊"
};

// Toggle chatbot window
toggleBtn.addEventListener('click', function() {
    chatbotOpen = !chatbotOpen;
    chatbotWindow.classList.toggle('active', chatbotOpen);
    
    if (chatbotOpen && chatMessages.children.length === 0) {
        setTimeout(() => {
            addMessage("Bonjour ! Je suis l'Assistant Campus Connect. Comment puis-je vous aider aujourd'hui ? 😊", 'bot');
        }, 300);
    }
});

// Send message
function sendMessage() {
    const message = userInput.value.trim();
    if (message) {
        addMessage(message, 'user');
        userInput.value = '';
        
        setTimeout(() => {
            const response = getResponse(message);
            addMessage(response, 'bot');
        }, 600);
    }
}

// Add message to chat
function addMessage(text, sender) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}-message`;
    messageDiv.innerHTML = text.replace(/\n/g, '<br>');
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Get response
function getResponse(question) {
    question = question.toLowerCase().trim();
    
    // Recherche exacte d'abord
    for (const [key, value] of Object.entries(knowledgeBase)) {
        if (question === key || question.includes(key)) {
            return value;
        }
    }
    
    // Recherche par mots-clés
    if (question.includes("matière") && (question.includes("ajouter") || question.includes("créer"))) {
        return knowledgeBase["comment ajouter une matière"];
    }
    if ((question.includes("ressource") || question.includes("fichier")) && question.includes("télécharger")) {
        return knowledgeBase["comment télécharger une ressource"];
    }
    if (question.includes("favori") || question.includes("j'aime") || question.includes("like")) {
        return knowledgeBase["comment ajouter un favori"];
    }
    if (question.includes("filtrer") || question.includes("recherche")) {
        return knowledgeBase["comment filtrer les ressources"];
    }
    if (question.includes("activité") || question.includes("activité")) {
        return knowledgeBase["qu'est-ce que mes activités"];
    }
    
    // Réponse par défaut personnalisée
    return "Je ne connais pas encore cette réponse 😅. Essayez de poser votre question comme :\n• 'Comment ajouter une matière ?'\n• 'Comment télécharger une ressource ?'\n• 'Comment ajouter un favori ?'";
}

// Quick questions
function askQuestion(question) {
    userInput.value = question;
    sendMessage();
}

// Close chatbot when clicking outside
document.addEventListener('click', function(e) {
    if (chatbotOpen && !chatbotWindow.contains(e.target) && e.target !== toggleBtn) {
        chatbotOpen = false;
        chatbotWindow.classList.remove('active');
    }
});

// Handle enter key
userInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Init chatbot on page load
document.addEventListener('DOMContentLoaded', function() {
    // Pas de message initial automatique pour ne pas perturber l'utilisateur
});
</script>

</body>
</html>