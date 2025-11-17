<?php
require '../model/post.php';

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    addLike($post_id);
}

// Redirect back to index.php
header("Location: ../index.php");
exit();
