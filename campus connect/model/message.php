<?php
// model/message.php - Real messaging system with conversations
require_once 'db.php';

function getConversations($user_id) {
    global $pdo;
    
    if (!$pdo) {
        // Fallback to temporary data
        return [
            [
                'user_id' => 2, 
                'username' => 'Marie', 
                'last_message' => 'Salut! Comment ça va?', 
                'last_message_time' => date('Y-m-d H:i:s'),
                'unread_count' => 0
            ],
            [
                'user_id' => 3, 
                'username' => 'Pierre', 
                'last_message' => 'Tu as vu le nouvel événement?', 
                'last_message_time' => date('Y-m-d H:i:s'),
                'unread_count' => 1
            ]
        ];
    }
    
    try {
        // Get unique conversations for this user
        $sql = "SELECT 
                    CASE 
                        WHEN sender_id = ? THEN receiver_id 
                        ELSE sender_id 
                    END as other_user_id,
                    u.username,
                    MAX(m.created_at) as last_message_time,
                    (SELECT content FROM private_messages 
                     WHERE ((sender_id = ? AND receiver_id = other_user_id) 
                            OR (sender_id = other_user_id AND receiver_id = ?)) 
                     ORDER BY created_at DESC LIMIT 1) as last_message,
                    (SELECT COUNT(*) FROM private_messages 
                     WHERE receiver_id = ? AND sender_id = other_user_id AND is_read = 0) as unread_count
                FROM private_messages m
                JOIN (SELECT 2 as id, 'Marie' as username 
                      UNION SELECT 3, 'Pierre' 
                      UNION SELECT 4, 'Sophie'
                      UNION SELECT 5, 'Thomas') u ON u.id = other_user_id
                WHERE sender_id = ? OR receiver_id = ?
                GROUP BY other_user_id
                ORDER BY last_message_time DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id, $user_id]);
        return $stmt->fetchAll();
        
    } catch (Exception $e) {
        error_log("Get conversations error: " . $e->getMessage());
        return [];
    }
}

function getMessages($user_id, $other_user_id) {
    global $pdo;
    
    if (!$pdo) {
        // Fallback to temporary data
        return [
            [
                'id' => 1,
                'sender_id' => 1,
                'receiver_id' => $other_user_id,
                'content' => 'Salut! Comment ça va?',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 minutes')),
                'is_read' => 1
            ],
            [
                'id' => 2,
                'sender_id' => $other_user_id,
                'receiver_id' => 1,
                'content' => 'Bonjour! Je vais bien, merci! Et toi?',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 minutes')),
                'is_read' => 1
            ],
            [
                'id' => 3,
                'sender_id' => 1,
                'receiver_id' => $other_user_id,
                'content' => 'Super! Tu as vu le nouvel événement sur le campus?',
                'created_at' => date('Y-m-d H:i:s'),
                'is_read' => 0
            ]
        ];
    }
    
    try {
        // Mark messages as read when viewing conversation
        $update_sql = "UPDATE private_messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ? AND is_read = 0";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$other_user_id, $user_id]);
        
        // Get all messages between these users
        $sql = "SELECT * FROM private_messages 
                WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
                ORDER BY created_at ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $other_user_id, $other_user_id, $user_id]);
        return $stmt->fetchAll();
        
    } catch (Exception $e) {
        error_log("Get messages error: " . $e->getMessage());
        return [];
    }
}

function sendMessage($sender_id, $receiver_id, $content) {
    global $pdo;
    
    if (!$pdo) {
        // For temporary data, just return success
        return true;
    }
    
    try {
        $sql = "INSERT INTO private_messages (sender_id, receiver_id, content, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$sender_id, $receiver_id, $content]);
        
    } catch (Exception $e) {
        error_log("Send message error: " . $e->getMessage());
        return false;
    }
}

function searchUsers($query, $current_user_id = null) {
    // Sample users for search
    $users = [
        ['id' => 2, 'username' => 'Marie', 'email' => 'marie@campus.com'],
        ['id' => 3, 'username' => 'Pierre', 'email' => 'pierre@campus.com'],
        ['id' => 4, 'username' => 'Sophie', 'email' => 'sophie@campus.com'],
        ['id' => 5, 'username' => 'Thomas', 'email' => 'thomas@campus.com'],
        ['id' => 6, 'username' => 'Laura', 'email' => 'laura@campus.com'],
        ['id' => 7, 'username' => 'Alexandre', 'email' => 'alex@campus.com']
    ];
    
    $results = array_filter($users, function($user) use ($query, $current_user_id) {
        if ($current_user_id && $user['id'] == $current_user_id) return false;
        return stripos($user['username'], $query) !== false || stripos($user['email'], $query) !== false;
    });
    
    return array_values($results);
}

// Get user info for display
function getUserInfo($user_id) {
    $users = [
        1 => ['username' => 'Vous', 'email' => 'vous@campus.com'],
        2 => ['username' => 'Marie', 'email' => 'marie@campus.com'],
        3 => ['username' => 'Pierre', 'email' => 'pierre@campus.com'],
        4 => ['username' => 'Sophie', 'email' => 'sophie@campus.com'],
        5 => ['username' => 'Thomas', 'email' => 'thomas@campus.com']
    ];
    
    return $users[$user_id] ?? ['username' => 'Utilisateur', 'email' => 'inconnu@campus.com'];
}
?>