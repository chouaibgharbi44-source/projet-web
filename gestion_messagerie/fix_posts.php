<?php
// fix_posts.php - Add user tracking to posts and comments
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->exec("USE campus_connect_new");
    
    // Add user_id to posts table
    $pdo->exec("ALTER TABLE posts ADD COLUMN user_id VARCHAR(100) DEFAULT 'anonymous'");
    echo "✅ Added user_id to posts table<br>";
    
    // Add user_id to comments table
    $pdo->exec("ALTER TABLE comments ADD COLUMN user_id VARCHAR(100) DEFAULT 'anonymous'");
    echo "✅ Added user_id to comments table<br>";
    
    // Update existing posts with user_id
    $pdo->exec("UPDATE posts SET user_id = 'user_1' WHERE user_id = 'anonymous'");
    echo "✅ Updated existing posts<br>";
    
    echo "<h3>✅ Database updated successfully!</h3>";
    echo "<a href='index.php'>Go to Campus Connect</a>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>