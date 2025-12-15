<?php
// model/user.php - Version avec noms UNIQUES
require_once 'db.php';

/**
 * Récupère les informations d'un utilisateur
 * Nom différent pour éviter les conflits
 */
function getUserDetails($user_id) {
    global $pdo;
    
    if (!$pdo) {
        $default_users = [
            1 => ['id' => 1, 'username' => 'Vous', 'email' => 'vous@campus.com', 'avatar' => 'V', 'is_online' => true],
            2 => ['id' => 2, 'username' => 'Marie', 'email' => 'marie@campus.com', 'avatar' => 'M', 'is_online' => true],
            3 => ['id' => 3, 'username' => 'Pierre', 'email' => 'pierre@campus.com', 'avatar' => 'P', 'is_online' => true],
            4 => ['id' => 4, 'username' => 'Sophie', 'email' => 'sophie@campus.com', 'avatar' => 'S', 'is_online' => false],
            5 => ['id' => 5, 'username' => 'Thomas', 'email' => 'thomas@campus.com', 'avatar' => 'T', 'is_online' => true],
            6 => ['id' => 6, 'username' => 'Laura', 'email' => 'laura@campus.com', 'avatar' => 'L', 'is_online' => false],
            7 => ['id' => 7, 'username' => 'Alexandre', 'email' => 'alex@campus.com', 'avatar' => 'A', 'is_online' => true],
            8 => ['id' => 8, 'username' => 'Julie', 'email' => 'julie@campus.com', 'avatar' => 'J', 'is_online' => false],
            9 => ['id' => 9, 'username' => 'Nicolas', 'email' => 'nico@campus.com', 'avatar' => 'N', 'is_online' => true]
        ];
        
        return $default_users[$user_id] ?? ['id' => $user_id, 'username' => 'Utilisateur', 'email' => '', 'avatar' => '?', 'is_online' => false];
    }
    
    try {
        $sql = "SELECT id, username, email, avatar, is_online FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if ($user) {
            return $user;
        }
        
        return ['id' => $user_id, 'username' => 'Utilisateur', 'email' => '', 'avatar' => '?', 'is_online' => false];
    } catch (Exception $e) {
        error_log("Erreur getUserDetails: " . $e->getMessage());
        return ['id' => $user_id, 'username' => 'Utilisateur', 'email' => '', 'avatar' => '?', 'is_online' => false];
    }
}

/**
 * Recherche des utilisateurs
 */
function searchAllUsers($query, $current_user_id = null) {
    global $pdo;
    
    $users = [
        ['id' => 2, 'username' => 'Marie', 'email' => 'marie@campus.com', 'avatar' => 'M', 'is_online' => true],
        ['id' => 3, 'username' => 'Pierre', 'email' => 'pierre@campus.com', 'avatar' => 'P', 'is_online' => true],
        ['id' => 4, 'username' => 'Sophie', 'email' => 'sophie@campus.com', 'avatar' => 'S', 'is_online' => false],
        ['id' => 5, 'username' => 'Thomas', 'email' => 'thomas@campus.com', 'avatar' => 'T', 'is_online' => true],
        ['id' => 6, 'username' => 'Laura', 'email' => 'laura@campus.com', 'avatar' => 'L', 'is_online' => false],
        ['id' => 7, 'username' => 'Alexandre', 'email' => 'alex@campus.com', 'avatar' => 'A', 'is_online' => true],
        ['id' => 8, 'username' => 'Julie', 'email' => 'julie@campus.com', 'avatar' => 'J', 'is_online' => false],
        ['id' => 9, 'username' => 'Nicolas', 'email' => 'nico@campus.com', 'avatar' => 'N', 'is_online' => true]
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
    
    try {
        $sql = "SELECT id, username, email, avatar, is_online FROM users 
                WHERE (username LIKE :query OR email LIKE :query)";
        
        if ($current_user_id) {
            $sql .= " AND id != :current_user";
        }
        
        $sql .= " ORDER BY username LIMIT 10";
        
        $stmt = $pdo->prepare($sql);
        $search_term = "%$query%";
        
        $params = ['query' => $search_term];
        if ($current_user_id) {
            $params['current_user'] = $current_user_id;
        }
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Erreur searchAllUsers: " . $e->getMessage());
        return [];
    }
}
?>