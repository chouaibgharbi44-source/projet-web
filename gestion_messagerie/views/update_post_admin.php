<?php
// views/update_post_admin.php
session_start();
require_once '../model/post.php';

// Simple admin check
if (!isset($_POST['key']) || $_POST['key'] !== 'admin123') {
    echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? null;
    $content = $_POST['content'] ?? null;
    
    error_log("=== ADMIN UPDATE POST ===");
    error_log("Post ID: $post_id");
    error_log("Content: " . substr($content ?? '', 0, 100));
    
    if (!$post_id || !$content || empty(trim($content))) {
        echo json_encode(['success' => false, 'error' => 'Données invalides']);
        exit();
    }
    
    $content = trim($content);
    
    // Validation
    if (strlen($content) < 10) {
        echo json_encode(['success' => false, 'error' => 'Le contenu doit contenir au moins 10 caractères']);
        exit();
    }
    
    if (strlen($content) > 5000) {
        echo json_encode(['success' => false, 'error' => 'Le contenu ne doit pas dépasser 5000 caractères']);
        exit();
    }
    
    // Admin can update any post - pass null for user_id to bypass ownership check
    $result = updatePost($post_id, $content, null);
    
    if ($result) {
        error_log("Post updated successfully by admin");
        echo json_encode(['success' => true, 'message' => 'Publication modifiée avec succès']);
    } else {
        error_log("Failed to update post");
        echo json_encode(['success' => false, 'error' => 'Échec de la modification. Vérifiez que le post existe.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
}
exit();
?>