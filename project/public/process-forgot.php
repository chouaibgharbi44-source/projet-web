<?php
// public/process-forgot.php
session_start();
require_once '../config.php';
require_once '../model/User.php';

// Charge PHPMailer automatiquement
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot-password.php');
    exit;
}

$email = trim($_POST['email']);
$userModel = new User();
$user = $userModel->getByEmail($email);

if (!$user) {
    sleep(1);
    header('Location: forgot-password.php?sent=1');
    exit;
}

// Token + expiration
$token = bin2hex(random_bytes(32));
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

global $pdo;
$pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)")
    ->execute([$email, $token, $expires]);

// Lien de réinitialisation
$resetLink = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . "/reset-password.php?token=" . urlencode($token);

$message = "
Salut {$user['full_name']} !

Clique sur ce lien pour réinitialiser ton mot de passe (valable 1 heure) :

$resetLink

Si tu n'as rien demandé, ignore cet email.

À bientôt !
CampusConnect
";

$mail = new PHPMailer(true);

try {
    // === CONFIGURATION SMTP (exemple Gmail) ===
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'youssef.zaiediv@gmail.com';           // ← TON EMAIL
    $mail->Password   = 'swzg saad dlhv wnop'; // ← Mot de passe d'application (pas ton vrai mdp)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    // Expéditeur / Destinataire
    $mail->setFrom('no-reply@campusconnect.local', 'CampusConnect');
    $mail->addAddress($email);

    // Contenu
    $mail->Subject = 'Réinitialisation de ton mot de passe - CampusConnect';
    $mail->Body    = $message;

    $mail->send();
} catch (Exception $e) {
    // En prod tu peux logger l’erreur au lieu de l’afficher
    error_log("Erreur envoi email : {$mail->ErrorInfo}");
}

header('Location: forgot-password.php?sent=1');
exit;