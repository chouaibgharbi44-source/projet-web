<?php
session_start();
require_once '../config.php';

$token = $_GET['token'] ?? '';
$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (strlen($password) < 8) {
        $error = "Le mot de passe doit faire au moins 8 caractères";
    } elseif ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas";
    } else {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();

        if ($reset) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hashed, $reset['email']]);

            // Supprime le token
            $pdo->prepare("DELETE FROM password_resets WHERE token = ?")->execute([$token]);

            $success = "Mot de passe changé ! Tu peux te connecter.";
        } else {
            $error = "Lien invalide ou expiré";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="css/profile.css">
    <style>.reset-box { max-width: 420px; margin: 80px auto; padding: 40px; background: #1e1e1e; border-radius: 16px; }</style>
</head>
<body>
<div class="reset-box">
    <h2>Nouveau mot de passe</h2>

    <?php if ($success): ?>
        <p style="color:#4ade80; text-align:center;"><?= $success ?></p>
        <p style="text-align:center;"><a href="login.php">← Se connecter</a></p>
    <?php else: ?>
        <?php if ($error): ?><p style="color:#ff6b6b;"><?= $error ?></p><?php endif; ?>

        <form method="POST">
            <div class="info-item"><input type="password" name="password" placeholder="Nouveau mot de passe (8+)" required minlength="8"></div>
            <div class="info-item"><input type="password" name="confirm" placeholder="Confirmer" required></div>
            <button type="submit" class="save-btn">Changer le mot de passe</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>