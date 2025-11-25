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
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0,123,255,0.2);
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
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .dashboard-card a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="topbar admin">
    <h1>Backoffice - Dashboard</h1>
    <div class="admin-button"><a href="index.php">Frontoffice</a></div>
</div>

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

</body>
</html>
