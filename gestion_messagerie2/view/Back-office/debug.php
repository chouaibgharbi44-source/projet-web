<?php
// debug.php - Version TRÈS SIMPLE
session_start();
echo "<h1>🔧 DEBUG SIMPLE</h1>";

// 1. Vérifier la session
echo "<h2>1. Session</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// 2. Tester la base de données
echo "<h2>2. Base de données</h2>";

// Chemin relatif vers db.php - ajustez si nécessaire
$db_path = '../../model/db.php';
if (file_exists($db_path)) {
    require_once $db_path;
    
    if (isset($pdo) && $pdo) {
        echo "✓ Connecté à la base de données<br>";
        
        // Vérifier la table group_messages
        try {
            $stmt = $pdo->query("SHOW TABLES LIKE 'group_messages'");
            $table = $stmt->fetch();
            
            if ($table) {
                echo "✓ Table 'group_messages' existe<br>";
                
                // Vérifier la structure
                echo "<h3>Structure de group_messages :</h3>";
                $stmt = $pdo->query("DESCRIBE group_messages");
                $columns = $stmt->fetchAll();
                
                echo "<table border='1' cellpadding='5'>";
                echo "<tr><th>Colonne</th><th>Type</th><th>Null</th><th>Default</th></tr>";
                foreach ($columns as $col) {
                    echo "<tr>";
                    echo "<td>" . $col['Field'] . "</td>";
                    echo "<td>" . $col['Type'] . "</td>";
                    echo "<td>" . $col['Null'] . "</td>";
                    echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                // Vérifier les messages
                echo "<h3>Messages dans la table :</h3>";
                $stmt = $pdo->query("SELECT id, group_id, user_id, username, 
                                    LEFT(content, 30) as preview, 
                                    is_deleted, created_at 
                                    FROM group_messages 
                                    ORDER BY id LIMIT 10");
                $messages = $stmt->fetchAll();
                
                if ($messages) {
                    echo "<table border='1' cellpadding='5'>";
                    echo "<tr><th>ID</th><th>Groupe</th><th>User</th><th>Username</th><th>Preview</th><th>is_deleted</th><th>Créé le</th></tr>";
                    foreach ($messages as $msg) {
                        echo "<tr>";
                        echo "<td>" . $msg['id'] . "</td>";
                        echo "<td>" . $msg['group_id'] . "</td>";
                        echo "<td>" . $msg['user_id'] . "</td>";
                        echo "<td>" . $msg['username'] . "</td>";
                        echo "<td>" . htmlspecialchars($msg['preview']) . "...</td>";
                        echo "<td>" . ($msg['is_deleted'] ?? 'NULL') . "</td>";
                        echo "<td>" . $msg['created_at'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "Aucun message dans la table<br>";
                }
            } else {
                echo "✗ Table 'group_messages' n'existe pas<br>";
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "✗ Non connecté à la base de données<br>";
        echo "Mode démo actif - messages stockés en session<br>";
    }
} else {
    echo "✗ Fichier db.php non trouvé à: " . $db_path . "<br>";
}

// 3. Tester la suppression manuellement
echo "<h2>3. Tester la suppression</h2>";

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    echo "Tentative de suppression du message ID: $delete_id<br>";
    
    if (isset($pdo) && $pdo) {
        try {
            // D'abord, voir l'état actuel
            $sql = "SELECT * FROM group_messages WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$delete_id]);
            $message = $stmt->fetch();
            
            if ($message) {
                echo "<strong>Message trouvé avant suppression:</strong><br>";
                echo "is_deleted actuel: " . ($message['is_deleted'] ?? 'NULL') . "<br>";
                
                // Essayer de supprimer
                if (isset($message['is_deleted'])) {
                    $sql = "UPDATE group_messages SET is_deleted = 1 WHERE id = ?";
                } else {
                    $sql = "DELETE FROM group_messages WHERE id = ?";
                }
                
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([$delete_id]);
                
                echo "Résultat: " . ($result ? "SUCCÈS" : "ÉCHEC") . "<br>";
                echo "Lignes affectées: " . $stmt->rowCount() . "<br>";
                
                // Vérifier après
                $sql = "SELECT * FROM group_messages WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$delete_id]);
                $after = $stmt->fetch();
                
                if ($after) {
                    echo "Message toujours présent après 'suppression'<br>";
                    echo "Nouvelle valeur is_deleted: " . ($after['is_deleted'] ?? 'NULL') . "<br>";
                } else {
                    echo "✓ Message complètement supprimé de la base<br>";
                }
            } else {
                echo "✗ Message non trouvé dans la base<br>";
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage() . "<br>";
        }
    } else {
        // Mode démo - session
        if (!isset($_SESSION['deleted_messages'])) {
            $_SESSION['deleted_messages'] = [];
        }
        
        if (!in_array($delete_id, $_SESSION['deleted_messages'])) {
            $_SESSION['deleted_messages'][] = $delete_id;
            echo "✓ Message $delete_id ajouté à la liste des supprimés en session<br>";
        } else {
            echo "⚠ Message $delete_id déjà marqué comme supprimé en session<br>";
        }
    }
}

echo "<h3>Tester avec un ID :</h3>";
echo "<form method='get'>";
echo "Message ID: <input type='number' name='delete_id' value='1' min='1'> ";
echo "<input type='submit' value='Tester la suppression'>";
echo "</form>";

// 4. Liens utiles
echo "<h2>4. Liens</h2>";
echo "<ul>";
echo "<li><a href='debug.php?delete_id=1'>Tester suppression message 1</a></li>";
echo "<li><a href='debug.php?delete_id=2'>Tester suppression message 2</a></li>";
echo "<li><a href='debug.php?delete_id=3'>Tester suppression message 3</a></li>";
echo "<li><a href='../Front-office/group_messages.php?group_id=1'>Retour au chat (groupe 1)</a></li>";
echo "</ul>";

// 5. Vérifier le fichier deletegroupmessage.php
echo "<h2>5. Fichier deletegroupmessage.php</h2>";
$delete_file = 'deletegroupmessage.php';
if (file_exists($delete_file)) {
    echo "✓ Fichier existe: " . filesize($delete_file) . " bytes<br>";
    
    // Lire les 20 premières lignes
    $lines = file($delete_file, FILE_IGNORE_NEW_LINES);
    echo "<pre>";
    for ($i = 0; $i < min(20, count($lines)); $i++) {
        echo htmlspecialchars($lines[$i]) . "\n";
    }
    echo "...</pre>";
} else {
    echo "✗ Fichier non trouvé<br>";
}
?>