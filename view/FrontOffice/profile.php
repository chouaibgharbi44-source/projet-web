<?php
// ... (Your PHP code remains exactly the same - no changes needed)
session_start();
require_once '../../config.php';
require_once '../../model/User.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

$userModel = new User();
$currentUser = $userModel->getById($_SESSION['user_id']);

if (!$currentUser) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {

    $fullName = trim(($_POST['first_name'] ?? '') . ' ' . ($_POST['last_name'] ?? ''));

    $updates = [
        'full_name'  => $fullName,
        'phone'      => trim($_POST['phone'] ?? ''),
        'department' => trim($_POST['department'] ?? ''),
        'year'       => $_POST['year'] ?? null
    ];

    // Profile picture upload
    if (!empty($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp']) && $_FILES['profile_pic']['size'] < 5_000_000) {
            @mkdir('uploads/profiles', 0755, true);
            $filename = $_SESSION['user_id'] . '.' . $ext;
            $path = 'uploads/profiles/' . $filename;
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $path)) {
                $updates['profile_pic'] = $path;
            }
        }
    }

    // Banner upload
    if (!empty($_FILES['banner']['name']) && $_FILES['banner']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp']) && $_FILES['banner']['size'] < 8_000_000) {
            @mkdir('uploads/banners', 0755, true);
            $filename = $_SESSION['user_id'] . '.' . $ext;
            $path = 'uploads/banners/' . $filename;
            if (move_uploaded_file($_FILES['banner']['tmp_name'], $path)) {
                $updates['banner'] = $path;
            }
        }
    }

    if ($userModel->updateProfile($_SESSION['user_id'], $updates)) {
        $currentUser = $userModel->getById($_SESSION['user_id']);
        $success = "Profil mis à jour avec succès !";
    }
}

// Split name for form
$nameParts = explode(' ', trim($currentUser['full_name']), 2);
$firstName = $nameParts[0] ?? '';
$lastName = $nameParts[1] ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - CampusConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/profile.css">
    <style>
        :root {
            --primary: #ff2f78;
            --primary-light: #ff5d96;
            --primary-gradient: linear-gradient(120deg, #ff2f78, #ff5d96);
        }

        /* === ANIMATIONS === */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 47, 120, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 47, 120, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 47, 120, 0); }
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
        }

        /* Main card entrance */
        .profile-card {
            background: var(--white);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(255,77,141,0.08);
            animation: fadeInUp 0.8s ease-out forwards;
        }

        /* Sidebar entrance */
        .activity-section {
            animation: slideInRight 0.8s ease-out 0.2s forwards;
            opacity: 0; /* Start hidden for animation */
            animation-fill-mode: both;
        }

        .banner-container {
            position: relative;
            height: 260px;
            overflow: hidden;
        }

        .banner {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Circle edit buttons */
        .banner-edit,
        .pic-edit {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            padding: 0 !important;
            cursor: pointer;
            box-shadow: var(--shadow-strong);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .banner-edit {
            width: 56px;
            height: 56px;
            background: rgba(255, 77, 141, 0.85);
            backdrop-filter: blur(4px);
        }

        .pic-edit {
            width: 44px;
            height: 44px;
            background: var(--primary);
        }

        .banner-edit:hover, .pic-edit:hover {
            transform: scale(1.15);
            animation: pulse 1.5s infinite;
        }

        .banner-edit .material-icons,
        .pic-edit .material-icons {
            font-size: 24px;
            color: white;
        }

        /* Strong avatar overlap */
        .profile-header {
            position: relative;
            padding: 0 2rem;
            margin-top: -150px;
            display: flex;
            align-items: flex-end;
            gap: 1.5rem;
            padding-bottom: 1.5rem;
            animation: fadeInUp 0.9s ease-out 0.3s forwards;
            opacity: 0;
            animation-fill-mode: both;
        }

        .profile-pic {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid var(--white);
            box-shadow: var(--shadow-soft);
        }

        .user-info-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: var(--ink-900);
        }

        .user-role {
            color: var(--rose-700);
            font-weight: 600;
            margin: 0.5rem 0;
        }

        .user-id {
            color: var(--ink-500);
            margin: 0;
        }

        .form-content {
            padding: 0 2rem 2.5rem;
            animation: fadeInUp 1s ease-out 0.5s forwards;
            opacity: 0;
            animation-fill-mode: both;
        }

        .success-alert {
            background: #d9f99d;
            color: #1e3d00;
            border: 1px solid #a3e635;
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.2rem;
            margin-bottom: 2rem;
        }

        .info-item input:focus,
        .info-item select:focus {
            border-color: var(--rose-600);
            box-shadow: 0 0 0 3px rgba(255,77,141,0.15);
        }

        .btn-group {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .save-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.9rem 2.5rem;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: var(--shadow-strong);
            min-width: 200px;
            transition: all 0.3s ease;
        }

        .save-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 35px rgba(255,77,141,0.25);
        }

        .logout-btn {
            background: transparent;
            color: #e11d48;
            border: 1.5px solid #fecaca;
            padding: 0.9rem 2rem;
            border-radius: 999px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            transform: translateY(-4px);
        }

        @media (max-width: 968px) {
            .container {
                grid-template-columns: 1fr;
            }
            .profile-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .btn-group {
                justify-content: center;
            }
        }

        @media (max-width: 640px) {
            .profile-pic {
                width: 130px;
                height: 130px;
            }
            .profile-header {
                margin-top: -65px;
            }
            .btn-group {
                flex-direction: column;
                width: 100%;
            }
            .save-btn, .logout-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-inner">
            <div class="logo">CAMPUS CONNECT</div>
            <nav class="navbar">
                <a href="homepage.php" class="nav-link">Accueil</a>
                <a href="../../BasmaCRUD/index.php" class="nav-link">Événements</a>
                <a href="../../VV13/index.php" class="nav-link">Ressources</a>
                <a href="#" class="nav-link">Messages</a>
                <a href="#" class="nav-link">Quizzes</a>
                <a href="profile.php" class="nav-link active">Profil</a>
            </nav>
        </div>
    </header>

    <div class="container">

        <div class="profile-card">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_profile" value="1">

                <!-- Banner -->
                <div class="banner-container">
                    <img src="<?= $currentUser['banner'] ?? 'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2000&auto=format&fit=crop' ?>"
                         alt="Bannière" class="banner" id="bannerPreview">
                    <label class="banner-edit">
                        <input type="file" name="banner" accept="image/*" id="bannerInput" class="file-input">
                        <span class="material-icons">photo_camera</span>
                    </label>
                </div>

                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-pic-container">
                        <img src="<?= $currentUser['profile_pic'] ?? 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $currentUser['id'] ?>"
                             alt="Photo de profil" class="profile-pic" id="profilePreview">
                        <label class="pic-edit">
                            <input type="file" name="profile_pic" accept="image/*" id="profileInput" class="file-input">
                            <span class="material-icons">camera_alt</span>
                        </label>
                    </div>

                    <div class="user-info-header">
                        <h1><?= htmlspecialchars($currentUser['full_name']) ?></h1>
                        <p class="user-role">
                            <?= $currentUser['type'] === 'student' ? 'Étudiant' : 
                               ($currentUser['type'] === 'professor' ? 'Professeur' : 'Administrateur') ?>
                        </p>
                        <p class="user-id">@<?= htmlspecialchars($currentUser['student_id'] ?? 'user' . $currentUser['id']) ?></p>
                    </div>
                </div>

                <div class="form-content">
                    <?php if ($success): ?>
                        <div class="success-alert"><?= $success ?></div>
                    <?php endif; ?>

                    <div class="info-grid">
                        <div class="info-item">
                            <label>Prénom</label>
                            <input type="text" name="first_name" value="<?= htmlspecialchars($firstName) ?>" required>
                        </div>
                        <div class="info-item">
                            <label>Nom</label>
                            <input type="text" name="last_name" value="<?= htmlspecialchars($lastName) ?>" required>
                        </div>
                        <div class="info-item">
                            <label>Email</label>
                            <input type="email" value="<?= htmlspecialchars($currentUser['email']) ?>" disabled>
                        </div>
                        <div class="info-item">
                            <label>Téléphone</label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>">
                        </div>
                        <div class="info-item">
                            <label>Département</label>
                            <input type="text" name="department" value="<?= htmlspecialchars($currentUser['department'] ?? '') ?>">
                        </div>
                        <div class="info-item">
                            <label>Année</label>
                            <select name="year">
                                <option value="">Sélectionner une année</option>
                                <option value="1" <?= $currentUser['year'] == '1' ? 'selected' : '' ?>>1ère année</option>
                                <option value="2" <?= $currentUser['year'] == '2' ? 'selected' : '' ?>>2ème année</option>
                                <option value="3" <?= $currentUser['year'] == '3' ? 'selected' : '' ?>>3ème année</option>
                            </select>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="save-btn">Sauvegarder les modifications</button>
                        <a href="logout.php" class="logout-btn">Déconnexion</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Activity Sidebar -->
        <div class="activity-section">
            <h2>Activité récente</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <span class="material-icons">person_add</span>
                    <div>
                        <p>Compte créé</p>
                        <small><?= date('d/m/Y', strtotime($currentUser['created_at'] ?? 'now')) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Real-time image previews
        document.getElementById('profileInput').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                document.getElementById('profilePreview').src = URL.createObjectURL(e.target.files[0]);
            }
        });

        document.getElementById('bannerInput').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                document.getElementById('bannerPreview').src = URL.createObjectURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>