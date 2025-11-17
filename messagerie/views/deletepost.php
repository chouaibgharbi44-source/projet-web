<?php
require '../model/post.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Delete post
    deletePost($post_id);

    // Redirect back to index.php
    header("Location: ../index.php");
    exit();
} else {
    echo "No post ID specified.";
}
?>
