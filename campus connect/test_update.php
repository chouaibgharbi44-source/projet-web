<?php
session_start();
// Set a specific test user
$_SESSION['user_id'] = 'test_user_123';

require_once 'model/post.php';

echo "<h3>Testing Post Update Functionality</h3>";

// Create a test post first
$test_content = "Test post for updating - " . date('H:i:s');
$result = addPost($test_content, $_SESSION['user_id']);

echo "Create post result: " . ($result ? "SUCCESS" : "FAILED") . "<br>";

// Get the post we just created
$posts = getPosts();
$test_post = $posts[0] ?? null;

if ($test_post) {
    echo "<h4>Test Post Created:</h4>";
    echo "ID: " . $test_post['id'] . "<br>";
    echo "Content: " . $test_post['content'] . "<br>";
    echo "User ID: " . $test_post['user_id'] . "<br>";
    echo "Session User ID: " . $_SESSION['user_id'] . "<br>";
    
    echo "<h4>Testing Update:</h4>";
    $new_content = "UPDATED: This post has been modified!";
    $update_result = updatePost($test_post['id'], $new_content, $_SESSION['user_id']);
    
    echo "Update result: " . ($update_result ? "SUCCESS" : "FAILED") . "<br>";
    
    // Check the updated post
    $updated_post = getPostById($test_post['id']);
    echo "Updated content: " . ($updated_post['content'] ?? 'NOT FOUND') . "<br>";
    
    echo "<h4>All Posts (for verification):</h4>";
    foreach (getPosts() as $post) {
        echo "ID: {$post['id']} - User: {$post['user_id']} - Content: {$post['content']}<br>";
    }
    
} else {
    echo "No test post created - check database connection";
}

echo "<br><a href='index.php'>Back to main site</a>";

// Check error log
echo "<h4>Check XAMPP error logs for detailed debugging info</h4>";
?>