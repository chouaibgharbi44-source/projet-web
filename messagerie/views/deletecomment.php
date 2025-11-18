<?php
require '../model/post.php';

if (isset($_GET['id'])) {
    $comment_id = $_GET['id'];
    deleteComment($comment_id);
}

header("Location: ../index.php");
exit();
?>
