<?php
// reset_database.php - Complete database reset
$host = 'localhost';
$username = 'root';
$password = '';

echo "<h3>Resetting Campus Connect Database...</h3>";

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to MySQL<br>";
    
    // Drop database if exists (this will delete all data)
    $pdo->exec("DROP DATABASE IF EXISTS campus_connect");
    echo "✅ Old database removed<br>";
    
    // Create fresh database
    $pdo->exec("CREATE DATABASE campus_connect");
    echo "✅ New database created<br>";
    
    // Use the database
    $pdo->exec("USE campus_connect");
    echo "✅ Using database<br>";
    
    // Create posts table (simplified)
    $pdo->exec("CREATE TABLE posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Posts table created<br>";
    
    // Create comments table (simplified)
    $pdo->exec("CREATE TABLE comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Comments table created<br>";
    
    // Create likes table (simplified)
    $pdo->exec("CREATE TABLE likes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Likes table created<br>";
    
    // Insert fresh sample data
    $pdo->exec("INSERT INTO posts (content) VALUES 
        ('Bienvenue sur Campus Connect! 🎓'),
        ('Première publication de test!'),
        ('Partagez vos idées avec la communauté!')");
    echo "✅ Sample posts added<br>";
    
    $pdo->exec("INSERT INTO comments (post_id, content) VALUES 
        (1, 'Super plateforme!'),
        (1, 'Content de faire partie de cette communauté!'),
        (2, 'Très intéressant!')");
    echo "✅ Sample comments added<br>";
    
    echo "<h2 style='color: green;'>🎉 Database reset complete!</h2>";
    echo "<a href='index.php'>Go to Campus Connect</a>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Critical Error</h3>";
    echo "Error: " . $e->getMessage() . "<br><br>";
    echo "<strong>Possible solutions:</strong><br>";
    echo "1. Restart your computer and try again<br>";
    echo "2. Reinstall XAMPP<br>";
    echo "3. Use the temporary data system instead<br>";
}
?>