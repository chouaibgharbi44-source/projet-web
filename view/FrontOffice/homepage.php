<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Campus Connect — Votre université, unie</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    /* ====== Couleurs plus foncées ====== */
    :root {
      --primary: #7e22ce;    /* violet plus foncé */
      --secondary: #be185d;  /* rose plus foncé */
      --light: #f5f3ff;
      --dark: #1e1b4b;
      --white: #ffffff;
      --gray: #9ca3af;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: var(--white);
      scroll-behavior: smooth;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: 0 auto;
    }

    .btn {
      display: inline-block;
      padding: 12px 28px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
      text-align: center;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.25);
      color: white;
    }

    .btn:hover {
      background: rgba(255, 255, 255, 0.3);
      border-color: rgba(255, 255, 255, 0.45);
    }

    section {
      padding: 80px 0;
    }

    h2 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      text-align: center;
    }

    p.subtitle {
      font-size: 1.2rem;
      opacity: 0.9;
      text-align: center;
      max-width: 700px;
      margin: 0 auto 3rem;
    }

    /* ====== Header & Navbar ====== */
    header {
      background: rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.15);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 0;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .logo img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: white;
      object-fit: cover;
    }

    .logo-text h1 {
      font-size: 1.4rem;
      font-weight: 800;
    }

    .logo-text p {
      font-size: 0.8rem;
      opacity: 0.9;
    }

    .nav-links {
      display: flex;
      gap: 24px;
      list-style: none;
    }

    .nav-links a {
      font-weight: 600;
      opacity: 0.9;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .nav-links a:hover {
      opacity: 1;
      color: white;
    }

    .mobile-toggle {
      display: none;
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: white;
    }

    /* ====== Hero Section ====== */
    #accueil {
      text-align: center;
      padding: 120px 0 80px;
    }

    .hero-content h1 {
      font-size: 3.5rem;
      margin-bottom: 1rem;
      line-height: 1.2;
    }

    .hero-content p {
      font-size: 1.4rem;
      max-width: 700px;
      margin: 0 auto 2rem;
      opacity: 0.95;
    }

    .hero-buttons {
      display: flex;
      justify-content: center;
      gap: 16px;
      flex-wrap: wrap;
    }

    /* ====== Pour qui ? Section ====== */
    .pour-qui {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(12px);
      border-radius: 24px;
      margin: 0 20px;
      padding: 60px;
      color: var(--white);
    }

    .roles-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      margin-top: 40px;
    }

    .role-card {
      background: rgba(255, 255, 255, 0.1);
      padding: 2rem;
      border-radius: 20px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .role-icon {
      width: 70px;
      height: 70px;
      background: var(--white);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      color: var(--primary);
      font-size: 2rem;
    }

    .role-card h3 {
      font-size: 1.6rem;
      margin-bottom: 1rem;
    }

    .role-card p {
      opacity: 0.9;
      line-height: 1.6;
    }

    /* ====== Fonctionnement Section ====== */
    .fonctionnement {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(12px);
      border-radius: 24px;
      margin: 0 20px;
      padding: 60px;
      color: var(--white);
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      margin-top: 40px;
    }

    .card {
      background: rgba(255, 255, 255, 0.12);
      padding: 2rem;
      border-radius: 20px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      text-align: center;
    }

    .card-icon {
      width: 60px;
      height: 60px;
      background: var(--white);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      color: var(--primary);
      font-size: 1.5rem;
      font-weight: bold;
    }

    .card h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }

    .card p {
      opacity: 0.9;
    }

    /* ====== Aperçu des Pages ====== */
    .aperçu {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(12px);
      border-radius: 24px;
      margin: 0 20px;
      padding: 60px;
      color: var(--white);
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 24px;
      margin-top: 40px;
    }

    .feature-box {
      background: rgba(255, 255, 255, 0.1);
      padding: 1.8rem;
      border-radius: 16px;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .feature-box h3 {
      margin-bottom: 1rem;
    }

    .feature-box p {
      opacity: 0.85;
      margin-bottom: 1rem;
    }

    .feature-box a {
      color: var(--light);
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      opacity: 0.9;
    }

    .feature-box a:hover {
      opacity: 1;
    }

    /* ====== Footer ====== */
    footer {
      background: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(10px);
      padding: 60px 0 30px;
      margin-top: 40px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 30px;
      margin-bottom: 30px;
    }

    .footer-col h3 {
      font-size: 1.3rem;
      margin-bottom: 1.2rem;
    }

    .footer-col ul {
      list-style: none;
    }

    .footer-col ul li {
      margin-bottom: 0.8rem;
      opacity: 0.85;
    }

    .footer-col ul li a {
      transition: opacity 0.2s;
    }

    .footer-col ul li a:hover {
      opacity: 1;
      color: white;
    }

    .social-links {
      display: flex;
      gap: 12px;
      margin-top: 1rem;
    }

    .social-links a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 50%;
      transition: background 0.3s;
    }

    .social-links a:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .copyright {
      text-align: center;
      padding-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
      opacity: 0.8;
    }

    /* ====== Responsive ====== */
    @media (max-width: 768px) {
      .mobile-toggle {
        display: block;
      }

      .nav-links {
        position: absolute;
        top: 80px;
        left: 0;
        width: 100%;
        background: rgba(30, 27, 75, 0.95);
        backdrop-filter: blur(10px);
        flex-direction: column;
        padding: 1.5rem 0;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        display: none;
        color: var(--white);
      }

      .nav-links.active {
        display: flex;
      }

      .nav-links a {
        color: white;
        padding: 0.8rem 2rem;
      }

      .fonctionnement,
      .aperçu,
      .pour-qui {
        margin: 0 10px;
        padding: 40px 20px;
      }

      .hero-content h1 {
        font-size: 2.3rem;
      }

      .hero-content p {
        font-size: 1.1rem;
      }

      h2 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>

  <!-- ====== Header ====== -->
  <header>
    <div class="container navbar">
      <div class="logo">
        <img src="https://placehold.co/120x120/7e22ce/ffffff?text=CC" alt="Logo Campus Connect">
        <div class="logo-text">
          <h1>CAMPUS CONNECT</h1>
          <p>Votre université, unie</p>
        </div>
      </div>

      <button class="mobile-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
      </button>

      <ul class="nav-links" id="navLinks">
        <li><a href="#accueil"><i class="fas fa-home"></i> Accueil</a></li>
        <li><a href="../../VV13/index.php"><i class="fas fa-book"></i> Matériel</a></li>
        <li><a href="../../BasmaCRUD/index.php"><i class="fas fa-calendar"></i> Événements</a></li>
        <li><a href="#quiz"><i class="fas fa-award"></i> Quiz</a></li>
        <li><a href="#forums"><i class="fas fa-comments"></i> Forums</a></li>
        <li><a href="/campus connect/view/Front-office/messages.php" class="nav-item">Messages</a>
        <li><a href="/campus connect/view/Front-office/group_messages.php" class="nav-item">Groupes</a>
        <li><a href="profile.php" class="button">Your Profile</a></li>
      </ul>
    </div>
  </header>

  <!-- ====== Hero Section ====== -->
  <section id="accueil">
    <div class="container">
      <div class="hero-content">
        <h1>Campus Connect<br><span style="font-size:1.8rem;font-weight:400;">Votre université, unie</span></h1>
        <p>Une plateforme unique qui relie les étudiants, favorise le partage et la collaboration.<br>Remplacez Facebook, Google Drive et WhatsApp par une solution académique complète.</p>
        <div class="hero-buttons">
          <a href="#materiel" class="btn">Commencer Maintenant</a>
          <a href="#fonctionnement" class="btn">En savoir plus</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ====== Pour qui ? Section ====== -->
  <div class="container">
    <div class="pour-qui">
      <h2>Pour qui est Campus Connect ?</h2>
      <p class="subtitle">Une plateforme pensée pour toute la communauté éducative.</p>
      <div class="roles-grid">
        <div class="role-card">
          <div class="role-icon"><i class="fas fa-user-graduate"></i></div>
          <h3>Étudiants</h3>
          <p>Partagez vos cours, organisez des groupes d’étude, participez à des événements et restez connecté avec vos camarades — tout en un seul endroit.</p>
        </div>
        <div class="role-card">
          <div class="role-icon"><i class="fas fa-chalkboard-teacher"></i></div>
          <h3>Enseignants</h3>
          <p>Diffusez vos supports pédagogiques, créez des quiz, gérez les échéances et animez la communauté académique de façon simple et efficace.</p>
        </div>
        <div class="role-card">
          <div class="role-icon"><i class="fas fa-users"></i></div>
          <h3>Parents</h3>
          <p>Suivez la progression de vos enfants, recevez les annonces importantes et restez informé des activités du campus en temps réel.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- ====== Fonctionnement du Site ====== -->
  <div class="container">
    <div class="fonctionnement">
      <h2>Comment ça marche ?</h2>
      <p class="subtitle">Campus Connect simplifie la vie étudiante en centralisant tout dans une seule plateforme.</p>
      <div class="cards">
        <div class="card">
          <div class="card-icon"><i class="fas fa-book"></i></div>
          <h3>Partage de Matériel</h3>
          <p>Téléchargez, partagez et accédez à tous les cours, notes et ressources pédagogiques en un seul endroit.</p>
        </div>
        <div class="card">
          <div class="card-icon"><i class="fas fa-calendar"></i></div>
          <h3>Événements</h3>
          <p>Créez, découvrez et rejoignez des événements campus avec des notifications automatiques.</p>
        </div>
        <div class="card">
          <div class="card-icon"><i class="fas fa-comments"></i></div>
          <h3>Forums & Discussions</h3>
          <p>Participez aux discussions, posez des questions et collaborez avec vos camarades de classe.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- ====== Aperçu des Pages ====== -->
  <div class="container">
    <div class="aperçu">
      <h2>Toutes les Fonctionnalités en Un Coup d’Œil</h2>
      <p class="subtitle">Découvrez toutes les fonctionnalités de Campus Connect et accédez directement aux différentes sections.</p>
      <div class="features-grid">
        <div class="feature-box">
          <h3>Matériel Pédagogique</h3>
          <p>Gestion complète des matières et des ressources pédagogiques. CRUD matières, organisation et partage des documents.</p>
          <a href="../../VV13/index.php">Voir les matériaux →</a>
        </div>
        <div class="feature-box">
          <h3>Événements Campus</h3>
          <p>Créez, gérez et suivez tous les événements du campus avec inscriptions et détails complets.</p>
          <a href="../../BasmaCRUD/index.php">Voir les événements →</a>
        </div>
        <div class="feature-box">
          <h3>Quiz Étudiants</h3>
          <p>Testez vos connaissances avec des quiz interactifs, questions, correction automatique et scores.</p>
          <a href="#quiz">Accéder aux quiz →</a>
        </div>
        <div class="feature-box">
          <h3>Forums de Discussion</h3>
          <p>Créez des posts, commentez, réagissez et engagez-vous dans des discussions avec vos camarades.</p>
          <a href="#forums">Rejoindre les forums →</a>
        </div>
      </div>
    </div>
  </div>

  <!-- ====== Footer ====== -->
  <footer>
    <div class="container">
      <div class="footer-grid">
        <div class="footer-col">
          <div class="logo" style="gap:10px;">
            <img src="https://placehold.co/120x120/7e22ce/ffffff?text=CC" alt="Logo">
            <div>
              <h3>CAMPUS CONNECT</h3>
              <p style="opacity:0.9;font-size:0.9rem;">Votre université, unie</p>
            </div>
          </div>
          <p style="opacity:0.85;margin-top:1rem;">Une plateforme qui relie les étudiants, favorise le partage et la collaboration pour une éducation durable sans papier.</p>
        </div>
        <div class="footer-col">
          <h3>Liens Rapides</h3>
          <ul>
            <li><a href="#accueil">Accueil</a></li>
            <li><a href="../../VV13/index.php">Matériel</a></li>
            <li><a href="../../BasmaCRUD/index.php">Événements</a></li>
            <li><a href="#quiz">Quiz</a></li>
            <li><a href="#forums">Forums</a></li>
            <li><a href="profile.php">Profile</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h3>À Propos</h3>
          <ul>
            <li><a href="#">Notre Mission</a></li>
            <li><a href="#">Durabilité</a></li>
            <li><a href="#">Intelligence Artificielle</a></li>
            <li><a href="#">Équipe</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h3>Suivez-nous</h3>
          <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
      <div class="copyright">
        &copy; 2025 Campus Connect. Tous droits réservés.
      </div>
    </div>
  </footer>

  <script>
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');

    menuToggle.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          window.scrollTo({
            top: target.offsetTop - 80,
            behavior: 'smooth'
          });
          navLinks.classList.remove('active');
        }
      });
    });
  </script>
</body>

</html>
