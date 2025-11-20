<?php
 
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/User.php';

$userModel = new User();
$error = '';

 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        $user = $userModel->getByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
           
            if ($user['type'] === 'admin') {
                
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['full_name']; 
                $_SESSION['role']      = 'admin';

                
                header('Location: ../public/index.php');
                exit;
            } else {
                $error = "Accès refusé. Ce compte n'a pas les droits d'administrateur.";
            }
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In - CampusConnect</title>
    <link rel="stylesheet" href="css/style_admin_login.css">
    <style>
        .error-message, #js-error-message {
            color: #ff6b6b;
            text-align: center;
            margin: 15px 0;
            font-weight: 600;
            padding: 12px;
            background: rgba(255, 107, 107, 0.15);
            border-radius: 8px;
            border: 1px solid #ff6b6b;
        }
        #js-error-message { display: none; }
    </style>
</head>
<body>

    <div class="wrapper">
        <div class="left-panel">
            <div class="branding">
                <h1 class="logo-text">CampusConnect</h1>
                <p class="tagline">Admin Portal</p>
            </div>
        </div>

        <div class="right-panel">
            <div class="login-card">

                <div class="login-header">
                    <h2>Welcome Back, Admin</h2>
                    <p class="subtitle">Sign in to manage CampusConnect</p>
                </div>

                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div id="js-error-message"></div>

                <form action="" method="POST" id="admin-login-form">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="admin@campusconnect.edu" required autocomplete="username" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="Mot de passe" required autocomplete="current-password">
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" id="remember" name="remember">
                            <span>Rester connecté</span>
                        </label>
                        <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="signin-btn">Se connecter</button>
                </form>

                <div class="extra-links">
                    <p>Pas administrateur ? <a href="login.php">Retour à CampusConnect</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="js/admin_login.js"></script> 
</body>
</html>