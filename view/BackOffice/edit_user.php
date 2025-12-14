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
        body {
    font-family: system-ui;
    background: #121212;
    color:  #bb86fc;
    margin: 0;
    padding: 3rem 1rem;
    display: flex;
    justify-content: center;
}

.container {
    width: 100%;
    max-width: 480px; /* Smaller and tighter */
    background: #1a1a1a;
    padding: 1.8rem;
    border-radius: 20px;
    box-shadow: 0 0 25px #0005;
}

h1 {
    text-align: center;
    margin-bottom: 1.5rem;
    font-size: 1.6rem;
    font-weight: 600;
}

form {
    display: flex;
    flex-direction: column;
    gap: 0.75rem; /* Much tighter */
}

input,
select {
    width: 100%;
    padding: 1.2rem 1.4rem;      /* More padding = text further from borders */
    background: #1e1e1e;
    border: 1px solid #333;
    border-radius: 14px;         /* Softer shape */
    color: white;
    font-size: 1rem;
    text-align: center;          /* Center text horizontally */
    box-sizing: border-box;
}

.actions {
    margin-top: 0.5rem;  /* tighter */
    display: flex;
    gap: 0.75rem;
}

button {
    background: #bb86fc;
    color: black;
    padding: 0.9rem;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    flex: 1;
    font-size: 0.95rem;
}

.cancel-btn {
    background: #2c2c2c;
    color: #e0e0e0;
    padding: 0.9rem;
    border-radius: 10px;
    text-align: center;
    text-decoration: none;
    font-weight: bold;
    flex: 1;
    font-size: 0.95rem;
}

button:hover { background: #d3a4ff; }
.cancel-btn:hover { background: #3b3b3b; }

    </style>

</head>
<body>

<div class="container">
    <h1>Éditer l'utilisateur</h1>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <input name="student_id" 
               value="<?= htmlspecialchars($user['student_id']) ?>" 
               required 
               placeholder="ID Étudiant">

        <input name="first_name" 
               value="<?= htmlspecialchars($firstName) ?>" 
               required 
               placeholder="Prénom">

        <input name="last_name" 
               value="<?= htmlspecialchars($lastName) ?>" 
               required 
               placeholder="Nom">

        <input name="email" 
               type="email" 
               value="<?= htmlspecialchars($user['email']) ?>" 
               required 
               placeholder="Email">

        <input name="phone" 
               value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
               placeholder="Téléphone">

        <select name="user_type" required>
            <option value="student" <?= $user['type'] === 'student' ? 'selected' : '' ?>>Étudiant</option>
            <option value="teacher" <?= $user['type'] === 'teacher' ? 'selected' : '' ?>>Professeur</option>
            <option value="admin" <?= $user['type'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        </select>

        <input name="year" 
               value="<?= htmlspecialchars($user['year'] ?? '') ?>" 
               placeholder="Année (ex: 2)">

        <div class="actions">
            <button type="submit">Sauvegarder</button>
            <a href="index.php" class="cancel-btn">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>
