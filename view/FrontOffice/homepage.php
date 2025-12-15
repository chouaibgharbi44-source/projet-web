<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Campus Connect — Votre université, unie</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --rose-700: #ff2f78;
      --rose-600: #ff4d8d;
      --rose-500: #ff5d96;
      --rose-400: #ff7cae;
      --rose-200: #ffd9e8;
      --rose-50: #fff5f9;
      --ink-900: #131216;
      --ink-700: #2f2b33;
      --ink-500: #5a5563;
      --ink-300: #a59fb0;
      --ink-100: #efedf3;
      --white: #ffffff;
      --shadow-soft: 0 10px 30px rgba(17, 12, 20, 0.08);
      --shadow-strong: 0 15px 45px rgba(255, 77, 141, 0.25);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', Arial, sans-serif;
    }

    body {
      background: radial-gradient(circle at 5% 0%, rgba(255,125,174,0.12), transparent 55%),
                  radial-gradient(circle at 90% 10%, rgba(255,213,232,0.6), transparent 45%),
                  var(--white);
      color: var(--ink-700);
      line-height: 1.6;
      min-height: 100vh;
      overflow-x: hidden;
      position: relative;
    }

    /* Animated background particles */
    .bg-particle {
      position: fixed;
      border-radius: 50%;
      pointer-events: none;
      opacity: 0.15;
      animation: float 20s infinite ease-in-out;
      z-index: 0;
    }

    .bg-particle:nth-child(1) {
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, var(--rose-400), transparent);
      top: 10%;
      left: -100px;
      animation-delay: 0s;
    }

    .bg-particle:nth-child(2) {
      width: 200px;
      height: 200px;
      background: radial-gradient(circle, var(--rose-300), transparent);
      top: 60%;
      right: -50px;
      animation-delay: 5s;
    }

    .bg-particle:nth-child(3) {
      width: 250px;
      height: 250px;
      background: radial-gradient(circle, var(--rose-200), transparent);
      bottom: 20%;
      left: 20%;
      animation-delay: 10s;
    }

    @keyframes float {
      0%, 100% { transform: translate(0, 0) scale(1); }
      25% { transform: translate(30px, -30px) scale(1.1); }
      50% { transform: translate(-20px, 20px) scale(0.9); }
      75% { transform: translate(40px, 10px) scale(1.05); }
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      position: relative;
      z-index: 1;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(120deg, var(--rose-700), var(--rose-500));
      color: var(--white);
      padding: 14px 32px;
      border-radius: 999px;
      font-weight: 600;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: var(--shadow-strong);
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
      width: 300px;
      height: 300px;
    }

    .btn:hover {
      transform: translateY(-6px) scale(1.05);
      box-shadow: 0 30px 50px rgba(255,77,141,0.35);
    }

    section {
      padding: 80px 0;
    }

    h2 {
      font-size: 2.8rem;
      margin-bottom: 1rem;
      text-align: center;
      color: var(--ink-900);
      font-weight: 700;
      opacity: 0;
      transform: translateY(40px);
      transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
    }

    h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%) scaleX(0);
      width: 80px;
      height: 4px;
      background: linear-gradient(90deg, var(--rose-700), var(--rose-500));
      border-radius: 2px;
      transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.3s;
    }

    h2.visible::after {
      transform: translateX(-50%) scaleX(1);
    }

    p.subtitle {
      font-size: 1.2rem;
      color: var(--ink-500);
      text-align: center;
      max-width: 700px;
      margin: 0 auto 3rem;
      opacity: 0;
      transform: translateY(30px);
      transition: all 1s cubic-bezier(0.4, 0, 0.2, 1) 0.2s;
    }

    .visible {
      opacity: 1 !important;
      transform: translateY(0) !important;
    }

    /* Header & Navbar */
    header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(200,100,150,0.1);
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: all 0.3s ease;
    }

    header.scrolled {
      box-shadow: 0 8px 30px rgba(0,0,0,0.08);
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
      transition: transform 0.3s ease;
    }

    .logo:hover {
      transform: scale(1.05);
    }

    .logo img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 77, 141, 0.2);
    }

    .logo:hover img {
      box-shadow: 0 6px 25px rgba(255, 77, 141, 0.4);
      transform: rotate(5deg);
    }

    .logo-text h1 {
      font-size: 1.4rem;
      font-weight: 700;
      background: linear-gradient(90deg, #7b2da8, #ff6fb1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .logo-text p {
      font-size: 0.8rem;
      color: var(--ink-500);
    }

    .nav-links {
      display: flex;
      gap: 24px;
      list-style: none;
      align-items: center;
    }

    .nav-links a {
      font-weight: 500;
      color: var(--ink-500);
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      padding: 8px 12px;
      border-radius: 8px;
    }

    .nav-links a::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%) scaleX(0);
      width: 100%;
      height: 2px;
      background: linear-gradient(90deg, var(--rose-700), var(--rose-500));
      transition: transform 0.3s ease;
    }

    .nav-links a:hover {
      color: var(--rose-600);
      background: var(--rose-50);
    }

    .nav-links a:hover::before {
      transform: translateX(-50%) scaleX(1);
    }

    .mobile-toggle {
      display: none;
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--ink-700);
      transition: transform 0.3s ease;
    }

    .mobile-toggle:hover {
      transform: scale(1.1);
    }

    /* Hero Section */
    #accueil {
      background: linear-gradient(135deg, #f0e6f6, #f5e8fa);
      text-align: center;
      padding: 140px 0 100px;
      position: relative;
      overflow: hidden;
    }

    #accueil::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -20%;
      width: 800px;
      height: 800px;
      background: radial-gradient(circle, rgba(255,111,177,0.15), transparent 70%);
      border-radius: 50%;
      animation: pulse 8s infinite ease-in-out;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.15; }
      50% { transform: scale(1.1); opacity: 0.25; }
    }

    .hero-content {
      position: relative;
      z-index: 1;
    }

    .hero-content h1 {
      font-size: 4rem;
      margin-bottom: 1rem;
      line-height: 1.2;
      color: var(--ink-900);
      opacity: 0;
      transform: translateY(50px) scale(0.95);
      animation: heroFadeIn 1.2s cubic-bezier(0.4, 0, 0.2, 1) 0.3s forwards;
      font-weight: 800;
    }

    @keyframes heroFadeIn {
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .hero-content h1 span {
      font-size: 2rem;
      font-weight: 400;
      display: block;
      color: var(--ink-700);
      margin-top: 8px;
    }

    .hero-content p {
      font-size: 1.3rem;
      max-width: 750px;
      margin: 0 auto 2.5rem;
      color: var(--ink-500);
      opacity: 0;
      transform: translateY(40px);
      animation: heroFadeIn 1.2s cubic-bezier(0.4, 0, 0.2, 1) 0.6s forwards;
    }

    .hero-buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
      opacity: 0;
      transform: translateY(30px);
      animation: heroFadeIn 1.2s cubic-bezier(0.4, 0, 0.2, 1) 0.9s forwards;
    }

    /* Section Cards */
    .section-card {
      background: var(--white);
      border-radius: 32px;
      padding: 70px;
      margin: 0 20px;
      box-shadow: var(--shadow-soft);
      border: 1px solid rgba(255,77,141,0.08);
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .section-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,111,177,0.03), transparent 70%);
      transition: transform 0.8s ease;
    }

    .section-card:hover::before {
      transform: scale(1.1);
    }

    .section-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 25px 50px rgba(17, 12, 20, 0.12);
    }

    .roles-grid,
    .cards,
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 35px;
      margin-top: 50px;
      position: relative;
      z-index: 1;
    }

    .role-card,
    .card,
    .feature-box {
      background: linear-gradient(135deg, var(--rose-50), #fff);
      padding: 2.5rem;
      border-radius: 24px;
      text-align: center;
      border: 2px solid var(--rose-200);
      box-shadow: var(--shadow-soft);
      opacity: 0;
      transform: translateY(40px) scale(0.95);
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .role-card::before,
    .card::before,
    .feature-box::before {
      content: '';
      position: absolute;
      top: -100%;
      left: -100%;
      width: 300%;
      height: 300%;
      background: radial-gradient(circle, rgba(255,111,177,0.1), transparent 40%);
      transition: all 0.8s ease;
    }

    .role-card:hover::before,
    .card:hover::before,
    .feature-box:hover::before {
      top: -50%;
      left: -50%;
    }

    .role-card:hover,
    .card:hover,
    .feature-box:hover {
      transform: translateY(-15px) scale(1.02);
      box-shadow: var(--shadow-strong);
      border-color: var(--rose-400);
    }

    .role-icon,
    .card-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--rose-700), var(--rose-500));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      color: var(--white);
      font-size: 2.2rem;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 10px 30px rgba(255, 77, 141, 0.3);
      position: relative;
      z-index: 1;
    }

    .role-card:hover .role-icon,
    .card:hover .card-icon {
      transform: scale(1.15) rotate(10deg);
      box-shadow: 0 15px 40px rgba(255, 77, 141, 0.4);
    }

    .card-icon {
      width: 70px;
      height: 70px;
      font-size: 1.8rem;
    }

    .role-card h3,
    .card h3,
    .feature-box h3 {
      font-size: 1.7rem;
      margin-bottom: 1rem;
      color: var(--ink-900);
      font-weight: 700;
      position: relative;
      z-index: 1;
    }

    .role-card p,
    .card p,
    .feature-box p {
      color: var(--ink-500);
      line-height: 1.8;
      position: relative;
      z-index: 1;
    }

    .feature-box a {
      color: var(--rose-600);
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 12px;
      transition: all 0.3s ease;
      position: relative;
      z-index: 1;
      padding: 8px 16px;
      border-radius: 8px;
    }

    .feature-box a:hover {
      color: var(--rose-700);
      background: var(--rose-50);
      gap: 12px;
    }

    /* Footer */
    footer {
      background: linear-gradient(135deg, #f5f7fa, #ffffff);
      border-top: 1px solid rgba(200,100,150,0.1);
      padding: 70px 0 30px;
      margin-top: 100px;
      position: relative;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 40px;
      margin-bottom: 40px;
    }

    .footer-col h3 {
      font-size: 1.4rem;
      margin-bottom: 1.5rem;
      color: var(--ink-900);
      font-weight: 700;
    }

    .footer-col ul {
      list-style: none;
    }

    .footer-col ul li {
      margin-bottom: 1rem;
    }

    .footer-col ul li a {
      color: var(--ink-500);
      transition: all 0.3s ease;
      display: inline-block;
    }

    .footer-col ul li a:hover {
      color: var(--rose-600);
      transform: translateX(5px);
    }

    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 1.5rem;
    }

    .social-links a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 42px;
      height: 42px;
      background: linear-gradient(135deg, var(--rose-50), #fff);
      border: 2px solid var(--rose-200);
      border-radius: 50%;
      color: var(--rose-600);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      font-size: 1.1rem;
    }

    .social-links a:hover {
      background: linear-gradient(135deg, var(--rose-600), var(--rose-500));
      color: var(--white);
      transform: translateY(-8px) rotate(10deg) scale(1.15);
      box-shadow: 0 10px 25px rgba(255, 77, 141, 0.3);
      border-color: var(--rose-600);
    }

    .copyright {
      text-align: center;
      padding-top: 30px;
      border-top: 1px solid rgba(200,100,150,0.1);
      color: var(--ink-500);
      font-size: 0.95rem;
    }

    @media (max-width: 768px) {
      .mobile-toggle {
        display: block;
      }

      .nav-links {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        flex-direction: column;
        padding: 1.5rem 0;
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        display: none;
        border-radius: 0 0 16px 16px;
      }

      .nav-links.active {
        display: flex;
        animation: slideDown 0.3s ease;
      }

      @keyframes slideDown {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .nav-links a {
        padding: 1rem 2rem;
        color: var(--ink-700);
        width: 100%;
      }

      .section-card {
        margin: 0 10px;
        padding: 40px 25px;
        border-radius: 24px;
      }

      .hero-content h1 {
        font-size: 2.8rem;
      }

      .hero-content h1 span {
        font-size: 1.5rem;
      }

      h2 {
        font-size: 2.2rem;
      }

      .roles-grid,
      .cards,
      .features-grid {
        grid-template-columns: 1fr;
        gap: 25px;
      }
    }
  </style>
</head>
<body>
  <div class="bg-particle"></div>
  <div class="bg-particle"></div>
  <div class="bg-particle"></div>

  <header id="header">
    <div class="container navbar">
      <div class="logo">
        <img src="https://placehold.co/120x120/ff6fb1/ffffff?text=CC" alt="Logo Campus Connect">
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
        <li><a href="../../gestionquizz/index.php"><i class="fas fa-award"></i> Quiz</a></li>
        <li><a href="../../gestion_messagerie2/index.php"><i class="fas fa-comments"></i> Forums</a></li>
        <li><a href="profile.php" class="buttonn">Your Profile</a></li>
      </ul>
    </div>
  </header>

  <section id="accueil">
    <div class="container">
      <div class="hero-content">
        <h1>Campus Connect<br><span>Votre université, unie</span></h1>
        <p>Une plateforme unique qui relie les étudiants, favorise le partage et la collaboration.<br>Remplacez Facebook, Google Drive et WhatsApp par une solution académique complète.</p>
        <div class="hero-buttons">
          <a href="#materiel" class="btn">Commencer Maintenant</a>
          <a href="#fonctionnement" class="btn">En savoir plus</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Pour qui ? Section -->
  <div class="container">
    <div class="pour-qui section-card">
      <h2>Pour qui est Campus Connect ?</h2>
      <p class="subtitle">Une plateforme pensée pour toute la communauté éducative.</p>
      <div class="roles-grid">
        <div class="role-card">
          <div class="role-icon"><i class="fas fa-user-graduate"></i></div>
          <h3>Étudiants</h3>
          <p>Partagez vos cours, organisez des groupes d'étude, participez à des événements et restez connecté avec vos camarades — tout en un seul endroit.</p>
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

  <!-- Fonctionnement du Site -->
  <div class="container">
    <div class="fonctionnement section-card">
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

  <!-- Aperçu des Pages -->
  <div class="container">
    <div class="aperçu section-card">
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
          <a href="../../gestion_messagerie2/index.php">Rejoindre les forums →</a>
        </div>
      </div>
    </div>
  </div>

  <footer>
    <div class="container">
      <div class="footer-grid">
        <div class="footer-col">
          <div class="logo" style="gap:10px;">
            <img src="https://placehold.co/120x120/ff6fb1/ffffff?text=CC" alt="Logo">
            <div>
              <h3>CAMPUS CONNECT</h3>
              <p style="opacity:0.9;font-size:0.9rem;">Votre université, unie</p>
            </div>
          </div>
          <p style="color:var(--ink-500);margin-top:1rem;">Une plateforme qui relie les étudiants, favorise le partage et la collaboration pour une éducation durable sans papier.</p>
        </div>
        <div class="footer-col">
          <h3>Liens Rapides</h3>
          <ul>
            <li><a href="#accueil">Accueil</a></li>
            <li><a href="../../VV13/index.php">Matériel</a></li>
            <li><a href="../../BasmaCRUD/index.php">Événements</a></li>
            <li><a href="../../gestionquizz/index.php">Quiz</a></li>
            <li><a href="../../gestion_messagerie2/index.php">Forums</a></li>
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
    // Mobile menu toggle
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');
    menuToggle.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });

    // Header scroll effect
    window.addEventListener('scroll', () => {
      document.getElementById('header').classList.toggle('scrolled', window.scrollY > 50);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          window.scrollTo({
            top: target.offsetTop - 100,
            behavior: 'smooth'
          });
          navLinks.classList.remove('active');
        }
      });
    });

    // Intersection Observer for section animations
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.querySelector('h2').classList.add('visible');
          entry.target.querySelector('p.subtitle').classList.add('visible');

          const cards = entry.target.querySelectorAll('.role-card, .card, .feature-box');
          cards.forEach((card, index) => {
            setTimeout(() => {
              card.style.opacity = '1';
              card.style.transform = 'translateY(0) scale(1)';
            }, index * 150);
          });

          // Add underline animation after title is visible
          setTimeout(() => {
            entry.target.querySelector('h2').classList.add('visible');
          }, 300);
        }
      });
    }, { threshold: 0.15 });

    document.querySelectorAll('.section-card').forEach(section => {
      observer.observe(section);
    });
  </script>
</body>
</html>