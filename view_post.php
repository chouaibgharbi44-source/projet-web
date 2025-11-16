<?php
include "includes/db.php";
include "includes/header.php";

$id = $_GET['id'];

$post = $conn->query("SELECT * FROM posts WHERE id=$id")->fetch_assoc();
?>

<div class="post full">
    <h2><?= $post["title"] ?></h2>
    <p><?= $post["content"] ?></p>
    <small>Posted on <?= $post["created_at"] ?></small>
</div>

<h3 class="comment-title">Comments</h3>

<?php
$comments = $conn->query("SELECT * FROM comments WHERE post_id=$id ORDER BY created_at DESC");

while ($c = $comments->fetch_assoc()) {
    echo "
    <div class='comment'>
        <p>{$c['content']}</p>
        <small>{$c['created_at']}</small>
    </div>";
}
?>

<h3>Add a Comment</h3>

<form action="add_comment.php" method="POST" class="comment-form">
    <input type="hidden" name="post_id" value="<?= $id ?>">
    <textarea name="content" placeholder="Write a comment..." required></textarea>
    <button type="submit">Comment</button>
</form>

</body>
</html>
