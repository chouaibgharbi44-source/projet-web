<?php
session_start();
// Set a test user
$_SESSION['user_id'] = 'test_user_delete';

require_once 'model/post.php';

// Create a test post first
addPost("Test post for deletion", $_SESSION['user_id']);

// Get the post we just created
$posts = getPosts();
$test_post = $posts[0] ?? null;

if ($test_post) {
    echo "<h3>Test Post Created:</h3>";
    echo "ID: " . $test_post['id'] . "<br>";
    echo "Content: " . $test_post['content'] . "<br>";
    echo "User ID: " . $test_post['user_id'] . "<br>";
    
    echo "<h3>Testing Delete:</h3>";
    echo "<a href='views/deletepost.php?post_id=" . $test_post['id'] . "' target='_blank'>Click here to delete this test post</a><br>";
    echo "After clicking, check if the post disappears from the main page.";
    
} else {
    echo "No test post created";
}

echo "<br><br><a href='index.php'>Back to main site</a>";
?>