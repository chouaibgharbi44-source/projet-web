<?php include "includes/header.php"; ?>

<h2 class="page-title">Create a New Status</h2>

<form action="add_post.php" method="POST" class="post-form">
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="content" placeholder="Write your status..." required></textarea>
    <button type="submit">Publish</button>
</form>

</body>
</html>
