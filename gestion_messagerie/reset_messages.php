<?php
session_start();

// Réinitialiser complètement
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'Vous';
$_SESSION['deleted_messages'] = [];

echo "<h1>Session réinitialisée!</h1>";
echo "<p>Tous les messages seront restaurés.</p>";
echo '<p><a href="views/messages.php">Retour à la messagerie</a></p>';
echo '<p><a href="views/messages.php?receiver_id=2">Voir conversation avec Marie</a></p>';

// Afficher les données
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>