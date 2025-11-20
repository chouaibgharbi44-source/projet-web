<?php
session_start();
require_once '../config.php';
require_once '../model/User.php';

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

    // Split full_name into parts for editing (optional: keep as one field)
    $fullName = trim($_POST['full_name']);

    $updates = [
        'full_name'  => $fullName,
        'phone'      => trim($_POST['phone'] ?? ''),
        'department' => trim($_POST['department'] ?? ''),
        'year'       => $_POST['year'] ?? null,
        'type'       => $currentUser['type'], // cannot change type here
        'student_id' => $currentUser['student_id'] // keep same
    ];

    // Profile picture upload
    if (!empty($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp']) && $_FILES['profile_pic']['size'] < 5_000_000) {
            @mkdir('uploads/profiles', 0755, true);
            $path = 'uploads/profiles/' . $currentUser['id'] . '.' . $ext;
            move_uploaded_file($_FILES['profile_pic']['tmp_name'], $path);
            $updates['profile_pic'] = $path;
        }
    }

    // Banner upload
    if (!empty($_FILES['banner']['name']) && $_FILES['banner']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp']) && $_FILES['banner']['size'] < 8_000_000) {
            @mkdir('uploads/banners', 0755, true);
            $path = 'uploads/banners/' . $currentUser['id'] . '.' . $ext;
            move_uploaded_file($_FILES['banner']['tmp_name'], $path);
            $updates['banner'] = $path;
        }
    }

    $userModel->updateProfile($_SESSION['user_id'], $updates); // We'll fix this method
    $currentUser = $userModel->getById($_SESSION['user_id']);
    $success = "Profil mis à jour avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - CampusConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>

<div class="container">
    <!-- Banner -->
    <div class="banner-container">
        <img src="<?= $currentUser['banner'] ?? 'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2000&auto=format&fit=crop' ?>" 
             alt="Bannière" class="banner" id="bannerImg">
        <label class="banner-edit">
            <input type="file" name="banner" accept="image/*" class="file-input">
            <span class="material-icons">photo_camera</span>
        </label>
    </div>

    <div class="profile-header">
        <div class="profile-pic-container">
            <img src="<?= $currentUser['profile_pic'] ?? 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $currentUser['id'] ?>" 
                 alt="Photo" class="profile-pic" id="profilePic">
            <label class="pic-edit">
                <input type="file" name="profile_pic" accept="image/*" class="file-input">
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
        <a href="logout.php" class="logout-btn">Déconnexion</a>
    </div>

    <?php if ($success): ?>
        <div class="success-alert"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="info-form">
        <input type="hidden" name="update_profile" value="1">

        <div class="info-grid">
            <div class="info-item">
                <label>Nom complet</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($currentUser['full_name']) ?>" required>
            </div>
            <div class="info-item">
                <label>Email</label>
                <input type="email" value="<?= htmlspecialchars($currentUser['email']) ?>" disabled>
            </div>
            <div class="info-item">
                <label>Téléphone</label>
                <input type="tel" 
                name="phone" 
                id="phone" 
                value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>"
                placeholder="Ex: 06 12 34 56 78"
                pattern="^(\+33|0)[1-9](\s?\d{2}){4}$"
                title="Format: 06 XX XX XX XX ou +33 6 XX XX XX XX">
                <small id="phone-error" style="color:#ff6b6b; display:none; margin-top:4px;">
                    Format invalide (ex: 06 12 34 56 78)
                </small>
            </div>
            <div class="info-item">
                <label>Département</label>
                <input type="text" name="department" value="<?= htmlspecialchars($currentUser['department'] ?? '') ?>">
            </div>

            <?php if ($currentUser['type'] === 'student'): ?>
            <div class="info-item">
                <label>Année d'étude</label>
                <select name="year">
                    <option value="">Non renseigné</option>
                    <?php for($i=1; $i<=6; $i++): ?>
                        <option value="<?= $i ?>" <?= ($currentUser['year'] ?? '') == $i ? 'selected' : '' ?>><?= $i ?>ème année</option>
                    <?php endfor; ?>
                </select>
            </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="save-btn">Sauvegarder</button>
    </form>

    <div class="activity-section">
        <h2>Activité récente</h2>
        <div class="activity-list">
            <div class="activity-item">
                <span class="material-icons">person_add</span>
                <div><p>Compte créé</p><small><?= date('d/m/Y', strtotime($currentUser['created_at'] ?? 'now')) ?></small></div>
            </div>
        </div>
    </div>
</div>

<script src="js/profile.js"></script>
</body>
</html>