<?php
session_start();
require_once '../model/post.php';

class PostController {
    
    // Delete post (admin or owner)
    public function deletePost() {
        if (!isset($_SESSION['user_id'])) {
            return ['error' => 'Not authenticated'];
        }
        
        if (!isset($_GET['post_id'])) {
            return ['error' => 'Post ID required'];
        }
        
        $post_id = (int)$_GET['post_id'];
        $user_id = $_SESSION['user_id'];
        $user_role = $_SESSION['role'] ?? 'user';
        
        // Check if user owns the post or is admin
        $post = getPostById($post_id);
        if (!$post) {
            return ['error' => 'Post not found'];
        }
        
        if ($post['user_id'] != $user_id && $user_role !== 'admin') {
            return ['error' => 'Unauthorized'];
        }
        
        if (deletePost($post_id)) {
            return ['success' => 'Post deleted successfully'];
        } else {
            return ['error' => 'Failed to delete post'];
        }
    }
    
    // Update post
    public function updatePost() {
        if (!isset($_SESSION['user_id'])) {
            return ['error' => 'Not authenticated'];
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Invalid request method'];
        }
        
        if (!isset($_POST['post_id']) || !isset($_POST['content'])) {
            return ['error' => 'Post ID and content required'];
        }
        
        $post_id = (int)$_POST['post_id'];
        $content = trim($_POST['content']);
        $user_id = $_SESSION['user_id'];
        
        if (empty($content)) {
            return ['error' => 'Content cannot be empty'];
        }
        
        if (updatePost($post_id, $content, $user_id)) {
            return ['success' => 'Post updated successfully'];
        } else {
            return ['error' => 'Failed to update post'];
        }
    }
    
    // Get post for editing
    public function getPost() {
        if (!isset($_SESSION['user_id'])) {
            return ['error' => 'Not authenticated'];
        }
        
        if (!isset($_GET['post_id'])) {
            return ['error' => 'Post ID required'];
        }
        
        $post_id = (int)$_GET['post_id'];
        $user_id = $_SESSION['user_id'];
        
        $post = getPostById($post_id);
        if (!$post) {
            return ['error' => 'Post not found'];
        }
        
        // Check if user owns the post
        if ($post['user_id'] != $user_id) {
            return ['error' => 'Unauthorized'];
        }
        
        return ['post' => $post];
    }
}

// Handle requests
$controller = new PostController();
$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'delete':
        echo json_encode($controller->deletePost());
        break;
        
    case 'update':
        echo json_encode($controller->updatePost());
        break;
        
    case 'get':
        echo json_encode($controller->getPost());
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}