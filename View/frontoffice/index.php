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
            <a href="#" class="nav-link">Accueil</a>
            <a href="#" class="nav-link active">Ressources</a>
            <a href="#" class="nav-link">Evenements</a>
            <a href="#" class="nav-link">Messages</a>
            <a href="#" class="nav-link">Groupes</a>
            <a href="#" class="nav-link">Profil</a>
        </nav>
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
        <h3>Tout Les Contenus Partagés</h3>
        <p>Ressources pédagogiques partagées par la communauté</p>
    </div>
    <button class="btn btn-add" onclick="document.getElementById('formModal').style.display='block';">➕ Ajouter une matière</button>
</section>

<!-- Modal Form -->
<div id="formModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="document.getElementById('formModal').style.display='none';">&times;</span>
        <h3>Ajouter une matière</h3>
        <form method="post" action="index.php?action=store" onsubmit="return validateForm(this);">
            <label>Nom matière:<br /><input type="text" name="nom_matiere" placeholder="Ex: Mathématiques" /></label><br />
            <label>Titre:<br /><input type="text" name="titre" placeholder="Ex: Algèbre de base" /></label><br />
            <label>Description:<br /><textarea name="description" rows="4" placeholder="Décrivez la matière..."></textarea></label><br />
            <label>Date d'ajout:<br /><input type="text" name="date_ajout" value="<?php echo date('Y-m-d H:i:s'); ?>" /></label><br />
            <label>Niveau difficulté:<br /><input type="text" name="niveau_difficulte" placeholder="Ex: Facile, Moyen, Difficile" /></label><br />
            <input class="btn" type="submit" value="Enregistrer" />
        </form>
    </div>
</div>

<!-- Shared Content Cards Section -->
<section class="shared-content">
    <h3>Tout Les Contenus Partagés</h3>
    <p class="section-desc">Ressources pédagogiques partagées par la communauté</p>
    <div class="cards-container">
        <!-- Card 1 -->
        <div class="content-card">
            <div class="card-user">👤 Utilisateur : Sarah B.</div>
            <h4 class="card-title">Cours : Introduction à la Programmation</h4>
            <p class="card-desc">Ce document présente les bases de la programmation en C avec des exemples simples.</p>
            <div class="card-actions">
                <a href="#" class="card-link">📄 Voir le document</a>
                <button class="btn-comment">Commenter</button>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="content-card">
            <div class="card-user">👤 Utilisateur : Karim M.</div>
            <h4 class="card-title">Cours : Mathématiques - Algèbre</h4>
            <p class="card-desc">Résumé complet du chapitre sur les équations et inéquations du second degré.</p>
            <div class="card-actions">
                <a href="#" class="card-link">📄 Voir le document</a>
                <button class="btn-comment">Commenter</button>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="content-card">
            <div class="card-user">👤 Utilisateur : Leila T.</div>
            <h4 class="card-title">Cours : Web - HTML & CSS</h4>
            <p class="card-desc">Un support clair et bien structuré pour apprendre à créer des pages web simples.</p>
            <div class="card-actions">
                <a href="#" class="card-link">📄 Voir le document</a>
                <button class="btn-comment">Commenter</button>
            </div>
        </div>
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
</script>

</body>
</html>

git