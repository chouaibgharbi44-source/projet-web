<?php
require_once '../model/post.php';

// Emergency delete - no user checking
if (isset($_GET['post_id'])) {
    $post_id = (int)$_GET['post_id'];
    emergencyDeletePost($post_id);
}

header("Location: ../index.php");
exit();
?>