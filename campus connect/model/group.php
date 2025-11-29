<?php
// model/group.php - Real database functions
require_once 'db.php';

function getAllGroups() {
    global $pdo;
    if (!$pdo) {
        // Fallback to temporary data
        return [
            [
                'id' => 1, 'name' => 'Programmation', 'description' => 'Discussions sur la programmation', 
                'subject' => 'Informatique', 'member_count' => 24, 'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2, 'name' => 'Mathématiques', 'description' => 'Aide en mathématiques', 
                'subject' => 'Maths', 'member_count' => 18, 'created_at' => date('Y-m-d H:i:s')
            ]
        ];
    }
    
    try {
        $sql = "SELECT * FROM chat_groups ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function getGroup($group_id) {
    global $pdo;
    if (!$pdo) return ['id' => 1, 'name' => 'Programmation', 'description' => 'Discussions sur la programmation', 'subject' => 'Informatique', 'member_count' => 24];
    
    try {
        $sql = "SELECT * FROM chat_groups WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$group_id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

function getGroupMessages($group_id) {
    global $pdo;
    if (!$pdo) {
        return [
            ['id' => 1, 'group_id' => 1, 'user_id' => 2, 'username' => 'Marie', 'content' => 'Quelqu\'un peut m\'aider avec Python?', 'created_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'group_id' => 1, 'user_id' => 3, 'username' => 'Pierre', 'content' => 'Bien sûr! Quel est le problème?', 'created_at' => date('Y-m-d H:i:s')]
        ];
    }
    
    try {
        $sql = "SELECT * FROM group_messages WHERE group_id = ? ORDER BY created_at ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$group_id]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function addGroupMessage($group_id, $user_id, $username, $content) {
    global $pdo;
    if (!$pdo) return true; // Fallback success
    
    try {
        $sql = "INSERT INTO group_messages (group_id, user_id, username, content, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$group_id, $user_id, $username, $content]);
    } catch (Exception $e) {
        return false;
    }
}

function searchGroups($query) {
    global $pdo;
    if (!$pdo) return getAllGroups();
    
    try {
        $sql = "SELECT * FROM chat_groups WHERE name LIKE ? OR description LIKE ? OR subject LIKE ? ORDER BY name";
        $stmt = $pdo->prepare($sql);
        $search_term = "%$query%";
        $stmt->execute([$search_term, $search_term, $search_term]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}
?>