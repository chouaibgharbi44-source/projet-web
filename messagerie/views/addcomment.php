<?php
require '../model/post.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $content = $_POST['comment'];

    addComment($post_id, $content);
}

header("Location: ../index.php");
exit();
?>
