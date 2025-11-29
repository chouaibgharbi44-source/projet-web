<?php
require_once '../model/post.php';

if (isset($_GET['post_id'])) {
    $post_id = (int)$_GET['post_id'];
    
    global $pdo;
    if ($pdo) {
        // Only add like if database is available
        try {
            $sql = "INSERT IGNORE INTO likes (post_id, created_at) VALUES (?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$post_id]);
        } catch (Exception $e) {
            // If database fails, do nothing (likes will be random)
        }
    }
}

header("Location: ../index.php");
exit();
?>