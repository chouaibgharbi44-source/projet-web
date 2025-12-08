<?php
session_start();
// Set a test user
$_SESSION['user_id'] = 'test_user_123';

require_once 'model/post.php';

// Create a test post first
addPost("Test post for editing", $_SESSION['user_id']);

// Get the post we just created
$posts = getPosts();
$test_post = $posts[0] ?? null;

if ($test_post) {
    echo "<h3>Test Post Created:</h3>";
    echo "ID: " . $test_post['id'] . "<br>";
    echo "Content: " . $test_post['content'] . "<br>";
    echo "User ID: " . $test_post['user_id'] . "<br>";
    
    // Test updating
    $result = updatePost($test_post['id'], "This post has been edited!", $_SESSION['user_id']);
    echo "<h3>Update Test:</h3>";
    echo "Update result: " . ($result ? "SUCCESS" : "FAILED") . "<br>";
    
    // Check the updated post
    $updated_post = getPostById($test_post['id']);
    echo "Updated content: " . ($updated_post['content'] ?? 'NOT FOUND') . "<br>";
} else {
    echo "No test post created";
}

echo "<br><a href='index.php'>Back to main site</a>";
?>