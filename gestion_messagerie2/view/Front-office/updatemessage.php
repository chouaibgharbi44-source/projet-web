<?php
// views/updatemessage.php - VERSION GESTION TEMP ID
session_start();

// Activer le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Vérifier la session
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Mode démo
    $_SESSION['username'] = 'Utilisateur';
}

require_once '../../model/message.php';  // Changed from '../model/message.php'

header('Content-Type: application/json');


// Fonction pour identifier les temp IDs
function isTempId($id) {
    return is_string($id) && strpos($id, 'temp-') === 0;
}

// Fonction pour extraire le timestamp d'un temp ID
function getTempIdTimestamp($temp_id) {
    if (preg_match('/temp-(\d+)/', $temp_id, $matches)) {
        return $matches[1];
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données
    $raw_message_id = $_POST['message_id'] ?? null;
    $content = $_POST['content'] ?? null;
    $user_id = $_SESSION['user_id'];
    
    error_log("=== MODIFICATION MESSAGE ===");
    error_log("Message ID brut: " . $raw_message_id);
    error_log("Type ID: " . gettype($raw_message_id));
    error_log("User ID: " . $user_id);
    error_log("Contenu: " . substr($content ?? '', 0, 50));
    
    // Validation
    if (!$raw_message_id || !$content) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes']);
        exit();
    }
    
    $content = trim($content);
    if (empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Le message ne peut pas être vide']);
        exit();
    }
    
    // **GESTION DES TEMP IDs**
    if (isTempId($raw_message_id)) {
        error_log("DÉTECTÉ: Temp ID - " . $raw_message_id);
        
        // OPTION 1: Mode démo - simuler la modification
        $timestamp = getTempIdTimestamp($raw_message_id);
        
        if ($timestamp) {
            error_log("Temp ID timestamp: " . date('Y-m-d H:i:s', $timestamp / 1000));
        }
        
        // Pour les temp IDs, on simule la modification
        // En production, il faudrait d'abord enregistrer le message en BD
        
        echo json_encode([
            'success' => true,
            'message' => 'Message temporaire modifié',
            'simulated' => true,
            'temp_id' => $raw_message_id,
            'new_content' => $content,
            'note' => 'En production, le message doit d\'abord être enregistré en base de données'
        ]);
        exit();
    }
    
    // **POUR LES IDs NORMAUX (non-temp)**
    
    // Convertir en int si numérique
    $message_id = is_numeric($raw_message_id) ? (int)$raw_message_id : $raw_message_id;
    
    error_log("Recherche message avec ID: " . $message_id . " (type: " . gettype($message_id) . ")");
    
    // 1. Chercher le message
    $message = getMessageById($message_id);
    
    if (!$message) {
        error_log("Message non trouvé avec ID: " . $message_id);
        
        // Essayer comme string si échoue comme int
        if (is_int($message_id)) {
            $message = getMessageById((string)$message_id);
            error_log("Tentative comme string: " . ($message ? "trouvé" : "non trouvé"));
        }
        
        if (!$message) {
            echo json_encode([
                'success' => false, 
                'message' => 'Message non trouvé (ID: ' . $message_id . ')',
                'suggestion' => 'Vérifiez que le message existe dans la base de données'
            ]);
            exit();
        }
    }
    
    error_log("Message trouvé. Sender: " . $message['sender_id'] . ", User: " . $user_id);
    
    // 2. Vérifier les permissions
    if ($message['sender_id'] != $user_id) {
        echo json_encode([
            'success' => false, 
            'message' => 'Vous n\'êtes pas l\'auteur de ce message'
        ]);
        exit();
    }
    
    // 3. Modifier le message
    $result = updateMessage($message_id, $content, $user_id);
    
    if ($result) {
        echo json_encode([
            'success' => true, 
            'message' => 'Message modifié avec succès',
            'message_id' => $message_id
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Erreur lors de la modification'
        ]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>