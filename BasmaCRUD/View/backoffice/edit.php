<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Événement - Campus Connect Admin</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <script type="text/javascript" src="View/assets/controle_saisie.js"></script>
    <style>
        /* Dashboard Specific Internal Styles */
        :root {
            --sidebar-width: 260px;
            --primary-gradient: linear-gradient(135deg, #7b2da8 0%, #ff6fb1 100%);
            --bg-body: #f4f7fe;
        }

        body {
            background-color: var(--bg-body);
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
            margin: 0;
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

        .header-title-page h1 {
            font-size: 24px;
            color: #2b3674;
            margin: 0 0 10px 0;
            font-weight: 700;
        }

        .form-container-clean {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            max-width: 900px;
            margin: 0 auto;
        }

        .form-row-cs {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
        }

        .form-group-cs {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group-cs label {
            font-weight: 600;
            color: #2b3674;
            font-size: 14px;
        }

        .form-input-cs {
            padding: 14px;
            border: 1px solid #e0e5f2;
            border-radius: 12px;
            background: #fdfaff;
            font-size: 14px;
            color: #333;
            outline: none;
            transition: all 0.3s;
        }

        .form-input-cs:focus {
            border-color: #7b2da8;
            box-shadow: 0 0 0 4px rgba(123, 45, 168, 0.1);
            background: #fff;
        }

        textarea.form-input-cs {
            resize: vertical;
            min-height: 120px;
        }

        .btn-submit-cs {
            background: var(--primary-gradient);
            color: white;
            padding: 16px 32px;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 10px 20px rgba(123, 45, 168, 0.2);
            align-self: flex-end;
            margin-top: 10px;
        }

        .btn-submit-cs:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(123, 45, 168, 0.3);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #888;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #7b2da8;
        }

        .error-msg {
            margin-top: 4px;
            color: #e14d5a;
            font-size: 12px;
            display: none;
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

        <a href="index.php?area=admin" class="back-link"><i class="fas fa-arrow-left"></i> Retour à la liste</a>

        <div class="header-title-page">
            <h1>Modifier Événement</h1>
        </div>

        <div class="form-container-clean">
            <form method="post" action="index.php?action=update&area=admin" onsubmit="return validateForm(this);"
                novalidate>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($evenement['id']); ?>" />

                <div class="form-row-cs">
                    <div class="form-group-cs">
                        <label for="edit_title">Titre de l'événement</label>
                        <input type="text" name="title" id="edit_title" class="form-input-cs"
                            value="<?php echo htmlspecialchars($evenement['title']); ?>" required />
                    </div>
                    <div class="form-group-cs">
                        <label for="edit_category">Catégorie</label>
                        <input type="text" name="category" id="edit_category" class="form-input-cs"
                            value="<?php echo htmlspecialchars($evenement['category']); ?>" />
                    </div>
                </div>

                <div class="form-group-cs" style="margin-bottom:20px;">
                    <label for="edit_description">Description</label>
                    <textarea name="description" id="edit_description"
                        class="form-input-cs"><?php echo htmlspecialchars($evenement['description']); ?></textarea>
                </div>

                <div class="form-row-cs">
                    <div class="form-group-cs">
                        <label for="edit_date">Date & Heure</label>
                        <!-- Converting date to datetime-local format if possible, otherwise text -->
                        <input type="text" name="date" id="edit_date" class="form-input-cs"
                            value="<?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($evenement['date']))); ?>" />
                        <span style="font-size:12px; color:#888;">Format: YYYY-MM-DD HH:MM</span>
                    </div>
                    <div class="form-group-cs">
                        <label for="edit_capacity">Capacité (personnes)</label>
                        <input type="number" name="capacity" id="edit_capacity" class="form-input-cs"
                            value="<?php echo htmlspecialchars($evenement['capacity']); ?>" />
                    </div>
                </div>

                <div class="form-row-cs">
                    <div class="form-group-cs">
                        <label for="edit_location">Lieu</label>
                        <input type="text" name="location" id="edit_location" class="form-input-cs"
                            value="<?php echo htmlspecialchars($evenement['location']); ?>" />
                    </div>
                    <div class="form-group-cs">
                        <label for="status">Statut</label>
                        <select name="status" id="status" class="form-input-cs">
                            <option value="approved" <?php echo $evenement['status'] == 'approved' ? 'selected' : ''; ?>>
                                Approuvé (Visible)</option>
                            <option value="pending" <?php echo $evenement['status'] == 'pending' ? 'selected' : ''; ?>>En
                                attente</option>
                            <option value="rejected" <?php echo $evenement['status'] == 'rejected' ? 'selected' : ''; ?>>
                                Rejeté</option>
                            <option value="cancelled" <?php echo $evenement['status'] == 'cancelled' ? 'selected' : ''; ?>>
                                Annulé</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-cs" style="margin-bottom:20px;">
                    <label for="edit_image">Image (URL)</label>
                    <input type="text" name="image" id="edit_image" class="form-input-cs"
                        value="<?php echo htmlspecialchars($evenement['image']); ?>" />
                </div>

                <div style="text-align:right;">
                    <button type="submit" class="btn-submit-cs">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                </div>

            </form>
        </div>

    </div>

</body>

</html>