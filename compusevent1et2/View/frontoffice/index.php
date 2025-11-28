<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Campus Connect - Événements</title>
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
            <a href="#" class="nav-link">Accueil</a>
            <a href="#" class="nav-link active">Événements</a>
            <a href="#" class="nav-link">Evenements</a>
            <a href="#" class="nav-link">Messages</a>
            <a href="index.php?entity=reservation" class="nav-link">Réservations</a>
            <a href="#" class="nav-link">Groupes</a>
            <a href="#" class="nav-link">Profil</a>
        </nav>
        <div class="admin-button">
            <a href="index.php?area=admin" class="pulse">Admin</a>
        </div>
    </div>
</header>

<!-- Hero Section 1 -->
<section class="hero-section">
    <div class="hero-content">
        <h2>Campus Events — Trouvez, partagez, participez</h2>
    </div>
</section>

<!-- Hero Section 2 & Add Form -->
<section class="section-with-form">
    <div class="hero-text">
        <h3>Calendrier des événements</h3>
        <p>Explorez, triez et rejoignez les événements organisés par la communauté.</p>
    </div>
    <div class="filters">
        <input id="searchInput" class="search" placeholder="Rechercher par titre ou lieu..." />
        <select id="categoryFilter">
            <option value="">Toutes catégories</option>
            <option>Conférence</option>
            <option>Atelier</option>
            <option>Autre</option>
        </select>
        <button class="btn btn-add" onclick="document.getElementById('formModal').style.display='block';">➕ Ajouter un événement</button>
    </div>
</section>

<!-- Modal Form -->
<div id="formModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('formModal').style.display='none';">&times;</span>
        <h3>Ajouter un événement</h3>
        <form method="post" action="index.php?action=store" onsubmit="return validateForm(this);">
            <label>Titre:<br /><input type="text" name="title" placeholder="Titre de l'événement" required /></label><br />
            <label>Description:<br /><textarea name="description" rows="4" placeholder="Décrivez l'événement..." required></textarea></label><br />
            <label>Date & Heure (YYYY-MM-DD HH:MM):<br /><input type="text" name="date" placeholder="2025-12-15 14:00" value="<?php echo date('Y-m-d H:i'); ?>" required /></label><br />
            <label>Lieu:<br /><input type="text" name="location" placeholder="Salle, Amphithéâtre..." /></label><br />
            <label>Capacité:<br /><input type="number" name="capacity" placeholder="Ex: 100" /></label><br />
            <label>Catégorie:<br /><input type="text" name="category" value="Autre" /></label><br />
            <input type="hidden" name="created_by" value="1" />
            <div class="modal-actions">
                <button class="btn" type="submit">➕ Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- Shared Content Cards Section -->
<section class="shared-content">
    <h3>Événements à venir</h3>
    <p class="section-desc">Rejoignez les événements qui vous intéressent — conférences, ateliers, rencontres.</p>
    <div class="cards-container">
        <?php if (!empty($evenements)) : ?>
            <?php foreach ($evenements as $e) : ?>
                <div class="content-card">
                    <div class="card-top">
                        <span class="tag"><?php echo htmlspecialchars($e['category']); ?></span>
                        <span class="badge <?php echo $e['status']; ?>"><?php echo htmlspecialchars($e['status']); ?></span>
                    </div>
                    <h4 class="card-title"><?php echo htmlspecialchars($e['title']); ?></h4>
                    <div class="card-user">📍 <?php echo htmlspecialchars($e['location']); ?> • <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($e['date']))); ?></div>
                    <p class="card-desc"><?php echo nl2br(htmlspecialchars($e['description'])); ?></p>
                    <div class="card-actions">
                        <a href="#" class="card-link">Voir</a>
                        <a class="btn-comment" href="index.php?entity=reservation&action=add&event_id=<?php echo $e['id']; ?>">S'inscrire</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="content-card">Aucun événement prévu.</div>
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
            <a href="#">Accueil</a><br />
            <a href="#">Matériel</a><br />
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

// Basic client-side filtering of event cards
document.addEventListener('DOMContentLoaded', function() {
    var search = document.getElementById('searchInput');
    var category = document.getElementById('categoryFilter');
    var cards = document.querySelectorAll('.cards-container .content-card');

    function filterCards() {
        var q = search.value.trim().toLowerCase();
        var cat = category.value;
        cards.forEach(function(card) {
            var title = (card.querySelector('.card-title') || { innerText: '' }).innerText.toLowerCase();
            var loc = (card.querySelector('.card-user') || { innerText: '' }).innerText.toLowerCase();
            var tag = (card.querySelector('.tag') || { innerText: '' }).innerText;
            var show = true;
            if (q && title.indexOf(q) === -1 && loc.indexOf(q) === -1) show = false;
            if (cat && tag.indexOf(cat) === -1) show = false;
            card.style.display = show ? '' : 'none';
        });
    }

    if (search) search.addEventListener('input', filterCards);
    if (category) category.addEventListener('change', filterCards);
});
</script>

</body>
</html>
