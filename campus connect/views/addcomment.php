<?php
session_start();
require_once '../model/post.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? null;
    $content = $_POST['content'] ?? null;
    $user_id = $_SESSION['user_id'] ?? 'anonymous';
    
    if ($post_id && $content && !empty(trim($content))) {
        addComment($post_id, trim($content), $user_id);
    }
}

header("Location: ../index.php");
exit();
?>