<?php
// views/deletegroupmessage.php - VERSION SIMPLIFIÉE QUI FONCTIONNE
session_start();

// Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

error_log("=== DELETE GROUP MESSAGE ===");
error_log("Session ID: " . session_id());

if (!isset($_SESSION['user_id'])) {
    error_log("Non authentifié");
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit();
}

require_once '../model/db.php';
require_once '../model/group.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_id = $_POST['message_id'] ?? null;
    $user_id = $_SESSION['user_id'];
    
    error_log("Message ID: " . $message_id);
    error_log("User ID: " . $user_id);
    
    if (!$message_id) {
        error_log("ID manquant");
        echo json_encode(['success' => false, 'message' => 'ID du message manquant']);
        exit();
    }
    
    // Message temporaire
    if (is_string($message_id) && strpos($message_id, 'temp-') === 0) {
        error_log("Message temporaire supprimé: $message_id");
        echo json_encode([
            'success' => true,
            'message' => 'Message temporaire supprimé',
            'simulated' => true
        ]);
        exit();
    }
    
    $message_id = (int)$message_id;
    
    // Appeler la fonction deleteGroupMessage
    $result = deleteGroupMessage($message_id, $user_id);
    
    error_log("Résultat suppression: " . print_r($result, true));
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'Message supprimé avec succès',
            'debug' => $result
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $result['error'] ?? 'Erreur lors de la suppression',
            'debug' => $result
        ]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>