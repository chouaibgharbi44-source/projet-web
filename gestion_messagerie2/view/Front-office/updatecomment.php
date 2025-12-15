<?php
// views/updatecomment.php
session_start();
require_once '../../model/post.php';  // Changed from '../model/post.php'

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = $_POST['comment_id'] ?? null;
    $content = $_POST['content'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;
    
    if (!$comment_id || !$content || empty(trim($content))) {
        echo json_encode(['success' => false, 'message' => 'Données invalides']);
        exit();
    }
    
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'Vous devez être connecté']);
        exit();
    }
    
    $content = trim($content);
    
    if (strlen($content) < 2) {
        echo json_encode(['success' => false, 'message' => 'Le commentaire doit contenir au moins 2 caractères']);
        exit();
    }
    
    if (strlen($content) > 1000) {
        echo json_encode(['success' => false, 'message' => 'Le commentaire ne doit pas dépasser 1000 caractères']);
        exit();
    }
    
    $result = updateComment($comment_id, $content, $user_id);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Commentaire modifié avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Vous n\'êtes pas l\'auteur de ce commentaire ou une erreur est survenue']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>