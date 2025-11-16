<?php
include "includes/db.php";

$post_id = $_POST["post_id"];
$content = $_POST["content"];

$sql = "INSERT INTO comments (post_id, content) VALUES ($post_id, '$content')";
$conn->query($sql);

header("Location: view_post.php?id=$post_id");
exit;
?>
