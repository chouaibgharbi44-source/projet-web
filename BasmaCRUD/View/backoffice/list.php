<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <style>
        /* Dashboard Specific Internal Styles to ensure they load */

        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
            --primary-gradient: linear-gradient(135deg, #7b2da8 0%, #ff6fb1 100%);
            --bg-body: #f4f7fe;
        }

        body {
            background-color: var(--bg-body);
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: white;
            position: fixed;
            left: 0;
            top: 0;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 40px;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            z-index: 100;
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 15px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            color: #888;
            text-decoration: none;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .nav-item a:hover,
        .nav-item.active a {
            background: rgba(123, 45, 168, 0.08);
            color: #7b2da8;
            font-weight: 600;
        }

        .nav-item a i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
        }

        /* Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header-title h1 {
            font-size: 28px;
            color: #2b3674;
            margin: 0;
            font-weight: 700;
        }

        .header-title p {
            color: #aaa;
            margin: 5px 0 0;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            background: white;
            padding: 10px 20px;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7b2da8;
            font-weight: bold;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.purple {
            background: rgba(123, 45, 168, 0.1);
            color: #7b2da8;
        }

        .stat-icon.pink {
            background: rgba(255, 111, 177, 0.1);
            color: #ff6fb1;
        }

        .stat-icon.blue {
            background: rgba(11, 37, 69, 0.1);
            color: #0b2545;
        }

        .stat-info h3 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: #2b3674;
        }

        .stat-info p {
            margin: 0;
            color: #a3aed0;
            font-size: 14px;
        }

        /* Table Section */
        .recent-activity {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .btn-new {
            background: var(--primary-gradient);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 5px 15px rgba(123, 45, 168, 0.2);
            transition: 0.3s;
        }

        .btn-new:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(123, 45, 168, 0.3);
        }

        /* Table Styling Overrides */
        .event-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .event-table th {
            color: #a3aed0;
            font-weight: 500;
            font-size: 14px;
            text-align: left;
            padding: 15px;
            background: transparent !important;
            /* Override default gradient */
            border: none;
        }

        .event-table tr {
            background: white;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.01);
            transition: 0.2s;
        }

        .event-table td {
            padding: 18px 15px;
            border: none;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            color: #2b3674;
            font-weight: 500;
        }

        .event-table tr td:first-child {
            border-left: 1px solid #f0f0f0;
            border-radius: 12px 0 0 12px;
        }

        .event-table tr td:last-child {
            border-right: 1px solid #f0f0f0;
            border-radius: 0 12px 12px 0;
        }

        .event-table tr:hover {
            transform: scale(1.005);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        /* Actions */
        .action-btn {
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            margin-right: 5px;
        }

        .action-btn.edit {
            background: rgba(123, 45, 168, 0.1);
            color: #7b2da8;
        }

        .action-btn.delete {
            background: rgba(255, 60, 100, 0.1);
            color: #e14d5a;
        }

        .action-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-layer-group"></i> Campus Connect
        </div>
        <ul class="nav-menu">
            <li class="nav-item active">
                <a href="index.php?area=admin">
                    <i class="fas fa-calendar-alt"></i> Événements
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?entity=reservation&area=admin">
                    <i class="fas fa-ticket-alt"></i> Réservations
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?entity=stats&area=admin">
                    <i class="fas fa-chart-pie"></i> Statistiques
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php">
                    <i class="fas fa-desktop"></i> Frontoffice
                </a>
            </li>
            <li class="nav-item" style="margin-top:auto">
                <a href="index.php?admin=logout" style="color:#e14d5a;">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Header -->
        <header class="dashboard-header">
            <div class="header-title">
                <h1>Dashboard</h1>
                <p>Bienvenue dans votre espace d'administration</p>
            </div>

            <div class="user-profile">
                <div class="user-avatar">AD</div>
                <span>Admin User</span>
            </div>
        </header>

        <!-- Stats Row (Calculated or Mock) -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info">
                    <h3><?php echo isset($evenements) ? count($evenements) : 0; ?></h3>
                    <p>Événements Total</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pink"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3>24</h3>
                    <p>Nouveaux Inscrits</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-chart-line"></i></div>
                <div class="stat-info">
                    <h3>+12%</h3>
                    <p>Croissance</p>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="recent-activity">
            <div class="section-header">
                <h2 style="margin:0; font-size:20px; color:#2b3674;">Liste des Événements</h2>
                <a class="btn-new" href="index.php?action=add&area=admin">
                    <i class="fas fa-plus"></i> Nouveau
                </a>
            </div>

            <table class="event-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Événement</th>
                        <th>Date & Lieu</th>
                        <th>Capacité</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($evenements)): ?>
                        <?php foreach ($evenements as $e): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($e['id']); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($e['title']); ?></strong><br>
                                    <span
                                        style="font-size:12px; color:#aaa;"><?php echo substr(htmlspecialchars($e['description']), 0, 30); ?>...</span>
                                </td>
                                <td>
                                    <i class="far fa-clock" style="color:#ff6fb1"></i>
                                    <?php echo date('d M, Y', strtotime($e['date'])); ?><br>
                                    <span
                                        style="font-size:12px; color:#aaa;"><?php echo htmlspecialchars($e['location']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($e['capacity']); ?></td>
                                <td><span class="tag"><?php echo htmlspecialchars($e['category']); ?></span></td>
                                <td>
                                    <?php
                                    $statusClass = 'pending';
                                    if (strtolower($e['status']) == 'approved')
                                        $statusClass = 'approved';
                                    if (strtolower($e['status']) == 'rejected')
                                        $statusClass = 'rejected';
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($e['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a class="action-btn edit"
                                        href="index.php?action=edit&id=<?php echo $e['id']; ?>&area=admin"><i
                                            class="fas fa-edit"></i></a>
                                    <a class="action-btn delete"
                                        href="index.php?action=delete&id=<?php echo $e['id']; ?>&area=admin"
                                        onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding:30px;">Aucun événement trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>

</html>