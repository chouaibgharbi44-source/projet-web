<?php
session_start();
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "User ID: " . ($_SESSION['user_id'] ?? 'NOT SET') . "\n";
echo "Username: " . ($_SESSION['username'] ?? 'NOT SET') . "\n";
echo "Deleted messages: " . (isset($_SESSION['deleted_messages']) ? implode(', ', $_SESSION['deleted_messages']) : 'NONE') . "\n";

// Test de suppression
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'Vous';
if (!isset($_SESSION['deleted_messages'])) {
    $_SESSION['deleted_messages'] = [3]; // Simuler un message supprimé
}

echo "\nAfter setting:\n";
echo "Deleted messages: " . implode(', ', $_SESSION['deleted_messages']);
?>