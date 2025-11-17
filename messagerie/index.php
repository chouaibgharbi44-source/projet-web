<?php
include 'header.php';
require 'model/post.php';

// Handle comment submission
if (isset($_POST['add_comment'])) {
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];
    addComment($post_id, $comment);
    header("Location: index.php"); // Refresh to show new comment
    exit();
}
?>

<div class="container">
    <a class="btn" href="views/addpost.php">Add New Post</a>

    <h2>Recent Posts</h2>

    <?php
    $posts = getPosts();
    foreach ($posts as $p): ?>
        <div class="post">
            <p><?= htmlspecialchars($p['content']); ?></p>
            <span class="date">Posted on <?= $p['created_at']; ?></span>
            <p>Likes: <?= countLikes($p['id']); ?></p>
<a href="views/like.php?post_id=<?= $p['id']; ?>" class="btn-like">Like 👍</a>
<a href="views/deletepost.php?id=<?= $p['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this post?');">Delete 🗑️</a>


            <!-- Show comments -->
            <div class="comments">
                <?php
                $comments = getComments($p['id']);
                foreach ($comments as $c): ?>
                    <div class="comment">
                        <p><?= htmlspecialchars($c['content']); ?></p>
                        <span class="date"><?= $c['created_at']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Comment form -->
            <form action="" method="POST" class="comment-form">
                <input type="hidden" name="post_id" value="<?= $p['id']; ?>">
                <input type="text" name="comment" placeholder="Add a comment..." required>
                <button type="submit" name="add_comment">Comment</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<script src="/campus connect/assets/js/script.js"></script>
</body>
</html>
