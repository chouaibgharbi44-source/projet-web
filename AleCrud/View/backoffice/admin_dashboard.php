<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Dashboard</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .admin-dashboard {
            padding: 40px 0;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        .dashboard-card {
            background: #f9f9f9;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            border-color: #bf41faff;
            box-shadow: 0 4px 12px rgba(246, 251, 255, 0.41);
        }
        .dashboard-card h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .dashboard-card p {
            color: #666;
            font-size: 0.95em;
            margin-bottom: 20px;
        }
        .dashboard-card a {
            display: inline-block;
            background: #ff7cae;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .dashboard-card a:hover {
            background: #ff7cae;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #ff7cae, #ff7cae);
            color: white;
            min-height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            font-size: 1.4em;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar li {
            margin: 15px 0;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .sidebar a:hover, .sidebar .active {
            background: rgba(255, 255, 255, 0.2);
        }
        .main-container {
            display: flex;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            background: white; /* 🔥 CHANGEMENT ICI : fond blanc au lieu de #120b1e */
            padding: 20px;
            overflow-y: auto;
        }

    </style>

</head>
<body>
    <!-- Conteneur principal pour aligner sidebar et content horizontalement -->
    <div class="main-container">
        <!-- Barre latérale -->
        <aside class="sidebar">
            <h2>Campus Connect</h2>
            <ul>
                <li><a href="#">Tableau de bord</a></li>
                <li><a href="#" class="active">Gestion du Matériel</a></li>
                <li><a href="#">Utilisateurs</a></li>
                <li><a href="#">Événements</a></li>
                <li><a href="#">Messagerie</a></li>
            </ul>
        </aside>

        <!-- Contenu principal -->
        <main class="content">
            <header>
                <h1>Gestion du Partage de Matériel</h1>
                <p>Interface d'administration pour consulter et gérer les documents partagés par les étudiants.</p>
            </header>

            <div class="container admin-dashboard">
                <h2 style="text-align: center; margin-bottom: 40px;">Gestion du contenu</h2>
                <div class="dashboard-grid">
                    <!-- Matières Card -->
                    <div class="dashboard-card">
                        <h3>📚 Matières</h3>
                        <p>Gérer les matières pédagogiques du système</p>
                        <a href="index.php?area=admin&action=list">Accéder</a>
                    </div>

                    <!-- Ressources Card -->
                    <div class="dashboard-card">
                        <h3>📄 Ressources</h3>
                        <p>Gérer les ressources et documents partagés</p>
                        <a href="index.php?entity=ressource&area=admin">Accéder</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>