<?php
// model/message.php - VERSION SIMPLIFIÉE SANS SESSION
require_once 'db.php';

function getConversations($user_id) {
    global $pdo;
    
    if (!$pdo) {
        // Mode démo - retourner des conversations statiques
        return [
            [
                'user_id' => 2, 
                'username' => 'Marie', 
                'email' => 'marie@campus.com',
                'avatar' => 'M',
                'is_online' => true,
                'last_message' => 'Parfait! Je serai présent.', 
                'last_message_time' => date('Y-m-d H:i:s', strtotime('-12 hours')),
                'unread_count' => 0
            ],
            [
                'user_id' => 3, 
                'username' => 'Pierre', 
                'email' => 'pierre@campus.com',
                'avatar' => 'P',
                'is_online' => true,
                'last_message' => 'Salut Pierre!', 
                'last_message_time' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'unread_count' => 2
            ]
        ];
    }
    
    // Code pour la base de données...
    return [];
}

function getMessages($user_id, $other_user_id) {
    global $pdo;
    
    if (!$pdo) {
        // Mode démo
        $demo_messages = [];
        
        if ($other_user_id == 2) {
            $demo_messages = [
                [
                    'id' => 1,
                    'sender_id' => 1,
                    'receiver_id' => 2,
                    'content' => 'Salut Marie! Ça va?',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                    'is_read' => 1,
                    'sender_username' => 'Vous',
                    'sender_avatar' => 'V'
                ],
                [
                    'id' => 2,
                    'sender_id' => 2,
                    'receiver_id' => 1,
                    'content' => 'Oui et toi? La réunion est à 14h demain.',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                    'is_read' => 1,
                    'sender_username' => 'Marie',
                    'sender_avatar' => 'M'
                ],
                [
                    'id' => 3,
                    'sender_id' => 1,
                    'receiver_id' => 2,
                    'content' => 'Parfait! Je serai présent.',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-12 hours')),
                    'is_read' => 1,
                    'sender_username' => 'Vous',
                    'sender_avatar' => 'V'
                ]
            ];
        }
        
        if ($other_user_id == 3) {
            $demo_messages = [
                [
                    'id' => 4,
                    'sender_id' => 1,
                    'receiver_id' => 3,
                    'content' => 'Salut Pierre!',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                    'is_read' => 1,
                    'sender_username' => 'Vous',
                    'sender_avatar' => 'V'
                ]
            ];
        }
        
        foreach ($demo_messages as &$msg) {
            $msg['sender_name'] = ($msg['sender_id'] == $user_id) ? 'Vous' : $msg['sender_username'];
        }
        
        return $demo_messages;
    }
    
    // Code pour la base de données...
    return [];
}

// Gardez les autres fonctions telles quelles, mais sans session_start()
function sendMessage($sender_id, $receiver_id, $content) {
    global $pdo;
    if (!$pdo) {
        $message_id = rand(1000, 9999);
        return ['success' => true, 'id' => $message_id];
    }
    // ... code base de données
    return ['success' => false];
}

function updateMessage($message_id, $content, $user_id = null) {
    global $pdo;
    // ... code
    return false;
}

function getMessageById($message_id) {
    global $pdo;
    // ... code
    return null;
}

function deleteMessage($message_id, $user_id) {
    global $pdo;
    
    if (!$pdo) {
        // En mode démo, toujours retourner true pour la compatibilité
        return true;
    }
    
    // Code pour la base de données...
    return false;
}

function searchUsers($query, $current_user_id = null) {
    global $pdo;
    
    $users = [
        ['id' => 2, 'username' => 'Marie', 'email' => 'marie@campus.com', 'avatar' => 'M', 'is_online' => true],
        ['id' => 3, 'username' => 'Pierre', 'email' => 'pierre@campus.com', 'avatar' => 'P', 'is_online' => true],
        // ... autres utilisateurs
    ];
    
    if (!$pdo) {
        if (empty($query)) {
            return array_slice($users, 0, 5);
        }
        
        return array_filter($users, function($user) use ($query) {
            return stripos($user['username'], $query) !== false || 
                   stripos($user['email'], $query) !== false;
        });
    }
    
    // Code pour la base de données...
    return $users;
}

function getUserInfo($user_id) {
    global $pdo;
    
    $default_users = [
        1 => ['username' => 'Vous', 'email' => 'vous@campus.com', 'avatar' => 'V', 'is_online' => true],
        2 => ['username' => 'Marie', 'email' => 'marie@campus.com', 'avatar' => 'M', 'is_online' => true],
        3 => ['username' => 'Pierre', 'email' => 'pierre@campus.com', 'avatar' => 'P', 'is_online' => true],
        // ... autres
    ];
    
    if (!$pdo) {
        return $default_users[$user_id] ?? ['username' => 'Utilisateur', 'email' => '', 'avatar' => '?', 'is_online' => false];
    }
    
    // Code pour la base de données...
    return $default_users[$user_id] ?? ['username' => 'Utilisateur', 'email' => '', 'avatar' => '?', 'is_online' => false];
}
?>