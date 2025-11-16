<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<h2 class="page-title">Latest Posts</h2>

<?php
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "
    <div class='post'>
        <h3><a href='view_post.php?id={$row['id']}'>{$row['title']}</a></h3>
        <p>{$row['content']}</p>
        <small>Posted on {$row['created_at']}</small>
    </div>";
}
?>

</body>
</html>
