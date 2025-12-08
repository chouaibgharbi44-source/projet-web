<?php
session_start();
require_once '../model/post.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'] ?? 'anonymous';
    
    if (!empty($content)) {
        addPost($content, $user_id);
    }
}

header("Location: ../index.php");
exit();
?>