<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Campus Connect - Modifier Réservation</title>
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
        <h2>Modifier Ma Réservation</h2>
    </div>
</section>

<!-- Form Section -->
<section class="section-with-form">
    <div class="hero-text">
        <h3>Mise à jour</h3>
        <p>Modifiez les détails de votre réservation ci-dessous.</p>
    </div>
</section>

<div class="container">
    <div class="form-card">
        <form method="post" action="index.php?entity=reservation&action=update" onsubmit="return validateForm(this);">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($reservation['id']); ?>" />
            <label>Événement:<br />
                <select name="event_id" required>
                    <?php foreach ($events as $ev): ?>
                        <option value="<?php echo $ev['id']; ?>" <?php echo $ev['id']==$reservation['event_id']?'selected':''; ?>><?php echo htmlspecialchars($ev['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label><br />

            <label>Nom:<br /><input type="text" name="name" value="<?php echo htmlspecialchars($reservation['name']); ?>" required /></label><br />
            <label>Email:<br /><input type="email" name="email" value="<?php echo htmlspecialchars($reservation['email']); ?>" /></label><br />
            <label>Places:<br /><input type="number" name="seats" value="<?php echo htmlspecialchars($reservation['seats']); ?>" min="1" /></label><br />

            <input class="btn" type="submit" value="Mettre à jour" />
            <a href="index.php?entity=reservation" class="btn" style="text-decoration: none; display: inline-block;">Annuler</a>
        </form>
    </div>
</div>

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

</body>
</html>
