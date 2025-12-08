<?php
// setup.php - Create database and tables
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to MySQL<br>";
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS campus_connect");
    echo "✅ Database created<br>";
    
    // Use the database
    $pdo->exec("USE campus_connect");
    echo "✅ Using database<br>";
    
    // Create posts table
    $pdo->exec("CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Posts table created<br>";
    
    // Create comments table
    $pdo->exec("CREATE TABLE IF NOT EXISTS comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Comments table created<br>";
    
    // Create likes table
    $pdo->exec("CREATE TABLE IF NOT EXISTS likes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Likes table created<br>";
    
    // Insert sample data
    $pdo->exec("INSERT IGNORE INTO posts (id, content) VALUES 
        (1, 'Bienvenue sur Campus Connect! 🎓'),
        (2, 'Première publication de test!'),
        (3, 'Partagez vos idées avec la communauté!')");
    echo "✅ Sample posts added<br>";
    
    $pdo->exec("INSERT IGNORE INTO comments (post_id, content) VALUES 
        (1, 'Super plateforme!'),
        (1, 'Content de faire partie de cette communauté!'),
        (2, 'Très intéressant!')");
    echo "✅ Sample comments added<br>";
    
    echo "<h2>🎉 Database setup complete!</h2>";
    echo "<a href='index.php'>Go to Campus Connect</a>";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "Make sure MySQL is running in XAMPP!";
}
?>