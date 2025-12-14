<?php
// debug_messages.php
session_start();
require_once '../../model/db.php';

echo "<h2>DEBUG TABLE MESSAGES</h2>";

if ($pdo) {
    echo "✓ Connecté à la base<br>";
    
    // Vérifier la table messages
    $stmt = $pdo->query("SHOW CREATE TABLE messages");
    $table = $stmt->fetch();
    
    if ($table) {
        echo "<h3>Structure de la table messages :</h3>";
        echo "<pre>" . htmlspecialchars($table['Create Table']) . "</pre>";
        
        // Vérifier la colonne is_deleted
        $stmt = $pdo->query("SHOW COLUMNS FROM messages LIKE 'is_deleted'");
        $col = $stmt->fetch();
        
        if ($col) {
            echo "<p style='color: green;'>✓ Colonne is_deleted existe</p>";
        } else {
            echo "<p style='color: red;'>✗ Colonne is_deleted n'existe pas</p>";
        }
    } else {
        echo "✗ Table messages non trouvée<br>";
    }
} else {
    echo "✗ Non connecté<br>";
}

// Tester avec un message
echo "<h3>Tester avec un message :</h3>";
if (isset($_GET['test_id'])) {
    $test_id = (int)$_GET['test_id'];
    
    $sql = "SELECT * FROM messages WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$test_id]);
    $message = $stmt->fetch();
    
    if ($message) {
        echo "<pre>" . print_r($message, true) . "</pre>";
    } else {
        echo "Message $test_id non trouvé<br>";
    }
}

echo "<form method='get'>";
echo "Message ID: <input type='number' name='test_id' value='1'> ";
echo "<input type='submit' value='Vérifier'>";
echo "</form>";
?>