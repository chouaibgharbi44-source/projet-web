<?php
// views/addgroupmessage.php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit();
}

require_once '../../model/db.php';  // Changed from '../model/db.php'
require_once '../../model/group.php';  // Changed from '../model/group.php'

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group_id = $_POST['group_id'] ?? null;
    $content = $_POST['content'] ?? null;
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'] ?? 'Utilisateur';
    
    if (!$group_id || !$content) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes']);
        exit();
    }
    
    $content = trim($content);
    
    if (empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Le message ne peut pas être vide']);
        exit();
    }
    
    $result = addGroupMessage($group_id, $user_id, $username, $content);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message_id' => $result['id'],
            'message' => 'Message envoyé avec succès'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $result['error'] ?? 'Erreur lors de l\'envoi'
        ]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>