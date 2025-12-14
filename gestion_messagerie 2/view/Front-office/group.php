<?php
// model/group.php - VERSION FINALE CORRIGÉE
require_once 'db.php';  // This stays as 'db.php' because it's in the same model folder

/**
 * Récupère tous les groupes
 */
function getAllGroups() {
    global $pdo;
    
    // Mode démo si pas de connexion BD
    if (!$pdo) {
        return [
            [
                'id' => 1,
                'name' => 'Programmation',
                'description' => 'Discussions sur la programmation et le développement',
                'subject' => 'Informatique',
                'member_count' => 24,
                'message_count' => 12,
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 days'))
            ],
            [
                'id' => 2,
                'name' => 'Mathématiques',
                'description' => 'Aide et discussions en mathématiques',
                'subject' => 'Maths',
                'member_count' => 18,
                'message_count' => 8,
                'created_at' => date('Y-m-d H:i:s', strtotime('-25 days'))
            ],
            [
                'id' => 3,
                'name' => 'Physique-Chimie',
                'description' => 'Échanges sur la physique et la chimie',
                'subject' => 'Sciences',
                'member_count' => 15,
                'message_count' => 5,
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days'))
            ],
            [
                'id' => 4,
                'name' => 'Histoire-Géo',
                'description' => 'Discussions historiques et géographiques',
                'subject' => 'Humanités',
                'member_count' => 12,
                'message_count' => 3,
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days'))
            ],
            [
                'id' => 5,
                'name' => 'Langues Étrangères',
                'description' => 'Pratique des langues étrangères',
                'subject' => 'Langues',
                'member_count' => 20,
                'message_count' => 7,
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days'))
            ],
            [
                'id' => 6,
                'name' => 'Projets Étudiants',
                'description' => 'Coordination des projets étudiants',
                'subject' => 'Projets',
                'member_count' => 32,
                'message_count' => 15,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ]
        ];
    }
    
    try {
        // Vérifier la table existe
        $stmt = $pdo->query("SHOW TABLES LIKE 'chat_groups'");
        $table = $stmt->fetch();
        
        if (!$table) {
            // Créer la table si elle n'existe pas
            $create_table = "CREATE TABLE IF NOT EXISTS chat_groups (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                subject VARCHAR(50),
                member_count INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $pdo->exec($create_table);
            
            // Insérer des données par défaut
            $default_groups = [
                ['Programmation', 'Discussions sur la programmation et le développement', 'Informatique', 24],
                ['Mathématiques', 'Aide et discussions en mathématiques', 'Maths', 18],
                ['Physique-Chimie', 'Échanges sur la physique et la chimie', 'Sciences', 15]
            ];
            
            foreach ($default_groups as $group) {
                $sql = "INSERT INTO chat_groups (name, description, subject, member_count) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($group);
            }
        }
        
        $sql = "SELECT * FROM chat_groups ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ajouter message_count si non présent
        foreach ($groups as &$group) {
            if (!isset($group['message_count'])) {
                $group['message_count'] = getGroupMessageCount($group['id']);
            }
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
        // Mode démo
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
        
        if ($group && !isset($group['message_count'])) {
            $group['message_count'] = getGroupMessageCount($group_id);
        }
        
        return $group;
        
    } catch (Exception $e) {
        error_log("Erreur getGroup: " . $e->getMessage());
        return null;
    }
}

/**
 * Récupère les messages d'un groupe (avec gestion de la suppression)
 */
function getGroupMessages($group_id) {
    global $pdo;
    
    // Mode démo si pas de connexion BD
    if (!$pdo) {
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
                'user_id' => 1, // L'utilisateur courant (vous)
                'username' => 'Vous',
                'content' => 'Je peux vous aider aussi! J\'ai de l\'expérience en Python.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
            ],
            [
                'id' => 4,
                'group_id' => 2,
                'user_id' => 4,
                'username' => 'Sophie',
                'content' => 'Quelqu\'un a compris le dernier cours sur les intégrales?',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
            ],
            [
                'id' => 5,
                'group_id' => 3,
                'user_id' => 5,
                'username' => 'Thomas',
                'content' => 'Qui veut réviser pour l\'examen de chimie ensemble?',
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours'))
            ]
        ];
        
        // Filtrer les messages supprimés
        return array_filter($demo_messages, function($msg) use ($deleted_messages, $group_id) {
            return $msg['group_id'] == $group_id && !in_array($msg['id'], $deleted_messages);
        });
    }
    
    try {
        // Vérifier si la table existe
        $table_exists = $pdo->query("SHOW TABLES LIKE 'group_messages'")->fetch();
        
        if (!$table_exists) {
            // Créer la table si elle n'existe pas
            $create_table = "CREATE TABLE IF NOT EXISTS group_messages (
                id INT PRIMARY KEY AUTO_INCREMENT,
                group_id INT NOT NULL,
                user_id INT NOT NULL,
                username VARCHAR(100) NOT NULL,
                content TEXT NOT NULL,
                is_deleted TINYINT(1) DEFAULT 0,
                deleted_by INT DEFAULT NULL,
                deleted_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_group (group_id),
                INDEX idx_user (user_id)
            )";
            $pdo->exec($create_table);
            return [];
        }
        
        // Vérifier si la colonne is_deleted existe
        $check_column = $pdo->query("SHOW COLUMNS FROM group_messages LIKE 'is_deleted'")->fetch();
        
        if ($check_column) {
            // Avec is_deleted - exclure les messages supprimés
            $sql = "SELECT * FROM group_messages 
                    WHERE group_id = ? 
                    AND (is_deleted = 0 OR is_deleted IS NULL)
                    ORDER BY created_at ASC";
        } else {
            // Sans is_deleted - récupérer tous les messages
            $sql = "SELECT * FROM group_messages 
                    WHERE group_id = ? 
                    ORDER BY created_at ASC";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$group_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Erreur getGroupMessages: " . $e->getMessage());
        return [];
    }
}

/**
 * Compte le nombre de messages dans un groupe
 */
function getGroupMessageCount($group_id) {
    global $pdo;
    
    if (!$pdo) {
        // Mode démo
        $messages = getGroupMessages($group_id);
        return count($messages);
    }
    
    try {
        $check_column = $pdo->query("SHOW COLUMNS FROM group_messages LIKE 'is_deleted'")->fetch();
        
        if ($check_column) {
            $sql = "SELECT COUNT(*) as count FROM group_messages 
                    WHERE group_id = ? 
                    AND (is_deleted = 0 OR is_deleted IS NULL)";
        } else {
            $sql = "SELECT COUNT(*) as count FROM group_messages WHERE group_id = ?";
        }
        
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
    
    if (!$pdo) {
        // Mode démo
        return [
            'success' => true,
            'id' => rand(100, 999),
            'demo_mode' => true
        ];
    }
    
    try {
        $sql = "INSERT INTO group_messages (group_id, user_id, username, content, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$group_id, $user_id, $username, $content]);
        
        if ($result) {
            return [
                'success' => true,
                'id' => $pdo->lastInsertId()
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Erreur lors de l\'insertion'
            ];
        }
        
    } catch (Exception $e) {
        error_log("Erreur addGroupMessage: " . $e->getMessage());
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * Modifie un message de groupe
 */
function updateGroupMessage($message_id, $content, $user_id = null) {
    global $pdo;
    
    if (!$pdo) {
        // Mode démo
        return ['success' => true, 'demo_mode' => true];
    }
    
    try {
        // Vérifier les permissions si user_id est fourni
        if ($user_id) {
            $sql_check = "SELECT user_id FROM group_messages WHERE id = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$message_id]);
            $message = $stmt_check->fetch();
            
            if (!$message || $message['user_id'] != $user_id) {
                return ['success' => false, 'error' => 'Permission refusée'];
            }
        }
        
        $sql = "UPDATE group_messages SET content = ?, created_at = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([trim($content), $message_id]);
        
        return ['success' => $result];
        
    } catch (Exception $e) {
        error_log("Erreur updateGroupMessage: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Supprime un message de groupe (soft delete)
 */
function deleteGroupMessage($message_id, $user_id) {
    global $pdo;
    
    if (!$pdo) {
        // Mode démo - stocker en session
        if (!isset($_SESSION['deleted_messages'])) {
            $_SESSION['deleted_messages'] = [];
        }
        
        if (!in_array($message_id, $_SESSION['deleted_messages'])) {
            $_SESSION['deleted_messages'][] = $message_id;
        }
        
        return ['success' => true, 'demo_mode' => true];
    }
    
    try {
        // Vérifier si la colonne is_deleted existe
        $check_column = $pdo->query("SHOW COLUMNS FROM group_messages LIKE 'is_deleted'")->fetch();
        
        if ($check_column) {
            // Vérifier si le message existe et si l'utilisateur est l'auteur
            $sql_check = "SELECT id, user_id, is_deleted FROM group_messages WHERE id = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$message_id]);
            $message = $stmt_check->fetch();
            
            if (!$message) {
                return ['success' => false, 'error' => 'Message non trouvé'];
            }
            
            if ($message['user_id'] != $user_id) {
                return ['success' => false, 'error' => 'Permission refusée'];
            }
            
            if ($message['is_deleted'] == 1) {
                return ['success' => false, 'error' => 'Message déjà supprimé'];
            }
            
            // Soft delete
            $sql = "UPDATE group_messages 
                    SET is_deleted = 1, 
                        deleted_by = ?, 
                        deleted_at = NOW() 
                    WHERE id = ?";
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$user_id, $message_id]);
        } else {
            // Suppression permanente
            $sql = "DELETE FROM group_messages WHERE id = ? AND user_id = ?";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$message_id, $user_id]);
        }
        
        return ['success' => $result];
        
    } catch (Exception $e) {
        error_log("Erreur deleteGroupMessage: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Recherche des groupes
 */
function searchGroups($query) {
    global $pdo;
    
    if (!$pdo) {
        // Mode démo
        $all_groups = getAllGroups();
        $results = [];
        
        foreach ($all_groups as $group) {
            if (stripos($group['name'], $query) !== false || 
                stripos($group['description'], $query) !== false ||
                stripos($group['subject'], $query) !== false) {
                $results[] = $group;
            }
        }
        
        return $results;
    }
    
    try {
        $sql = "SELECT * FROM chat_groups 
                WHERE name LIKE ? OR description LIKE ? OR subject LIKE ? 
                ORDER BY name";
        
        $stmt = $pdo->prepare($sql);
        $search_term = "%$query%";
        $stmt->execute([$search_term, $search_term, $search_term]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Erreur searchGroups: " . $e->getMessage());
        return [];
    }
}

/**
 * Récupère un message spécifique par ID
 */
function getGroupMessageById($message_id) {
    global $pdo;
    
    if (!$pdo) {
        // Mode démo
        $demo_groups = [1, 2, 3];
        if (in_array($message_id, $demo_groups)) {
            return [
                'id' => $message_id,
                'group_id' => 1,
                'user_id' => 1,
                'username' => 'Vous',
                'content' => 'Message de test',
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
        return null;
    }
    
    try {
        $check_column = $pdo->query("SHOW COLUMNS FROM group_messages LIKE 'is_deleted'")->fetch();
        
        if ($check_column) {
            $sql = "SELECT * FROM group_messages 
                    WHERE id = ? 
                    AND (is_deleted = 0 OR is_deleted IS NULL)";
        } else {
            $sql = "SELECT * FROM group_messages WHERE id = ?";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$message_id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Erreur getGroupMessageById: " . $e->getMessage());
        return null;
    }
}

/**
 * Vérifie si un utilisateur est admin d'un groupe
 * NOTE: À adapter selon votre structure
 */
function isGroupAdmin($group_id, $user_id) {
    global $pdo;
    
    if (!$pdo) {
        // Mode démo - l'utilisateur 1 est admin
        return ($user_id == 1);
    }
    
    try {
        // Vérifier si la colonne admin_id existe dans chat_groups
        $check_column = $pdo->query("SHOW COLUMNS FROM chat_groups LIKE 'admin_id'")->fetch();
        
        if ($check_column) {
            $sql = "SELECT admin_id FROM chat_groups WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$group_id]);
            $group = $stmt->fetch();
            
            return ($group && $group['admin_id'] == $user_id);
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log("Erreur isGroupAdmin: " . $e->getMessage());
        return false;
    }
}

/**
 * Récupère les messages avec informations utilisateur
 */
function getGroupMessagesWithUsers($group_id) {
    global $pdo;
    
    if (!$pdo) {
        return getGroupMessages($group_id);
    }
    
    try {
        $check_column = $pdo->query("SHOW COLUMNS FROM group_messages LIKE 'is_deleted'")->fetch();
        
        if ($check_column) {
            $sql = "SELECT gm.*, u.username, u.avatar 
                    FROM group_messages gm
                    LEFT JOIN users u ON gm.user_id = u.id
                    WHERE gm.group_id = ? 
                    AND (gm.is_deleted = 0 OR gm.is_deleted IS NULL)
                    ORDER BY gm.created_at ASC";
        } else {
            $sql = "SELECT gm.*, u.username, u.avatar 
                    FROM group_messages gm
                    LEFT JOIN users u ON gm.user_id = u.id
                    WHERE gm.group_id = ? 
                    ORDER BY gm.created_at ASC";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$group_id]);
        
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // S'assurer que username existe toujours
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

/**
 * Initialise la base de données pour les groupes (pour installation)
 */
function initGroupDatabase() {
    global $pdo;
    
    if (!$pdo) {
        return false;
    }
    
    try {
        // Créer la table chat_groups
        $sql_groups = "CREATE TABLE IF NOT EXISTS chat_groups (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            subject VARCHAR(50),
            member_count INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql_groups);
        
        // Créer la table group_messages
        $sql_messages = "CREATE TABLE IF NOT EXISTS group_messages (
            id INT PRIMARY KEY AUTO_INCREMENT,
            group_id INT NOT NULL,
            user_id INT NOT NULL,
            username VARCHAR(100) NOT NULL,
            content TEXT NOT NULL,
            is_deleted TINYINT(1) DEFAULT 0,
            deleted_by INT DEFAULT NULL,
            deleted_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_group (group_id),
            INDEX idx_user (user_id)
        )";
        $pdo->exec($sql_messages);
        
        // Insérer des groupes par défaut
        $groups = [
            ['Programmation', 'Discussions sur la programmation et le développement', 'Informatique', 24],
            ['Mathématiques', 'Aide et discussions en mathématiques', 'Maths', 18],
            ['Physique-Chimie', 'Échanges sur la physique et la chimie', 'Sciences', 15]
        ];
        
        foreach ($groups as $group) {
            $sql = "INSERT IGNORE INTO chat_groups (name, description, subject, member_count) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($group);
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log("Erreur initGroupDatabase: " . $e->getMessage());
        return false;
    }
}

/**
 * Vérifie si la base de données des groupes est initialisée
 */
function isGroupDatabaseInitialized() {
    global $pdo;
    
    if (!$pdo) {
        return true; // Mode démo toujours initialisé
    }
    
    try {
        $table1 = $pdo->query("SHOW TABLES LIKE 'chat_groups'")->fetch();
        $table2 = $pdo->query("SHOW TABLES LIKE 'group_messages'")->fetch();
        
        return ($table1 && $table2);
        
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Crée un nouveau groupe
 */
function createGroup($name, $description, $subject, $user_id) {
    global $pdo;
    
    if (!$pdo) {
        // Mode démo
        $new_id = rand(100, 999);
        return [
            'success' => true,
            'id' => $new_id,
            'demo_mode' => true
        ];
    }
    
    try {
        $sql = "INSERT INTO chat_groups (name, description, subject, member_count, created_at) 
                VALUES (?, ?, ?, 1, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$name, $description, $subject]);
        
        if ($result) {
            return [
                'success' => true,
                'id' => $pdo->lastInsertId()
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Erreur lors de la création'
            ];
        }
        
    } catch (Exception $e) {
        error_log("Erreur createGroup: " . $e->getMessage());
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

/**
 * Récupère les groupes avec statistiques
 */
function getGroupsWithStats() {
    global $pdo;
    
    if (!$pdo) {
        return getAllGroups();
    }
    
    try {
        $check_column = $pdo->query("SHOW COLUMNS FROM group_messages LIKE 'is_deleted'")->fetch();
        
        if ($check_column) {
            $sql = "SELECT 
                        g.*,
                        COUNT(DISTINCT CASE WHEN gm.is_deleted = 0 THEN gm.id END) as message_count,
                        MAX(gm.created_at) as last_message_time
                    FROM chat_groups g
                    LEFT JOIN group_messages gm ON g.id = gm.group_id
                    GROUP BY g.id
                    ORDER BY g.created_at DESC";
        } else {
            $sql = "SELECT 
                        g.*,
                        COUNT(DISTINCT gm.id) as message_count,
                        MAX(gm.created_at) as last_message_time
                    FROM chat_groups g
                    LEFT JOIN group_messages gm ON g.id = gm.group_id
                    GROUP BY g.id
                    ORDER BY g.created_at DESC";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Assurer que message_count est un entier
        foreach ($groups as &$group) {
            $group['message_count'] = (int)($group['message_count'] ?? 0);
        }
        
        return $groups;
        
    } catch (Exception $e) {
        error_log("Erreur getGroupsWithStats: " . $e->getMessage());
        return getAllGroups();
    }
}

/**
 * Vérifie si un message a été supprimé (pour le mode démo)
 */
function isMessageDeleted($message_id) {
    if (!isset($_SESSION['deleted_messages'])) {
        return false;
    }
    
    return in_array($message_id, $_SESSION['deleted_messages']);
}

/**
 * Récupère les membres d'un groupe
 */
function getGroupMembers($group_id) {
    global $pdo;
    
    if (!$pdo) {
        // Mode démo
        return [
            ['id' => 1, 'username' => 'Admin', 'joined_at' => date('Y-m-d H:i:s')],
            ['id' => 2, 'username' => 'Marie', 'joined_at' => date('Y-m-d H:i:s')],
            ['id' => 3, 'username' => 'Pierre', 'joined_at' => date('Y-m-d H:i:s')]
        ];
    }
    
    try {
        // Cette fonction suppose une table group_members
        // À adapter selon votre structure
        $sql = "SELECT u.id, u.username, u.avatar 
                FROM users u
                JOIN group_members gm ON u.id = gm.user_id
                WHERE gm.group_id = ?
                ORDER BY u.username";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$group_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Erreur getGroupMembers: " . $e->getMessage());
        return [];
    }
}

// Initialiser la base de données si nécessaire
if (isset($pdo) && $pdo && !isGroupDatabaseInitialized()) {
    initGroupDatabase();
}
?>