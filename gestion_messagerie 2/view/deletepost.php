<?php
require_once '../model/post.php';

// Emergency delete - no user checking, just delete the post
if (isset($_GET['post_id'])) {
    $post_id = (int)$_GET['post_id'];
    
    global $pdo;
    if ($pdo) {
        try {
            // Delete everything related to this post
            $pdo->exec("DELETE FROM comments WHERE post_id = $post_id");
            $pdo->exec("DELETE FROM likes WHERE post_id = $post_id");
            $pdo->exec("DELETE FROM posts WHERE id = $post_id");
        } catch (Exception $e) {
            // Ignore errors
        }
    }
}

header("Location: ../index.php");
exit();
?>