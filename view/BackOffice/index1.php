<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin — Campus Connect</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    :root {
      --primary: #7e22ce;
      --secondary: #be185d;
      --light: #f5f3ff;
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
      display: flex;
      min-height: 100vh;
    }

    /* ====== Sidebar (Navbar verticale) ====== */
    .sidebar {
      width: 260px;
      background: rgba(0, 0, 0, 0.25);
      backdrop-filter: blur(12px);
      border-right: 1px solid rgba(255, 255, 255, 0.15);
      padding: 24px 0;
      display: flex;
      flex-direction: column;
      position: fixed;
      height: 100vh;
      z-index: 100;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 0 24px 28px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: white;
      object-fit: cover;
    }

    .logo h1 {
      font-size: 1.3rem;
      font-weight: 800;
    }

    .logo p {
      font-size: 0.8rem;
      opacity: 0.85;
    }

    .nav-links {
      list-style: none;
      margin-top: 30px;
      padding: 0 12px;
    }

    .nav-links li {
      margin-bottom: 8px;
    }

    .nav-links a {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 14px 20px;
      border-radius: 14px;
      color: white;
      opacity: 0.9;
      transition: all 0.25s ease;
    }

    .nav-links a:hover, .nav-links a.active {
      background: rgba(255, 255, 255, 0.2);
      opacity: 1;
    }

    .nav-links i {
      width: 24px;
      text-align: center;
      font-size: 1.1rem;
    }

    /* ====== Main Content ====== */
    .main-content {
      flex: 1;
      margin-left: 260px;
      padding: 40px;
    }

    .header {
      margin-bottom: 30px;
    }

    .header h1 {
      font-size: 2.2rem;
      margin-bottom: 12px;
    }

    .header p {
      opacity: 0.9;
      font-size: 1.05rem;
      max-width: 700px;
      line-height: 1.6;
    }

    .admin-info {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      padding: 28px;
      margin-bottom: 32px;
    }

    .admin-info h2 {
      font-size: 1.5rem;
      margin-bottom: 16px;
    }

    .admin-info p {
      margin-bottom: 12px;
      line-height: 1.6;
    }

    .modules-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 24px;
    }

    .module-card {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 18px;
      padding: 22px;
    }

    .module-card h3 {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 12px;
      font-size: 1.25rem;
    }

    .module-card p {
      opacity: 0.85;
      font-size: 0.95rem;
      line-height: 1.5;
    }

    .module-card .icon {
      width: 36px;
      height: 36px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        width: 80px;
      }
      .sidebar .logo span, .sidebar .logo p, .sidebar .nav-links span {
        display: none;
      }
      .sidebar .logo {
        justify-content: center;
        gap: 0;
        padding: 20px 0 24px;
        border: none;
      }
      .sidebar .logo img {
        margin: 0 auto;
      }
      .nav-links a {
        justify-content: center;
        padding: 16px;
      }
      .nav-links i {
        margin: 0;
      }
      .main-content {
        margin-left: 80px;
        padding: 24px 16px;
      }
    }
  </style>
</head>
<body>

  <!-- ====== Sidebar (Navbar verticale) ====== -->
  <aside class="sidebar">
    <div class="logo">
      <img src="https://placehold.co/120x120/7e22ce/ffffff?text=CC" alt="Logo Campus Connect">
      <div>
        <h1>CAMPUS CONNECT</h1>
        <p>Admin Panel</p>
      </div>
    </div>

    <ul class="nav-links">
      <li><a href="index1.php" class="active"><i class="fas fa-home"></i> <span>Accueil</span></a></li>
      <li><a href="../../VV13/index.php?area=admin"><i class="fas fa-book"></i> <span>Matériel</span></a></li>
      <li><a href="../../BasmaCRUD/index.php?area=admin"><i class="fas fa-book"></i> <span>events</span></a></li>
      <li><a href="../../gestionquizz/admin/"><i class="fas fa-award"></i> <span>Quiz</span></a></li>
      <li><a href="../../gestion_messagerie2/view/Back-office/admin.php?key=admin123"><i class="fas fa-comments"></i> <span>Forums</span></a></li>
      <li><a href="index.php"><i class="fas fa-user"></i> <span>gestion utilisateurs</span></a></li>
    </ul>
  </aside>

  <!-- ====== Contenu Principal ====== -->
  <main class="main-content">
    <div class="header">
      <h1>Espace d’administration</h1>
      <p>Bienvenue dans le back-office de Campus Connect. Ici, vous supervisez, modérez et assurez le bon fonctionnement de la plateforme éducative pour toute la communauté universitaire.</p>
    </div>

   

  <script>
    document.querySelectorAll('.nav-links a').forEach(link => {
      link.addEventListener('click', function() {
        document.querySelectorAll('.nav-links a').forEach(el => el.classList.remove('active'));
        this.classList.add('active');
      });
    });
  </script>
</body>
</html>
