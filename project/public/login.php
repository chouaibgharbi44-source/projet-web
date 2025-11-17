<?php
session_start();
require_once '../config.php';
require_once '../model/User.php';
$userModel = new User();
$error = '';

if ($_POST['action'] ?? '' === 'login') {
    $user = $userModel->getByEmail($_POST['email'] ?? '');
    if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else $error = "Email ou mot de passe incorrect.";
}

if ($_POST['action'] ?? '' === 'signup') {
    if ($userModel->getByEmail($_POST['email'] ?? '')) {
        $error = "Email déjà utilisé.";
    } else {
        $data = [
            'first_name' => $_POST['first_name'],
            'last_name'  => $_POST['last_name'],
            'email'      => $_POST['email'],
            'password'   => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'user_type'  => 'student'
        ];
        $userModel->create($data);
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect - Connexion</title>
    <link rel="stylesheet" href="css/style_login.css">
    <style>
        html, body {margin:0;padding:0;height:auto;min-height:100vh;overflow-y:auto}
        .form-content.hidden {display:none !important}
    </style>
</head>
<body onload="window.scrollTo(0,0)">

<div class="auth-container">
    <div class="auth-left">
        <div class="branding">
            <h1>CampusConnect</h1>
            <p class="tagline">Plateforme Sociale Universitaire</p>
            <p class="subtitle">Connectez-vous avec vos camarades, partagez vos projets, organisez des sessions d'étude</p>
        </div>
        <div class="features">
            <div class="feature-item"><div><h3>Communiquez</h3><p>Échangez avec étudiants et professeurs</p></div></div>
            <div class="feature-item"><div><h3>Collaborez</h3><p>Formez des groupes d'étude</p></div></div>
            <div class="feature-item"><div><h3>Partagez</h3><p>Publiez vos projets</p></div></div>
        </div>
    </div>

    <div class="auth-right">
        <div class="form-container">
            <?php if($error) echo "<p style='color:#ff6b6b;text-align:center;margin:10px 0'>$error</p>"; ?>

            <div class="form-wrapper active" id="formWrapper">
                <!-- LOGIN -->
                <div id="loginForm" class="form-content">
                    <h2>Bon retour !</h2>
                    <form method="post">
                        <input type="hidden" name="action" value="login">
                        <div class="form-group"><input type="email" name="email" placeholder="Email" required></div>
                        <div class="form-group"><input type="password" name="password" placeholder="Mot de passe" required></div>
                        <button type="submit" class="btn-submit">Se connecter</button>
                        <p class="signup-link">Pas de compte ? <a href="signup.php">Créer un compte</a></p>
                    </form>
                </div>

                <!-- SIGNUP -->
                <div id="signupForm" class="form-content hidden">
                    <h2>Créer un compte</h2>
                    <form method="post">
                        <input type="hidden" name="action" value="signup">
                        <div class="form-group"><input type="text" name="first_name" placeholder="Prénom" required></div>
                        <div class="form-group"><input type="text" name="last_name" placeholder="Nom" required></div>
                        <div class="form-group"><input type="email" name="email" placeholder="Email" required></div>
                        <div class="form-group"><input type="password" name="password" placeholder="Mot de passe (8+)" minlength="8" required></div>
                        <button type="submit" class="btn-submit">S'inscrire</button>
                        <p class="signup-link">Déjà un compte ? <a href="#" onclick="switchToLogin()">Se connecter</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchToSignup() {
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('signupForm').classList.remove('hidden');
}
function switchToLogin() {
    document.getElementById('signupForm').classList.add('hidden');
    document.getElementById('loginForm').classList.remove('hidden');
}
</script>
</body>
</html>