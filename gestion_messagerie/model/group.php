<?php
// model/group.php - VERSION FINALE CORRIGÉE
require_once 'db.php';

/**
 * Récupère tous les groupes
 */
function getAllGroups() {
    global $pdo;
    
    if (!$pdo) {
        return [
            [
                'id' => 1,
                'name' => 'Programmation',
                'description' => 'Discussions sur la programmation et le développement',
                'subject' => 'Informatique',
                'member_count' => 24,
                'message_count' => 3,
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
            ],
            [
                'id' => 2,
                'name' => 'Mathématiques',
                'description' => 'Aide et discussions en mathématiques',
                'subject' => 'Maths',
                'member_count' => 18,
                'message_count' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-25 days'))
            ]
        ];
    }
    
    try {
        $sql = "SELECT * FROM chat_groups ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ajouter message_count à chaque groupe
        foreach ($groups as &$group) {
            $group['message_count'] = getGroupMessageCount($group['id']);
        }
        
        return $groups;
        
    } catch (Exception $e) {
        error_log("Erreur getAllGroups: " . $e->getMessage());
        return [];
    }
}

/**
 * Récupère un groupe spécifique
 */
function getGroup($group_id) {
    global $pdo;
    
    if (!$pdo) {
        $demo_groups = getAllGroups();
        foreach ($demo_groups as $group) {
            if ($group['id'] == $group_id) {
                return $group;
            }
        }
        return null;
    }
    
    try {
        $sql = "SELECT * FROM chat_groups WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$group_id]);
        
        $group = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($group) {
            $group['message_count'] = getGroupMessageCount($group_id);
        }
        
        return $group;
        
    } catch (Exception $e) {
        error_log("Erreur getGroup: " . $e->getMessage());
        return null;
    }
}

/**
 * Récupère les messages d'un groupe - CORRIGÉ POUR FILTRER is_deleted
 */
function getGroupMessages($group_id) {
    global $pdo;
    
    error_log("getGroupMessages appelé pour groupe $group_id");
    
    if (!$pdo) {
        // Mode démo
        $deleted_messages = isset($_SESSION['deleted_messages']) ? $_SESSION['deleted_messages'] : [];
        
        $demo_messages = [
            [
                'id' => 1,
                'group_id' => 1,
                'user_id' => 2,
                'username' => 'Marie',
                'content' => 'Quelqu\'un peut m\'aider avec un problème en Python?',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
            ],
            [
                'id' => 2,
                'group_id' => 1,
                'user_id' => 3,
                'username' => 'Pierre',
                'content' => 'Bien sûr! Quel est le problème?',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ],
            [
                'id' => 3,
                'group_id' => 1,
                'user_id' => 1,
                'username' => 'Jean',
                'content' => 'Moi aussi je peux aider, j\'adore Python!',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
            ],
            [
                'id' => 5,
                'group_id' => 1,
                'user_id' => 1,
                'username' => 'Jean',
                'content' => 'hello',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 minutes'))
            ]
        ];
        
        // Filtrer les messages supprimés
        $filtered = array_filter($demo_messages, function($msg) use ($deleted_messages, $group_id) {
            $is_in_group = $msg['group_id'] == $group_id;
            $is_deleted = in_array($msg['id'], $deleted_messages);
            
            error_log("Message {$msg['id']}: groupe=$is_in_group, supprimé=$is_deleted");
            return $is_in_group && !$is_deleted;
        });
        
        error_log("Messages après filtre: " . count($filtered));
        return array_values($filtered);
    }
    
    try {
        // IMPORTANT: Filtrer par is_deleted = 0
        $sql = "SELECT * FROM group_messages 
                WHERE group_id = ? 
                AND (is_deleted = 0 OR is_deleted IS NULL)
                ORDER BY created_at ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$group_id]);
        
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("Messages BD pour groupe $group_id: " . count($messages));
        
        return $messages;
        
    } catch (Exception $e) {
        error_log("Erreur getGroupMessages: " . $e->getMessage());
        return [];
    }
}

/**
 * Compte le nombre de messages non supprimés dans un groupe
 */
function getGroupMessageCount($group_id) {
    global $pdo;
    
    if (!$pdo) {
        $messages = getGroupMessages($group_id);
        return count($messages);
    }
    
    try {
        $sql = "SELECT COUNT(*) as count FROM group_messages 
                WHERE group_id = ? 
                AND (is_deleted = 0 OR is_deleted IS NULL)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$group_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['count'] ?? 0;
        
    } catch (Exception $e) {
        error_log("Erreur getGroupMessageCount: " . $e->getMessage());
        return 0;
    }
}

/**
 * Ajoute un message à un groupe
 */
function addGroupMessage($group_id, $user_id, $username, $content) {
    global $pdo;
    
    error_log("addGroupMessage: groupe=$group_id, user=$user_id, content=" . substr($content, 0, 50));
    
    if (!$pdo) {
        $new_id = rand(100, 999);
        return [
            'success' => true,
            'id' => $new_id,
            'demo_mode' => true
        ];
    }
    
    try {
        $sql = "INSERT INTO group_messages (group_id, user_id, username, content, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$group_id, $user_id, $username, $content]);
        
        if ($result) {
            $id = $pdo->lastInsertId();
            error_log("Message ajouté avec ID: $id");
            return [
                'success' => true,
                'id' => $id
            ];
        }
        
        return ['success' => false, 'error' => 'Erreur insertion'];
        
    } catch (Exception $e) {
        error_log("Erreur addGroupMessage: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Supprime un message de groupe - VERSION CORRIGÉE
 */
function deleteGroupMessage($message_id, $user_id) {
    global $pdo;
    
    error_log("deleteGroupMessage: message=$message_id, user=$user_id");
    
    if (!$pdo) {
        // Mode démo
        if (!isset($_SESSION['deleted_messages'])) {
            $_SESSION['deleted_messages'] = [];
        }
        
        if (!in_array($message_id, $_SESSION['deleted_messages'])) {
            $_SESSION['deleted_messages'][] = $message_id;
            error_log("Message $message_id ajouté à deleted_messages");
        } else {
            error_log("Message $message_id déjà dans deleted_messages");
        }
        
        return ['success' => true, 'demo_mode' => true];
    }
    
    try {
        // 1. Vérifier si le message existe et si l'utilisateur est l'auteur
        $sql_check = "SELECT id, user_id, is_deleted FROM group_messages WHERE id = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$message_id]);
        $message = $stmt_check->fetch();
        
        if (!$message) {
            error_log("Message $message_id non trouvé");
            return ['success' => false, 'error' => 'Message non trouvé'];
        }
        
        // 2. Vérifier si déjà supprimé
        if (isset($message['is_deleted']) && $message['is_deleted'] == 1) {
            error_log("Message $message_id déjà supprimé (is_deleted=1)");
            return ['success' => false, 'error' => 'Message déjà supprimé'];
        }
        
        // 3. Vérifier si l'utilisateur est l'auteur
        if ($message['user_id'] != $user_id) {
            error_log("Permission refusée: user $user_id n'est pas l'auteur");
            return ['success' => false, 'error' => 'Permission refusée'];
        }
        
        // 4. Soft delete
        $sql_delete = "UPDATE group_messages 
                      SET is_deleted = 1, 
                          deleted_by = ?, 
                          deleted_at = NOW() 
                      WHERE id = ?";
        
        $stmt_delete = $pdo->prepare($sql_delete);
        $result = $stmt_delete->execute([$user_id, $message_id]);
        
        if ($result) {
            $rows = $stmt_delete->rowCount();
            error_log("Soft delete réussi pour message $message_id, lignes affectées: $rows");
            return ['success' => true];
        } else {
            error_log("Échec soft delete pour message $message_id");
            return ['success' => false, 'error' => 'Échec suppression'];
        }
        
    } catch (Exception $e) {
        error_log("Exception deleteGroupMessage: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Modifie un message de groupe
 */
function updateGroupMessage($message_id, $content, $user_id = null) {
    global $pdo;
    
    if (!$pdo) {
        return ['success' => true, 'demo_mode' => true];
    }
    
    try {
        if ($user_id) {
            $sql_check = "SELECT user_id FROM group_messages WHERE id = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$message_id]);
            $message = $stmt_check->fetch();
            
            if (!$message || $message['user_id'] != $user_id) {
                return ['success' => false, 'error' => 'Permission refusée'];
            }
        }
        
        $sql = "UPDATE group_messages SET content = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([trim($content), $message_id]);
        
        return ['success' => $result];
        
    } catch (Exception $e) {
        error_log("Erreur updateGroupMessage: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Récupère un message par ID (uniquement non supprimé)
 */
function getGroupMessageById($message_id) {
    global $pdo;
    
    if (!$pdo) {
        return ['id' => $message_id, 'user_id' => 1, 'username' => 'Vous', 'content' => 'Message test'];
    }
    
    try {
        $sql = "SELECT * FROM group_messages 
                WHERE id = ? 
                AND (is_deleted = 0 OR is_deleted IS NULL)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$message_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Erreur getGroupMessageById: " . $e->getMessage());
        return null;
    }
}

/**
 * Vérifie si un utilisateur est admin
 */
function isGroupAdmin($group_id, $user_id) {
    global $pdo;
    
    if (!$pdo) {
        return ($user_id == 1);
    }
    
    try {
        // À adapter selon votre structure
        return false;
        
    } catch (Exception $e) {
        error_log("Erreur isGroupAdmin: " . $e->getMessage());
        return false;
    }
}

/**
 * Recherche des groupes
 */
function searchGroups($query) {
    global $pdo;
    
    if (!$pdo) {
        $all_groups = getAllGroups();
        $results = [];
        
        foreach ($all_groups as $group) {
            if (stripos($group['name'], $query) !== false || 
                stripos($group['description'], $query) !== false) {
                $results[] = $group;
            }
        }
        
        return $results;
    }
    
    try {
        $sql = "SELECT * FROM chat_groups 
                WHERE name LIKE ? OR description LIKE ? 
                ORDER BY name";
        
        $stmt = $pdo->prepare($sql);
        $search_term = "%$query%";
        $stmt->execute([$search_term, $search_term]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Erreur searchGroups: " . $e->getMessage());
        return [];
    }
}

/**
 * Récupère les messages avec infos utilisateur
 */
function getGroupMessagesWithUsers($group_id) {
    global $pdo;
    
    if (!$pdo) {
        return getGroupMessages($group_id);
    }
    
    try {
        $sql = "SELECT gm.*, u.username, u.avatar 
                FROM group_messages gm
                LEFT JOIN users u ON gm.user_id = u.id
                WHERE gm.group_id = ? 
                AND (gm.is_deleted = 0 OR gm.is_deleted IS NULL)
                ORDER BY gm.created_at ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$group_id]);
        
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // S'assurer que username existe
        foreach ($messages as &$msg) {
            if (empty($msg['username'])) {
                $msg['username'] = 'Utilisateur';
            }
        }
        
        return $messages;
        
    } catch (Exception $e) {
        error_log("Erreur getGroupMessagesWithUsers: " . $e->getMessage());
        return getGroupMessages($group_id);
    }
}
?>