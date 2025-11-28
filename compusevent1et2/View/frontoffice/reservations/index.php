<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Campus Connect - Réservations</title>
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
            <a href="index.php" class="nav-link">Événements</a>
            <a href="index.php?entity=reservation" class="nav-link active">Réservations</a>
            <a href="#" class="nav-link">Messages</a>
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
        <h2>Réservations — Gère tes places et confirmations</h2>
    </div>
</section>

<!-- Hero Section 2 & Add Form -->
<section class="section-with-form">
    <div class="hero-text">
        <h3>Mes réservations</h3>
        <p>Gérez vos demandes de réservation — créez, modifiez ou annulez.</p>
    </div>
    <div class="filters">
        <button class="btn btn-add" onclick="document.getElementById('formModal').style.display='block';">➕ Nouvelle réservation</button>
    </div>
</section>

<!-- Modal Form -->
<div id="formModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('formModal').style.display='none';">&times;</span>
        <h3>Ajouter une réservation</h3>
        <form method="post" action="index.php?entity=reservation&action=store" onsubmit="return validateForm(this);">
            <label>Événement:<br />
                <select name="event_id" required>
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $ev): ?>
                            <option value="<?php echo $ev['id']; ?>" <?php echo isset($selectedEventId) && $selectedEventId == $ev['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($ev['title']); ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">Aucun événement</option>
                    <?php endif; ?>
                </select>
            </label><br />
            <label>Nom:<br /><input type="text" name="name" required /></label><br />
            <label>Email:<br /><input type="email" name="email" /></label><br />
            <label>Places:<br /><input type="number" name="seats" value="1" min="1" /></label><br />
            <div class="modal-actions">
                <button class="btn" type="submit">➕ Réserver</button>
            </div>
        </form>
    </div>
</div>

<!-- Shared Content Cards Section -->
<section class="shared-content">
    <h3>Réservations récentes</h3>
    <p class="section-desc">Voici toutes vos réservations — confirmées, en attente ou annulées.</p>
    <div class="cards-container">
        <?php if (!empty($reservations)) : ?>
            <?php foreach ($reservations as $r) : ?>
                <div class="content-card">
                    <div class="card-top">
                        <span class="tag"><?php echo htmlspecialchars($r['event_title'] ?? '—'); ?></span>
                        <span class="badge <?php echo $r['status']; ?>"><?php echo htmlspecialchars($r['status']); ?></span>
                    </div>
                    <h4 class="card-title"><?php echo htmlspecialchars($r['name']); ?></h4>
                    <div class="card-user">✉️ <?php echo htmlspecialchars($r['email']); ?> • <?php echo htmlspecialchars($r['seats']); ?> place(s)</div>
                    <p class="card-desc">Créée le <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($r['created_at']))); ?></p>
                    <div class="card-actions">
                        <!-- Frontend users can only view, not edit or delete -->
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="content-card">Aucune réservation trouvée.</div>
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
            <a href="index.php">Événements</a><br />
            <a href="index.php?entity=reservation">Réservations</a>
        </div>
        <div class="footer-col">
            <h4>Pages Admin</h4>
            <a href="index.php?area=admin">Admin Événements</a><br />
            <a href="index.php?entity=reservation&area=admin">Admin Réservations</a><br />
            <a href="#">Paramètres</a>
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

</body>
</html>
