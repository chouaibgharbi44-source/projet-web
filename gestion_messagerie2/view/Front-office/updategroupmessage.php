<?php
// views/updategroupmessage.php - VERSION CORRIGÉE
session_start();

// Activer les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier la session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit();
}

require_once '../../model/group.php';  // Changed from '../model/group.php'

header('Content-Type: application/json');


// Fonction de logging
function logGroupUpdate($message) {
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] GROUP UPDATE: $message");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    logGroupUpdate("=== REQUÊTE DE MODIFICATION GROUPE ===");
    
    // Récupération sécurisée
    $message_id = filter_input(INPUT_POST, 'message_id', FILTER_SANITIZE_STRING);
    $raw_content = filter_input(INPUT_POST, 'content', FILTER_UNSAFE_RAW);
    $user_id = $_SESSION['user_id'];
    
    $content = $raw_content ? trim($raw_content) : null;
    
    logGroupUpdate("Message ID: $message_id");
    logGroupUpdate("User ID: $user_id");
    
    // VALIDATIONS
    if (empty($message_id) || empty($content)) {
        echo json_encode([
            'success' => false, 
            'message' => 'Données manquantes'
        ]);
        exit();
    }
    
    if (strlen($content) < 1) {
        echo json_encode([
            'success' => false, 
            'message' => 'Le message ne peut pas être vide'
        ]);
        exit();
    }
    
    if (strlen($content) > 2000) {
        echo json_encode([
            'success' => false, 
            'message' => 'Le message ne doit pas dépasser 2000 caractères'
        ]);
        exit();
    }
    
    try {
        // Convertir l'ID si numérique
        if (is_numeric($message_id)) {
            $message_id = (int)$message_id;
        }
        
        logGroupUpdate("Appel de updateGroupMessage avec ID: $message_id");
        
        // Appeler la fonction
        $result = updateGroupMessage($message_id, $content, $user_id);
        
        logGroupUpdate("Résultat: " . ($result ? 'SUCCÈS' : 'ÉCHEC'));
        
        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Message de groupe modifié avec succès'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Impossible de modifier le message. Vérifiez que vous en êtes l\'auteur.'
            ]);
        }
        
    } catch (Exception $e) {
        logGroupUpdate("ERREUR: " . $e->getMessage());
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur: ' . $e->getMessage()
        ]);
    }
    
    logGroupUpdate("=== FIN REQUÊTE GROUPE ===");
    
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Méthode non autorisée'
    ]);
}
?>