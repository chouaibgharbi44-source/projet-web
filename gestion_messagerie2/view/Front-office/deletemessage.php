<?php
// views/deletemessage.php - VERSION ROBUSTE
session_start();

// Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

error_log("=== DELETE MESSAGE START ===");

// Vérifier la session
if (!isset($_SESSION['user_id'])) {
    error_log("ERR: User not authenticated");
    echo json_encode([
        'success' => false, 
        'message' => 'Non authentifié',
        'session' => session_id(),
        'has_user_id' => isset($_SESSION['user_id'])
    ]);
    exit();
}

error_log("User ID: " . $_SESSION['user_id']);
error_log("POST data: " . print_r($_POST, true));

// Inclure le modèle
$model_path = dirname(__DIR__) . '/../model/message.php';  // Changed path
if (!file_exists($model_path)) {
    error_log("ERR: Model file not found: $model_path");
    echo json_encode(['success' => false, 'message' => 'Fichier modèle introuvable']);
    exit();
}

require_once $model_path;

header('Content-Type: application/json; charset=utf-8');

// Vérifier la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("ERR: Method not allowed: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit();
}

// Récupérer l'ID du message
$message_id = $_POST['message_id'] ?? null;
$user_id = $_SESSION['user_id'];

error_log("Processing delete for message ID: $message_id, user ID: $user_id");

if (!$message_id) {
    error_log("ERR: Missing message ID");
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit();
}

// Message temporaire
if (is_string($message_id) && strpos($message_id, 'temp-') === 0) {
    error_log("Deleting temp message: $message_id");
    echo json_encode(['success' => true, 'message' => 'Message temporaire supprimé']);
    exit();
}

$message_id = (int)$message_id;

// Appeler la fonction deleteMessage
try {
    $result = deleteMessage($message_id, $user_id);
    
    if ($result) {
        error_log("SUCCESS: Message $message_id deleted by user $user_id");
        echo json_encode([
            'success' => true, 
            'message' => 'Message supprimé avec succès',
            'message_id' => $message_id
        ]);
    } else {
        error_log("FAIL: deleteMessage returned false");
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur lors de la suppression',
            'debug' => 'deleteMessage returned false'
        ]);
    }
} catch (Exception $e) {
    error_log("EXCEPTION: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Exception: ' . $e->getMessage()
    ]);
}

error_log("=== DELETE MESSAGE END ===");
?>