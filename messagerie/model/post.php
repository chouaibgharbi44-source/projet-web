<?php
function connectDB() {
    $host = "localhost";
    $db = "tasks";
    $user = "root";
    $pass = "";

    try {
        return new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

/* ---------------- POSTS ---------------- */

function getPosts() {
    $db = connectDB();
    $stmt = $db->query("SELECT * FROM posts ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addPost($content) {
    $db = connectDB();
    $stmt = $db->prepare("INSERT INTO posts (content, created_at) VALUES (?, NOW())");
    $stmt->execute([$content]);
}

function deletePost($post_id) {
    $db = connectDB();
    $stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
}

/* ---------------- COMMENTS ---------------- */

function getComments($post_id) {
    $db = connectDB();
    $stmt = $db->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at ASC");
    $stmt->execute([$post_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addComment($post_id, $content) {
    $db = connectDB();
    $stmt = $db->prepare("INSERT INTO comments (post_id, content, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$post_id, $content]);
}

function deleteComment($comment_id) {
    $db = connectDB();
    $stmt = $db->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$comment_id]);
}

/* ---------------- LIKES ---------------- */

function countLikes($post_id) {
    $db = connectDB();
    $stmt = $db->prepare("SELECT COUNT(*) FROM post_likes WHERE post_id = ?");
    $stmt->execute([$post_id]);
    return $stmt->fetchColumn();
}

function addLike($post_id) {
    $db = connectDB();
    $stmt = $db->prepare("INSERT INTO post_likes (post_id, created_at) VALUES (?, NOW())");
    $stmt->execute([$post_id]);
}

?>
