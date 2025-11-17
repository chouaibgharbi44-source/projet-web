<?php
require '../model/post.php';

// Simple protection with URL key (change 'admin123' to something secure)
if (!isset($_GET['key']) || $_GET['key'] !== 'admin123') {
    echo "<h2>Access Denied</h2>";
    exit();
}

// Handle delete post action
if (isset($_GET['delete_post'])) {
    $post_id = (int)$_GET['delete_post'];
    deletePost($post_id); // deletes post + its comments and likes
    header("Location: admin.php?key=admin123");
    exit();
}

// Handle delete comment action
if (isset($_GET['delete_comment'])) {
    $comment_id = (int)$_GET['delete_comment'];
    deleteComment($comment_id);
    header("Location: admin.php?key=admin123");
    exit();
}

// Get all posts (including pending if you want)
$posts = getPosts(true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Backoffice</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
/* Admin-specific styles */
.btn-delete { background: #c0392b; color: white; padding: 5px 10px; border-radius:5px; text-decoration:none; margin-right:5px; }
.btn-delete:hover { background: #e74c3c; }
.post { border:1px solid #ccc; padding:10px; margin-bottom:15px; border-radius:5px; }
.comments-admin { margin-left: 20px; margin-top:10px; }
.comment-admin { border-bottom:1px solid #ccc; padding:5px 0; }
.date { font-size:0.85em; color:#555; }
</style>
</head>
<body>
<div class="container">
    <h2>Admin Backoffice - Delete Posts & Comments</h2>

    <?php
    if (empty($posts)) {
        echo "<p>No posts found.</p>";
    } else {
        foreach ($posts as $p): ?>
            <div class="post">
                <p><?= htmlspecialchars($p['content']); ?></p>
                <span class="date">Posted on <?= $p['created_at']; ?></span>
                <p>Likes: <?= countLikes($p['id']); ?></p>
                <a href="admin.php?key=admin123&delete_post=<?= $p['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this post?');">Delete Post 🗑️</a>

                <!-- Comments -->
                <div class="comments-admin">
                    <h4>Comments:</h4>
                    <?php
                    $comments = getComments($p['id']);
                    if (!empty($comments)) {
                        foreach ($comments as $c): ?>
                            <div class="comment-admin">
                                <p><?= htmlspecialchars($c['content']); ?></p>
                                <span class="date"><?= $c['created_at']; ?></span>
                                <a href="admin.php?key=admin123&delete_comment=<?= $c['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this comment?');">Delete Comment 🗑️</a>
                            </div>
                        <?php endforeach;
                    } else {
                        echo "<p>No comments.</p>";
                    }
                    ?>
                </div>
            </div>
        <?php endforeach;
    }
    ?>
</div>
</body>
</html>

