<?php include '../header.php'; ?>
<?php require '../model/post.php'; ?>


<div class="container">
<h2>Add a New Post</h2>
<form action="" method="POST">
<textarea name="content" placeholder="Write your status..." required></textarea>
<button type="submit" class="btn">Post</button>
</form>
</div>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$content = $_POST['content'];
addPost($content);
header('Location: ../index.php');
}
?>


</body>
</html>