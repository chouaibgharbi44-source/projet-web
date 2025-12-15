<?php
session_start();
require_once '../../model/post.php';  // Changed from '../model/post.php'

// Set content type to JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? null;
    $content = $_POST['content'] ?? null;
    $user_id = $_SESSION['user_id'] ?? 'anonymous';
    
    error_log("=== UPDATE POST REQUEST ===");
    error_log("Post ID: $post_id");
    error_log("User ID: $user_id");
    error_log("Content length: " . strlen($content ?? ''));
    
    if ($post_id && $content && !empty(trim($content))) {
        $result = updatePost($post_id, trim($content), $user_id);
        
        if ($result) {
            error_log("Post updated successfully");
            echo json_encode(['success' => true, 'message' => 'Post updated successfully']);
        } else {
            error_log("Failed to update post");
            echo json_encode(['success' => false, 'message' => 'Failed to update post. You may not own this post.']);
        }
    } else {
        error_log("Invalid data for update");
        echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
exit();
?>