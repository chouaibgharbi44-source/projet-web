<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
        <title>Campus Connect - Ressources Pédagogiques</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <link rel="stylesheet" type="text/css" href="View/assets/frontoffice.css?v=3" />
    <script type="text/javascript" src="View/assets/validation.js?v=3"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- === Custom Animations (added inline - no change to external CSS) === -->
    <style>
        /* Fade-in and slide-up on scroll */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Staggered card entrance */
        .content-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .content-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Enhanced hover for cards */
        .content-card:hover {
            transform: translateY(-12px) !important;
            box-shadow: 0 20px 40px rgba(255, 77, 141, 0.2);
        }

        /* Section titles and text */
        .hero-content h2 {
    opacity: 1;
    transform: translateY(0);
        }
        .hero-text h3,
        .shared-content h3,
        .section-desc {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.9s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.9s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Button enhancements */
        .btn-add,
        .card-link,
        .modal-actions .btn {
            transition: all 0.4s ease;
        }

        .btn-add:hover,
        .card-link:hover,
        .modal-actions .btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 30px rgba(255, 77, 141, 0.3);
        }

        /* Modal fade-in */
        .modal {
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease;
        }

        .modal.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            transform: scale(0.9);
            transition: transform 0.4s ease;
        }

        .modal.show .modal-content {
            transform: scale(1);
        }
    </style>
</head>
<body>

<!-- Header & Navigation -->
<header class="header">
    <div class="header-inner">
        <div class="logo">CAMPUS CONNECT</div>
        <nav class="navbar">
            <a href="../view/FrontOffice/homepage.php" class="nav-link active">acceuil</a>
            <a href="index.php" class="nav-link active">Matières</a>
            <a href="index.php?entity=ressource" class="nav-link">Ressources</a>
            <a href="../BasmaCRUD/index.php" class="nav-link">Événements</a>
            <a href="#" class="nav-link">Messages</a>
            <a href="#" class="nav-link">Groupes</a>
            <a href="../view/FrontOffice/profile.php" class="nav-link">Profile</a>
        </nav>
    </div>
</header>

<!-- Hero Section 1 -->
<section class="hero-section animate-on-scroll">
    <div class="hero-content">
        <h2>Ressources pédagogiques partagées par la communauté</h2>
    </div>
</section>

<!-- Hero Section 2 & Add Form -->
<section class="section-with-form">
    <div class="hero-text animate-on-scroll">
        <h3>Matières disponibles</h3>
        <p>Ajoutez vos matières puis reliez des ressources pour chaque contenu.</p>
    </div>
    <button class="btn btn-add animate-on-scroll" onclick="openModal()">➕ Ajouter une matière</button>
</section>

<!-- Modal Form -->
<div id="formModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3>Ajouter une matière</h3>
        <form method="post" action="index.php?action=store" onsubmit="return validateForm(this);">
            <label>Nom matière:<br /><input type="text" name="nom_matiere" placeholder="Ex: Mathématiques" /></label><br />
            <label>Titre:<br /><input type="text" name="titre" placeholder="Ex: Algèbre de base" /></label><br />
            <label>Description:<br /><textarea name="description" rows="4" placeholder="Décrivez la matière..."></textarea></label><br />
            <label>Date d'ajout:<br /><input type="text" name="date_ajout" value="<?php echo date('Y-m-d H:i:s'); ?>" /></label><br />
            <label>Niveau difficulté:<br /><input type="text" name="niveau_difficulte" placeholder="Ex: Facile, Moyen, Difficile" /></label><br />
            <div class="modal-actions">
                <button class="btn" type="submit">➕ Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- Shared Content Cards Section -->
<section class="shared-content">
    <h3 class="animate-on-scroll">Liste des matières</h3>
    <p class="section-desc animate-on-scroll">Découvrez les matières disponibles et visualisez les ressources associées.</p>
    <div class="cards-container">
        <?php if (!empty($matieres)) : ?>
            <?php foreach ($matieres as $index => $matiere) : ?>
                <div class="content-card" style="transition-delay: <?php echo $index * 0.1; ?>s;">
                    <div class="card-user">Matière #<?php echo htmlspecialchars($matiere['id']); ?></div>
                    <h4 class="card-title"><?php echo htmlspecialchars($matiere['nom_matiere']); ?></h4>
                    <p class="card-desc"><?php echo nl2br(htmlspecialchars($matiere['description'])); ?></p>
                    <p class="card-meta"><strong>Niveau :</strong> <?php echo htmlspecialchars($matiere['niveau_difficulte'] ?: '—'); ?></p>
                    <div class="card-actions">
                        <a href="index.php?entity=ressource&amp;matiere_id=<?php echo $matiere['id']; ?>" class="card-link">Voir les ressources</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="animate-on-scroll">Aucune matière pour l'instant. Ajoutez-en une via le bouton ci-dessus.</p>
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
            <a href="../view/FrontOffice/profile.php">Accueil</a><br />
            <a href="#">Matériel</a><br />
            <a href="#">Événements</a>
        </div>
        <div class="footer-col">
            <h4>Pages</h4>
            <a href="#">Messages</a><br />
            <a href="#">Groupes</a><br />
            <a href="../view/FrontOffice/profile.php">Profile</a>
        </div>
    </div>
    <div class="footer-copy">© 2025 - Campus Connect. Tous droits réservés.</div>
</footer>

<script>
    // Improved modal functions
    function openModal() {
        document.getElementById('formModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('formModal').classList.remove('show');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('formModal');
        if (event.target === modal) {
            closeModal();
        }
    }

    // Scroll animation trigger
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');

                // Special handling for cards
                if (entry.target.classList.contains('cards-container')) {
                    entry.target.querySelectorAll('.content-card').forEach(card => {
                        card.classList.add('visible');
                    });
                }
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    // Observe elements
    document.querySelectorAll('.animate-on-scroll, .content-card, .cards-container').forEach(el => {
        observer.observe(el);
    });
</script>

</body>
</html>