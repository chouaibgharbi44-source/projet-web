<?php
// setup_database.php - Complete database setup
$host = 'localhost';
$username = 'root';
$password = '';

echo "<h3>Setting up Campus Connect Database...</h3>";

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
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
    )");
    echo "✅ Comments table created<br>";
    
    // Create likes table
    $pdo->exec("CREATE TABLE IF NOT EXISTS likes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
    )");
    echo "✅ Likes table created<br>";
    
    // Create messages table
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT DEFAULT 1,
        receiver_id INT DEFAULT 1,
        content TEXT NOT NULL,
        is_read TINYINT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Messages table created<br>";
    
    // Create groups table
    $pdo->exec("CREATE TABLE IF NOT EXISTS groups (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        subject VARCHAR(50),
        member_count INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Groups table created<br>";
    
    // Create group_messages table
    $pdo->exec("CREATE TABLE IF NOT EXISTS group_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        group_id INT NOT NULL,
        user_id INT DEFAULT 1,
        username VARCHAR(50),
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
    )");
    echo "✅ Group messages table created<br>";
    
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
    
    $pdo->exec("INSERT IGNORE INTO groups (id, name, description, subject, member_count) VALUES 
        (1, 'Programmation', 'Discussions sur la programmation et le développement', 'Informatique', 24),
        (2, 'Mathématiques', 'Aide et discussions en mathématiques', 'Maths', 18),
        (3, 'Physique-Chimie', 'Échanges sur la physique et la chimie', 'Sciences', 15),
        (4, 'Histoire-Géo', 'Discussions historiques et géographiques', 'Humanités', 12),
        (5, 'Langues Étrangères', 'Pratique des langues étrangères', 'Langues', 20),
        (6, 'Projets Étudiants', 'Coordination des projets étudiants', 'Projets', 32)");
    echo "✅ Sample groups added<br>";
    
    $pdo->exec("INSERT IGNORE INTO group_messages (group_id, user_id, username, content) VALUES 
        (1, 2, 'Marie', 'Quelqu''un peut m''aider avec un problème en Python?'),
        (1, 3, 'Pierre', 'Bien sûr! Quel est le problème?'),
        (1, 1, 'Jean', 'Moi aussi je peux aider, j''adore Python!'),
        (2, 4, 'Sophie', 'Quelqu''un a compris le dernier cours sur les intégrales?')");
    echo "✅ Sample group messages added<br>";
    
    $pdo->exec("INSERT IGNORE INTO messages (sender_id, receiver_id, content) VALUES 
        (1, 2, 'Salut Marie! Comment ça va?'),
        (2, 1, 'Bonjour Jean! Je vais bien, merci! Et toi?'),
        (1, 3, 'Tu as vu le nouvel événement sur le campus?')");
    echo "✅ Sample messages added<br>";
    
    echo "<h2 style='color: green;'>🎉 Database setup complete!</h2>";
    echo "<a href='index.php'>Go to Campus Connect</a>";
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>❌ Error</h3>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Make sure MySQL is running in XAMPP!";
}
?>