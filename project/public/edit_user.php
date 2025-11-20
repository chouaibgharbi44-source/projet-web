<?php

session_start();
require_once '../config.php';
require_once '../model/User.php';

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$userModel = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $data = [
        'student_id' => $_POST['student_id'],
        'full_name'  => trim($_POST['first_name'] . ' ' . $_POST['last_name']),
        'email'      => $_POST['email'],
        'phone'      => $_POST['phone'] ?? '',
        'type'       => $_POST['user_type'],
        'year'       => $_POST['year'] ?? null
    ];
    $userModel->update($id, $data);
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$user = $userModel->getById($_GET['id']);
if (!$user) {
    die("Utilisateur non trouvé");
}

 
$nameParts = explode(' ', $user['full_name'], 2);
$firstName = $nameParts[0] ?? '';
$lastName  = $nameParts[1] ?? '';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditer utilisateur</title>
    <style>
        body { font-family: system-ui; background: #121212; color: #e0e0e0; padding: 2rem; }
        form { max-width: 600px; margin: auto; background: #1a1a1a; padding: 2rem; border-radius: 16px; }
        input, select { width: 100%; padding: 1rem; margin: 0.5rem 0; background: #1e1e1e; border: 1px solid #333; border-radius: 12px; color: white; }
        button { background: #bb86fc; color: black; padding: 1rem; border: none; border-radius: 12px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Éditer l'utilisateur</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        
        <input name="student_id" value="<?= htmlspecialchars($user['student_id']) ?>" required placeholder="ID Étudiant"><br>
        <input name="first_name" value="<?= htmlspecialchars($firstName) ?>" required placeholder="Prénom"><br>
        <input name="last_name" value="<?= htmlspecialchars($lastName) ?>" required placeholder="Nom"><br>
        <input name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>
        <input name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="Téléphone"><br>
        
        <select name="user_type" required>
            <option value="student" <?= $user['type'] === 'student' ? 'selected' : '' ?>>Étudiant</option>
            <option value="teacher" <?= $user['type'] === 'teacher' ? 'selected' : '' ?>>Professeur</option>
            <option value="admin" <?= $user['type'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        </select><br><br>
        
        <input name="year" value="<?= htmlspecialchars($user['year'] ?? '') ?>" placeholder="Année (ex: 2)"><br><br>
        
        <button type="submit">Sauvegarder</button>
        <a href="index.php" style="color:#bb86fc; margin-left: 1rem;">Annuler</a>
    </form>
</body>
</html>