<?php
session_start();
require_once '../model/post.php';

if (isset($_GET['comment_id'])) {
    $comment_id = (int)$_GET['comment_id'];
    $user_id = $_SESSION['user_id'] ?? 'anonymous';
    
    error_log("Attempting to delete comment $comment_id for user $user_id");
    
    $result = deleteComment($comment_id, $user_id);
    
    if ($result) {
        error_log("Comment $comment_id deleted successfully");
    } else {
        error_log("Failed to delete comment $comment_id");
    }
}

header("Location: ../index.php");
exit();
?>