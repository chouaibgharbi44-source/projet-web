<?php
// views/updatecomment_admin.php
session_start();
require_once '../../model/post.php';

// Simple admin check
if (!isset($_POST['key']) || $_POST['key'] !== 'admin123') {
    echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = $_POST['comment_id'] ?? null;
    $content = $_POST['content'] ?? null;
    
    error_log("=== ADMIN UPDATE COMMENT ===");
    error_log("Comment ID: $comment_id");
    
    if (!$comment_id || !$content || empty(trim($content))) {
        echo json_encode(['success' => false, 'error' => 'Données invalides']);
        exit();
    }
    
    $content = trim($content);
    
    // Validation
    if (strlen($content) < 2) {
        echo json_encode(['success' => false, 'error' => 'Le commentaire doit contenir au moins 2 caractères']);
        exit();
    }
    
    if (strlen($content) > 1000) {
        echo json_encode(['success' => false, 'error' => 'Le commentaire ne doit pas dépasser 1000 caractères']);
        exit();
    }
    
    // Admin can update any comment - pass null for user_id to bypass ownership check
    $result = updateComment($comment_id, $content, null);
    
    if ($result) {
        error_log("Comment updated successfully by admin");
        echo json_encode(['success' => true, 'message' => 'Commentaire modifié avec succès']);
    } else {
        error_log("Failed to update comment");
        echo json_encode(['success' => false, 'error' => 'Échec de la modification. Vérifiez que le commentaire existe.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
}
exit();
?>