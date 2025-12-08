<?php
// add_sample_messages.php - Add sample conversations
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->exec("USE campus_connect_new");
    
    // Clear existing messages
    $pdo->exec("DELETE FROM private_messages");
    
    // Add sample conversations
    $sample_messages = [
        // Conversation with Marie
        [1, 2, 'Salut Marie! Comment ça va?'],
        [2, 1, 'Bonjour! Je vais bien, merci! Et toi?'],
        [1, 2, 'Super! Tu as vu le nouvel événement sur le campus?'],
        [2, 1, 'Oui, ça a l\'air intéressant! Tu y vas?'],
        
        // Conversation with Pierre
        [1, 3, 'Hey Pierre! Tu as fini le projet de programmation?'],
        [3, 1, 'Pas encore, je travaille dessus. Tu as des questions?'],
        [1, 3, 'Oui, sur la partie base de données'],
        
        // Conversation with Sophie
        [4, 1, 'Bonjour! Tu veux réviser pour l\'examen ensemble?'],
        [1, 4, 'Bonne idée! Quand tu veux?'],
        [4, 1, 'Demain à la bibliothèque?']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO private_messages (sender_id, receiver_id, content, created_at) VALUES (?, ?, ?, NOW() - INTERVAL ? MINUTE)");
    
    $time_offset = 60; // Start from 60 minutes ago
    
    foreach ($sample_messages as $message) {
        $stmt->execute([$message[0], $message[1], $message[2], $time_offset]);
        $time_offset -= 5; // Each message 5 minutes apart
    }
    
    echo "✅ Sample messages added successfully!<br>";
    echo "<a href='views/messages.php'>Go to Messages</a>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>