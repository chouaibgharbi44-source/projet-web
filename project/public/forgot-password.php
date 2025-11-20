<?php session_start(); require_once '../config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - CampusConnect</title>
    <link rel="stylesheet" href="css/profile.css"> <!-- réutilise ton beau style -->
    <style>
        .forgot-box { max-width: 420px; margin: 100px auto; padding: 40px; background: #1e1e1e; border-radius: 16px; text-align: center; }
        .success { color: #4ade80; margin: 20px 0; }
        .error { color: #ff6b6b; }
    </style>
</head>
<body>

<div class="forgot-box">
    <h2>Mot de passe oublié ?</h2>
    <p>Entre ton email, on t’envoie un lien pour le réinitialiser.</p>

    <?php if (isset($_GET['sent'])): ?>
        <p class="success">Un email t’a été envoyé ! Vérifie ta boîte (et les spams).</p>
    <?php endif; ?>

    <form action="process-forgot.php" method="POST">
        <div class="info-item" style="margin: 20px 0;">
            <input type="email" name="email" placeholder="ton@email.fr" required style="width:100%; padding:14px; border-radius:12px; border:1px solid #333; background:#2d2d2d; color:white;">
        </div>
        <button type="submit" class="save-btn">Envoyer le lien</button>
    </form>

    <p style="margin-top:20px;"><a href="login.php">← Retour à la connexion</a></p>
</div>
</body>
</html>