<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Campus Connect Admin</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Shared Styles matching list.php exactly */
        :root {
            --sidebar-width: 260px;
            --primary-gradient: linear-gradient(135deg, #7b2da8 0%, #ff6fb1 100%);
            --bg-body: #f4f7fe;
            --text-main: #2b3674;
            --text-secondary: #a3aed0;
        }

        body {
            background-color: var(--bg-body);
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            color: var(--text-main);
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
            color: var(--text-main);
            margin: 0;
            font-weight: 700;
        }

        .header-title p {
            color: var(--text-secondary);
            margin: 5px 0 0;
            font-size: 14px;
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

        .stat-icon.green {
            background: rgba(0, 208, 132, 0.1);
            color: #00d084;
        }

        .stat-icon.orange {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .stat-info h3 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: var(--text-main);
        }

        .stat-info p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Layout for Charts */
        .charts-section {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .charts-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 25px;
        }

        .chart-card {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
        }

        .chart-card h3 {
            margin: 0 0 25px 0;
            font-size: 18px;
            color: var(--text-main);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-wrapper {
            position: relative;
            flex-grow: 1;
            min-height: 250px;
            width: 100%;
        }

        /* Consistent Table Styles */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        .styled-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            /* Floating rows effect */
        }

        .styled-table th {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 14px;
            text-align: left;
            padding: 0 15px 10px 15px;
            border: none;
        }

        .styled-table tr.table-row {
            background: white;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.01);
            transition: 0.2s;
        }

        .styled-table tr.table-row:hover {
            transform: scale(1.005);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .styled-table td {
            padding: 18px 15px;
            border: none;
            background: white;
            /* Needed for border-spacing gap background to show through */
            font-size: 14px;
            color: var(--text-main);
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .styled-table td:first-child {
            border-left: 1px solid #f0f0f0;
            border-radius: 12px 0 0 12px;
        }

        .styled-table td:last-child {
            border-right: 1px solid #f0f0f0;
            border-radius: 0 12px 12px 0;
        }

        .user-rank-icon {
            color: #ffc107;
            margin-right: 8px;
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
             <li class="nav-item">
                <a href="../view/BackOffice/index1.php">
                    <i class="fas fa-ticket-alt"></i> Acceuil
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?area=admin">
                    <i class="fas fa-calendar-alt"></i> Événements
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?entity=reservation&area=admin">
                    <i class="fas fa-ticket-alt"></i> Réservations
                </a>
            </li>
            <li class="nav-item active">
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
                <h1>Statistiques Avancées</h1>
                <p>Analyse complète et indicateurs de performance.</p>
            </div>

            <div class="user-profile">
                <div class="user-avatar">AD</div>
                <span>Admin User</span>
            </div>
        </header>

        <!-- KPI Cards Row -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info">
                    <h3><?php echo $totalEvents; ?></h3>
                    <p>Total Événements</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pink"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-info">
                    <h3><?php echo $totalReservations; ?></h3>
                    <p>Total Réservations</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-users-cog"></i></div>
                <div class="stat-info">
                    <h3><?php echo round($capacityStats['avg_capacity'] ?? 0); ?></h3>
                    <p>Capacité Moyenne</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-percent"></i></div>
                <div class="stat-info">
                    <h3><?php echo $occupancyStats['rate']; ?>%</h3>
                    <p>Taux de Remplissage</p>
                </div>
            </div>
        </div>

        <div class="charts-section">

            <!-- Row 1: Donuts and Pies -->
            <div class="charts-row">
                <div class="chart-card">
                    <h3><i class="fas fa-tags" style="color:#7b2da8;"></i> Événements par Catégorie</h3>
                    <div class="chart-wrapper">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3><i class="fas fa-check-double" style="color:#00d084;"></i> Statut des Réservations</h3>
                    <div class="chart-wrapper">
                        <canvas id="reservationsStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Row 2: Bars and Timeline -->
            <div class="charts-row">
                <div class="chart-card">
                    <h3><i class="fas fa-fire" style="color:#ff6fb1;"></i> Top 5 Événements (Réservations)</h3>
                    <div class="chart-wrapper">
                        <canvas id="reservationsChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3><i class="fas fa-calendar-week" style="color:#4318ff;"></i> Chronologie des Événements</h3>
                    <div class="chart-wrapper">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Row 3: Complex Data -->
            <div class="charts-row">
                <!-- Day of Week Radar -->
                <div class="chart-card">
                    <h3><i class="fas fa-calendar-day" style="color:#4318ff;"></i> Jours de Pic</h3>
                    <div class="chart-wrapper">
                        <canvas id="dayOfWeekChart"></canvas>
                    </div>
                    <p style="font-size:12px; color:#aaa; margin-top:10px; text-align:center;">Distribution hebdomadaire
                    </p>
                </div>

                <!-- Leaderboard Table -->
                <div class="chart-card">
                    <h3><i class="fas fa-crown" style="color:#ffc107;"></i> Top Utilisateurs Actifs</h3>
                    <div class="table-container">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>Rang</th>
                                    <th>Utilisateur</th>
                                    <th>Email</th>
                                    <th style="text-align:right;">Réservations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topUsers)): ?>
                                    <?php $i = 1;
                                    foreach ($topUsers as $user): ?>
                                        <tr class="table-row">
                                            <td>
                                                <?php if ($i == 1): ?><i class="fas fa-trophy user-rank-icon"></i>
                                                <?php else: ?>#<?php echo $i; endif; ?>
                                            </td>
                                            <td><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
                                            <td style="color:#888;"><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td style="text-align:right; font-weight:700; color:#7b2da8;">
                                                <?php echo $user['count']; ?></td>
                                        </tr>
                                        <?php $i++; endforeach; ?>
                                <?php else: ?>
                                    <tr class="table-row">
                                        <td colspan="4" style="text-align:center;">Aucune donnée.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        // Data from PHP
        const categoryData = <?php echo json_encode($eventsByCategory); ?>;
        const reservationData = <?php echo json_encode($reservationsByEvent); ?>;
        const resStatusData = <?php echo json_encode($reservationStatusDistribution); ?>;
        const timelineData = <?php echo json_encode($eventsByMonth); ?>;
        const dayData = <?php echo json_encode($reservationsByDay); ?>;

        // Tooltip Callback for percentage
        const percentageTooltip = {
            callbacks: {
                label: function (context) {
                    let label = context.label || '';
                    if (label) label += ': ';
                    const value = context.raw;
                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    const percentage = Math.round((value / total) * 100) + '%';
                    return label + value + ' (' + percentage + ')';
                }
            }
        };

        // 1. Categories (Doughnut)
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: categoryData.map(i => i.category),
                datasets: [{
                    data: categoryData.map(i => i.count),
                    backgroundColor: ['#7b2da8', '#ff6fb1', '#4318ff', '#00d084'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: percentageTooltip
                },
                layout: { padding: 10 }
            }
        });

        // 2. Reservation Status (Pie)
        new Chart(document.getElementById('reservationsStatusChart'), {
            type: 'pie',
            data: {
                labels: resStatusData.map(i => i.status),
                datasets: [{
                    data: resStatusData.map(i => i.count),
                    backgroundColor: ['#00d084', '#ffc107', '#eb144c', '#555'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: percentageTooltip
                },
                layout: { padding: 10 }
            }
        });

        // 3. Top Events (Bar)
        new Chart(document.getElementById('reservationsChart'), {
            type: 'bar',
            data: {
                labels: reservationData.map(i => i.title.length > 20 ? i.title.substring(0, 20) + '...' : i.title),
                datasets: [{
                    label: 'Nombre de réservations',
                    data: reservationData.map(i => i.count),
                    backgroundColor: '#ff6fb1',
                    borderRadius: 8,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // 4. Timeline (Line)
        new Chart(document.getElementById('timelineChart'), {
            type: 'line',
            data: {
                labels: timelineData.map(i => i.month),
                datasets: [{
                    label: 'Événements Créés',
                    data: timelineData.map(i => i.count),
                    borderColor: '#4318ff',
                    backgroundColor: 'rgba(67, 24, 255, 0.05)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4318ff',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' },
                        ticks: { stepSize: 1 }
                    },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // 5. Day of Week (Polar Area)
        new Chart(document.getElementById('dayOfWeekChart'), {
            type: 'polarArea',
            data: {
                labels: dayData.map(i => i.day),
                datasets: [{
                    data: dayData.map(i => i.count),
                    backgroundColor: [
                        'rgba(67, 24, 255, 0.7)',
                        'rgba(123, 45, 168, 0.7)',
                        'rgba(255, 111, 177, 0.7)',
                        'rgba(0, 208, 132, 0.7)',
                        'rgba(255, 193, 7, 0.7)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: { ticks: { display: false }, grid: { color: '#f0f0f0' } }
                },
                plugins: { legend: { position: 'right', display: false } }
            }
        });

    </script>
</body>

</html>