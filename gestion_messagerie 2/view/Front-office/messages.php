<?php
// views/messages.php - VERSION CORRIGÉE POUR L'ÉDITION
session_start();

// Pour la démo
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'Vous';
}

if (!isset($_SESSION['deleted_messages'])) {
    $_SESSION['deleted_messages'] = [];
}

if (!isset($_SESSION['sent_messages'])) {
    $_SESSION['sent_messages'] = [];
}

if (!isset($_SESSION['edited_messages'])) {
    $_SESSION['edited_messages'] = [];
}

// GESTION AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $action = $_POST['action'];
    $user_id = $_SESSION['user_id'];
    
    // SUPPRESSION DE MESSAGE
    if ($action === 'delete_message') {
        $message_id = $_POST['message_id'] ?? '';
        
        // Si c'est un ID numérique
        if (is_numeric($message_id)) {
            $message_id = (int)$message_id;
            
            // Ajouter aux messages supprimés
            if (!in_array($message_id, $_SESSION['deleted_messages'])) {
                $_SESSION['deleted_messages'][] = $message_id;
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Message supprimé avec succès',
                'message_id' => $message_id
            ]);
        } else {
            // Message temporaire - le supprimer des sent_messages
            $temp_id = $message_id;
            if (isset($_SESSION['sent_messages'][$temp_id])) {
                unset($_SESSION['sent_messages'][$temp_id]);
            }
            echo json_encode(['success' => true, 'message' => 'Message temporaire supprimé']);
        }
        exit();
    }
    
    // ÉDITION DE MESSAGE - VERSION CORRIGÉE
    if ($action === 'edit_message') {
        $message_id = $_POST['message_id'] ?? '';
        $new_content = $_POST['content'] ?? '';
        
        error_log("=== TENTATIVE D'ÉDITION ===");
        error_log("Message ID reçu: " . $message_id);
        error_log("Type: " . gettype($message_id));
        error_log("Nouveau contenu: " . $new_content);
        
        if (!$message_id || !$new_content) {
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            exit();
        }
        
        $new_content = trim($new_content);
        if (empty($new_content)) {
            echo json_encode(['success' => false, 'message' => 'Le message ne peut pas être vide']);
            exit();
        }
        
        // Vérifier si c'est un ID numérique (messages de base)
        if (is_numeric($message_id)) {
            $message_id = (int)$message_id;
            
            error_log("C'est un ID numérique: " . $message_id);
            
            // Messages de base
            $base_messages = [
                1 => ['id' => 1, 'sender_id' => 1, 'receiver_id' => 2, 'content' => 'Salut Marie! Ça va?'],
                2 => ['id' => 2, 'sender_id' => 2, 'receiver_id' => 1, 'content' => 'Oui et toi? La réunion est à 14h demain.'],
                3 => ['id' => 3, 'sender_id' => 1, 'receiver_id' => 2, 'content' => 'Parfait! Je serai présent.'],
                4 => ['id' => 4, 'sender_id' => 1, 'receiver_id' => 3, 'content' => 'Salut Pierre!'],
            ];
            
            // Vérifier si le message existe dans les messages de base
            if (isset($base_messages[$message_id])) {
                // Vérifier les permissions
                if ($base_messages[$message_id]['sender_id'] == $user_id) {
                    $_SESSION['edited_messages'][$message_id] = $new_content;
                    
                    error_log("Message de base édité avec succès: " . $message_id);
                    
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Message modifié avec succès',
                        'message_id' => $message_id,
                        'content' => $new_content
                    ]);
                    exit();
                } else {
                    error_log("Pas l'auteur du message: " . $message_id);
                    echo json_encode(['success' => false, 'message' => 'Vous n\'êtes pas l\'auteur de ce message']);
                    exit();
                }
            }
            
            // Vérifier dans les messages envoyés
            foreach ($_SESSION['sent_messages'] as $key => $msg) {
                if ($msg['id'] == $message_id) {
                    // Vérifier les permissions
                    if ($msg['sender_id'] == $user_id) {
                        $_SESSION['sent_messages'][$key]['content'] = $new_content;
                        $_SESSION['sent_messages'][$key]['is_edited'] = true;
                        
                        error_log("Message envoyé édité avec succès: " . $message_id);
                        
                        echo json_encode([
                            'success' => true, 
                            'message' => 'Message modifié avec succès',
                            'message_id' => $message_id,
                            'content' => $new_content
                        ]);
                        exit();
                    } else {
                        error_log("Pas l'auteur du message envoyé: " . $message_id);
                        echo json_encode(['success' => false, 'message' => 'Vous n\'êtes pas l\'auteur de ce message']);
                        exit();
                    }
                }
            }
        } else {
            // C'est probablement un temp ID (chaîne)
            error_log("C'est un temp ID: " . $message_id);
            
            // Chercher dans les messages envoyés
            foreach ($_SESSION['sent_messages'] as $key => $msg) {
                if ($key == $message_id || $msg['id'] == $message_id) {
                    // Vérifier les permissions
                    if ($msg['sender_id'] == $user_id) {
                        $_SESSION['sent_messages'][$key]['content'] = $new_content;
                        $_SESSION['sent_messages'][$key]['is_edited'] = true;
                        
                        error_log("Temp ID édité avec succès: " . $message_id);
                        
                        echo json_encode([
                            'success' => true, 
                            'message' => 'Message modifié avec succès',
                            'temp_id' => $message_id,
                            'content' => $new_content
                        ]);
                        exit();
                    } else {
                        error_log("Pas l'auteur du temp ID: " . $message_id);
                        echo json_encode(['success' => false, 'message' => 'Vous n\'êtes pas l\'auteur de ce message']);
                        exit();
                    }
                }
            }
        }
        
        error_log("Message non trouvé nulle part: " . $message_id);
        echo json_encode(['success' => false, 'message' => 'Message non trouvé (ID: ' . $message_id . ')']);
        exit();
    }
    
    // ENVOI DE MESSAGE
    if ($action === 'send_message') {
        $receiver_id = (int)($_POST['receiver_id'] ?? 0);
        $content = $_POST['content'] ?? '';
        
        if (!$receiver_id || !$content) {
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            exit();
        }
        
        // Créer un nouveau message
        $new_message_id = time() . rand(1000, 9999);
        
        $new_message = [
            'id' => $new_message_id,
            'sender_id' => $user_id,
            'receiver_id' => $receiver_id,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s'),
            'sender_username' => 'Vous',
            'sender_avatar' => 'V',
            'is_read' => 1,
            'original_content' => $content
        ];
        
        // Ajouter aux messages envoyés
        $_SESSION['sent_messages'][$new_message_id] = $new_message;
        
        echo json_encode([
            'success' => true, 
            'id' => $new_message_id,
            'message' => 'Message envoyé avec succès'
        ]);
        exit();
    }
    
    // OBTENIR UN MESSAGE POUR ÉDITION - VERSION CORRIGÉE
    if ($action === 'get_message') {
        $message_id = $_POST['message_id'] ?? '';
        
        error_log("=== DEMANDE DE MESSAGE POUR ÉDITION ===");
        error_log("Message ID demandé: " . $message_id);
        error_log("Type: " . gettype($message_id));
        
        // Chercher dans les messages de base
        $base_messages = [
            1 => ['id' => 1, 'sender_id' => 1, 'receiver_id' => 2, 'content' => 'Salut Marie! Ça va?'],
            2 => ['id' => 2, 'sender_id' => 2, 'receiver_id' => 1, 'content' => 'Oui et toi? La réunion est à 14h demain.'],
            3 => ['id' => 3, 'sender_id' => 1, 'receiver_id' => 2, 'content' => 'Parfait! Je serai présent.'],
            4 => ['id' => 4, 'sender_id' => 1, 'receiver_id' => 3, 'content' => 'Salut Pierre!'],
        ];
        
        $message = null;
        
        // Essayer d'abord comme ID numérique
        if (is_numeric($message_id)) {
            $numeric_id = (int)$message_id;
            error_log("Recherche comme ID numérique: " . $numeric_id);
            
            // Chercher dans les messages de base
            if (isset($base_messages[$numeric_id])) {
                $message = $base_messages[$numeric_id];
                error_log("Trouvé dans messages de base: " . $numeric_id);
                
                // Appliquer les éditions si existantes
                if (isset($_SESSION['edited_messages'][$numeric_id])) {
                    $message['content'] = $_SESSION['edited_messages'][$numeric_id];
                }
            } else {
                // Chercher dans les messages envoyés
                foreach ($_SESSION['sent_messages'] as $msg) {
                    if ($msg['id'] == $numeric_id) {
                        $message = $msg;
                        error_log("Trouvé dans messages envoyés: " . $numeric_id);
                        break;
                    }
                }
            }
        } else {
            // C'est probablement un temp ID (chaîne)
            error_log("Recherche comme temp ID: " . $message_id);
            
            // Chercher dans les messages envoyés
            if (isset($_SESSION['sent_messages'][$message_id])) {
                $message = $_SESSION['sent_messages'][$message_id];
                error_log("Trouvé comme temp ID: " . $message_id);
            } else {
                // Essayer de chercher par ID dans les messages envoyés
                foreach ($_SESSION['sent_messages'] as $msg) {
                    if ($msg['id'] == $message_id) {
                        $message = $msg;
                        error_log("Trouvé par ID dans messages envoyés: " . $message_id);
                        break;
                    }
                }
            }
        }
        
        if ($message) {
            error_log("Message trouvé, envoi réponse...");
            echo json_encode([
                'success' => true,
                'message' => $message
            ]);
        } else {
            error_log("Message non trouvé: " . $message_id);
            echo json_encode([
                'success' => false,
                'message' => 'Message non trouvé (ID: ' . $message_id . ')',
                'debug_info' => [
                    'requested_id' => $message_id,
                    'type' => gettype($message_id),
                    'sent_messages_keys' => array_keys($_SESSION['sent_messages']),
                    'edited_messages' => array_keys($_SESSION['edited_messages'])
                ]
            ]);
        }
        exit();
    }
}

// DONNÉES DE DÉMO
$demo_users = [
    1 => ['id' => 1, 'username' => 'Vous', 'email' => 'vous@campus.com', 'avatar' => 'V', 'is_online' => true],
    2 => ['id' => 2, 'username' => 'Marie', 'email' => 'marie@campus.com', 'avatar' => 'M', 'is_online' => true],
    3 => ['id' => 3, 'username' => 'Pierre', 'email' => 'pierre@campus.com', 'avatar' => 'P', 'is_online' => true],
];

// Messages de base
$base_messages = [
    1 => ['id' => 1, 'sender_id' => 1, 'receiver_id' => 2, 'content' => 'Salut Marie! Ça va?', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')), 'sender_username' => 'Vous', 'sender_avatar' => 'V', 'original_content' => 'Salut Marie! Ça va?'],
    2 => ['id' => 2, 'sender_id' => 2, 'receiver_id' => 1, 'content' => 'Oui et toi? La réunion est à 14h demain.', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')), 'sender_username' => 'Marie', 'sender_avatar' => 'M', 'original_content' => 'Oui et toi? La réunion est à 14h demain.'],
    3 => ['id' => 3, 'sender_id' => 1, 'receiver_id' => 2, 'content' => 'Parfait! Je serai présent.', 'created_at' => date('Y-m-d H:i:s', strtotime('-12 hours')), 'sender_username' => 'Vous', 'sender_avatar' => 'V', 'original_content' => 'Parfait! Je serai présent.'],
    4 => ['id' => 4, 'sender_id' => 1, 'receiver_id' => 3, 'content' => 'Salut Pierre!', 'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')), 'sender_username' => 'Vous', 'sender_avatar' => 'V', 'original_content' => 'Salut Pierre!'],
];

// Appliquer les éditions aux messages de base
foreach ($base_messages as $id => &$msg) {
    if (isset($_SESSION['edited_messages'][$id])) {
        $msg['content'] = $_SESSION['edited_messages'][$id];
        $msg['is_edited'] = true;
    }
}

// Fusionner tous les messages
$all_messages = array_merge($base_messages, $_SESSION['sent_messages']);
$deleted_messages = $_SESSION['deleted_messages'];

// Fonction pour obtenir les conversations
function getConversationsList($user_id, $all_messages, $deleted_messages, $demo_users) {
    $conversations = [];
    $user_conversations = [];
    
    // Trouver tous les utilisateurs avec qui on a conversé
    foreach ($all_messages as $msg) {
        if (is_array($msg)) {
            $msg_id = $msg['id'];
            if (in_array($msg_id, $deleted_messages)) {
                continue;
            }
            
            if ($msg['sender_id'] == $user_id) {
                $other_user_id = $msg['receiver_id'];
            } else if ($msg['receiver_id'] == $user_id) {
                $other_user_id = $msg['sender_id'];
            } else {
                continue;
            }
            
            if (!isset($user_conversations[$other_user_id])) {
                $user_conversations[$other_user_id] = [
                    'last_message' => $msg['content'],
                    'last_time' => $msg['created_at'],
                    'unread' => 0
                ];
            } else {
                if (strtotime($msg['created_at']) > strtotime($user_conversations[$other_user_id]['last_time'])) {
                    $user_conversations[$other_user_id] = [
                        'last_message' => $msg['content'],
                        'last_time' => $msg['created_at'],
                        'unread' => 0
                    ];
                }
            }
        }
    }
    
    // Construire le tableau des conversations
    foreach ($user_conversations as $other_user_id => $conv_data) {
        if (isset($demo_users[$other_user_id])) {
            $user = $demo_users[$other_user_id];
            
            $conversations[] = [
                'user_id' => $other_user_id,
                'username' => $user['username'],
                'email' => $user['email'],
                'avatar' => $user['avatar'],
                'is_online' => $user['is_online'],
                'last_message' => $conv_data['last_message'],
                'last_message_time' => $conv_data['last_time'],
                'unread_count' => $conv_data['unread']
            ];
        }
    }
    
    // Trier par date
    usort($conversations, function($a, $b) {
        return strtotime($b['last_message_time']) - strtotime($a['last_message_time']);
    });
    
    return $conversations;
}

// Obtenir les conversations
$conversations = getConversationsList($_SESSION['user_id'], $all_messages, $deleted_messages, $demo_users);

// Messages pour le destinataire actuel
$current_receiver = null;
$messages = [];

if (isset($_GET['receiver_id'])) {
    $receiver_id = (int)$_GET['receiver_id'];
    $current_receiver = $demo_users[$receiver_id] ?? null;
    
    if ($current_receiver) {
        foreach ($all_messages as $msg) {
            if (is_array($msg)) {
                if (($msg['sender_id'] == $_SESSION['user_id'] && $msg['receiver_id'] == $receiver_id) ||
                    ($msg['sender_id'] == $receiver_id && $msg['receiver_id'] == $_SESSION['user_id'])) {
                    
                    $msg_id = $msg['id'];
                    if (!in_array($msg_id, $deleted_messages)) {
                        $msg['sender_name'] = ($msg['sender_id'] == $_SESSION['user_id']) ? 'Vous' : $msg['sender_username'];
                        
                        // Appliquer les éditions pour l'affichage
                        if (isset($_SESSION['edited_messages'][$msg_id])) {
                            $msg['content'] = $_SESSION['edited_messages'][$msg_id];
                            $msg['is_edited'] = true;
                        }
                        
                        // Pour les messages envoyés
                        if (isset($msg['is_edited']) && $msg['is_edited']) {
                            // Le contenu est déjà correct
                        }
                        
                        $messages[] = $msg;
                    }
                }
            }
        }
        
        usort($messages, function($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie - Campus Connect</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern pink theme using Poppins and 3-color gradient */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --purple-1: #7b2da8;
            --rose-1: #ff6fb1;
            --dark-blue: #0b2545;
            --muted: #3a2a3a;
            --surface: rgba(255, 255, 255, 0.9);
            --glass: rgba(255, 255, 255, 0.6);
            --success-green: #2ecc71;
            --danger-red: #e63946;
            --border-radius: 14px;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            --shadow-hover: 0 20px 50px rgba(123, 45, 168, 0.12);
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: var(--muted);
            background: radial-gradient(1200px 600px at 10% 10%, rgba(123, 45, 168, 0.08), transparent 12%),
                radial-gradient(1000px 500px at 90% 90%, rgba(255, 111, 177, 0.06), transparent 12%),
                linear-gradient(135deg, #fffafc 0%, #fff 100%);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        .main-content {
            padding: 28px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .messages-container {
            display: flex;
            height: calc(100vh - 160px);
            gap: 28px;
            margin-top: 20px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .conversations-sidebar {
            width: 350px;
            background: var(--surface);
            border-radius: var(--border-radius);
            padding: 24px;
            overflow-y: auto;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .conversations-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--purple-1), var(--rose-1), var(--dark-blue));
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .conversations-header {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(123, 45, 168, 0.08);
        }

        .conversations-title {
            color: var(--dark-blue);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .title-icon {
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            box-shadow: 0 8px 20px rgba(123, 45, 168, 0.2);
        }

        .search-users {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 14px 50px 14px 18px;
            border: 1px solid rgba(123, 45, 168, 0.15);
            border-radius: 12px;
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.9);
            font-family: 'Poppins', sans-serif;
        }

        .search-input:focus {
            border-color: var(--rose-1);
            box-shadow: 0 0 0 3px rgba(255, 111, 177, 0.1);
            background: white;
        }

        .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--rose-1);
            font-size: 16px;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-hover);
            border: 1px solid rgba(123, 45, 168, 0.1);
            z-index: 1000;
            display: none;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 8px;
            backdrop-filter: blur(10px);
        }

        .search-result-item {
            padding: 14px 18px;
            border-bottom: 1px solid rgba(123, 45, 168, 0.05);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-result-item:hover {
            background: linear-gradient(90deg, rgba(123, 45, 168, 0.06), rgba(255, 111, 177, 0.04));
            transform: translateX(4px);
        }

        .conversations-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .conversation-item {
            display: flex;
            padding: 16px;
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition);
            align-items: center;
            border: 1px solid transparent;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .conversation-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(123, 45, 168, 0.1);
            border-color: rgba(255, 111, 177, 0.2);
            background: linear-gradient(90deg, rgba(255, 250, 253, 0.8), rgba(255, 240, 250, 0.8));
        }

        .conversation-item.active {
            background: linear-gradient(90deg, rgba(123, 45, 168, 0.1), rgba(255, 111, 177, 0.08));
            border-color: rgba(123, 45, 168, 0.2);
            box-shadow: 0 10px 30px rgba(123, 45, 168, 0.08);
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            margin-right: 16px;
            flex-shrink: 0;
            position: relative;
            box-shadow: 0 8px 20px rgba(123, 45, 168, 0.2);
        }

        .user-avatar.online::after {
            content: '';
            position: absolute;
            bottom: 4px;
            right: 4px;
            width: 12px;
            height: 12px;
            background: var(--success-green);
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .conversation-info {
            flex: 1;
            min-width: 0;
        }

        .conversation-info h4 {
            margin: 0 0 6px 0;
            font-size: 15px;
            font-weight: 600;
            color: var(--dark-blue);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .conversation-preview {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.4;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .conversation-time {
            font-size: 0.75rem;
            color: var(--rose-1);
            font-weight: 600;
            white-space: nowrap;
            margin-top: 4px;
        }

        .unread-badge {
            background: linear-gradient(135deg, var(--rose-1), #ff3d9e);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            margin-top: 6px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(255, 111, 177, 0.2);
        }

        .chat-area {
            flex: 1;
            background: var(--surface);
            border-radius: var(--border-radius);
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .chat-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--purple-1), var(--rose-1), var(--dark-blue));
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .chat-header {
            padding: 24px;
            background: linear-gradient(90deg, rgba(123, 45, 168, 0.1), rgba(255, 111, 177, 0.08));
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(123, 45, 168, 0.08);
            position: relative;
        }

        .chat-user-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .chat-user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
            position: relative;
            box-shadow: 0 8px 20px rgba(123, 45, 168, 0.2);
        }

        .chat-user-avatar.online::after {
            content: '';
            position: absolute;
            bottom: 4px;
            right: 4px;
            width: 12px;
            height: 12px;
            background: var(--success-green);
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .chat-user-details h3 {
            margin: 0 0 6px 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-blue);
        }

        .chat-user-status {
            font-size: 0.9rem;
            color: var(--rose-1);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .chat-user-status span {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .messages-list {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
            background: linear-gradient(180deg, rgba(255, 250, 253, 0.8) 0%, rgba(255, 245, 250, 0.6) 100%);
        }

        .message {
            max-width: 75%;
            padding: 18px 22px;
            border-radius: 20px;
            position: relative;
            animation: messageSlideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            line-height: 1.5;
            word-wrap: break-word;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .message.sent {
            align-self: flex-end;
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            color: white;
            border-bottom-right-radius: 8px;
            box-shadow: 0 10px 30px rgba(123, 45, 168, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .message.received {
            align-self: flex-start;
            background: white;
            color: var(--dark-text);
            border: 1px solid rgba(123, 45, 168, 0.1);
            border-bottom-left-radius: 8px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.03);
        }

        .message-content {
            font-size: 0.95rem;
            line-height: 1.5;
            word-break: break-word;
            position: relative;
        }

        .message-content.edited::after {
            content: ' (modifié)';
            font-size: 0.8rem;
            opacity: 0.8;
            font-style: italic;
            margin-left: 8px;
        }

        .message.sent .message-content.edited::after {
            color: rgba(255, 255, 255, 0.8);
        }

        .message.received .message-content.edited::after {
            color: rgba(123, 45, 168, 0.6);
        }

        .message-time {
            font-size: 0.75rem;
            opacity: 0.8;
            margin-top: 10px;
            text-align: right;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .message-actions {
            display: flex;
            gap: 8px;
            margin-left: auto;
        }

        .edit-message-btn,
        .delete-message-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
            backdrop-filter: blur(5px);
        }

        .message.sent .edit-message-btn:hover,
        .message.sent .delete-message-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        .message.received .edit-message-btn,
        .message.received .delete-message-btn {
            background: rgba(123, 45, 168, 0.08);
            color: var(--purple-1);
        }

        .message.received .edit-message-btn:hover {
            background: rgba(123, 45, 168, 0.15);
            color: var(--purple-1);
            transform: translateY(-2px);
        }

        .message.received .delete-message-btn:hover {
            background: rgba(230, 57, 70, 0.15);
            color: var(--danger-red);
            transform: translateY(-2px);
        }

        .message-form-container {
            padding: 24px;
            background: white;
            border-top: 1px solid rgba(123, 45, 168, 0.08);
            backdrop-filter: blur(10px);
        }

        .message-form {
            display: flex;
            gap: 16px;
            align-items: flex-end;
        }

        .message-input-container {
            flex: 1;
            position: relative;
        }

        .message-input {
            width: 100%;
            padding: 16px 24px;
            padding-right: 80px;
            border: 1px solid rgba(123, 45, 168, 0.15);
            border-radius: 16px;
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition);
            background: rgba(255, 250, 253, 0.9);
            resize: none;
            min-height: 60px;
            max-height: 120px;
            font-family: 'Poppins', sans-serif;
            line-height: 1.5;
        }

        .message-input:focus {
            border-color: var(--rose-1);
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 111, 177, 0.1);
            transform: translateY(-2px);
        }

        .input-actions {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 8px;
        }

        .input-action-btn {
            background: none;
            border: none;
            color: var(--rose-1);
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            font-size: 18px;
        }

        .input-action-btn:hover {
            background: rgba(255, 111, 177, 0.1);
            color: var(--purple-1);
            transform: translateY(-2px);
        }

        .send-button {
            padding: 16px 28px;
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            color: white;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 700;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 12px 30px rgba(123, 45, 168, 0.2);
            position: relative;
            overflow: hidden;
        }

        .send-button:hover:not(:disabled) {
            transform: translateY(-4px);
            box-shadow: 0 20px 50px rgba(123, 45, 168, 0.3);
        }

        .send-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .send-button:after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.14);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease, opacity 0.6s ease;
            opacity: 0;
        }

        .send-button:active:after {
            width: 300px;
            height: 300px;
            opacity: 1;
            transition: 0s;
        }

        .no-chat-selected {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--muted);
            text-align: center;
            flex-direction: column;
            gap: 24px;
            padding: 60px;
            background: linear-gradient(180deg, rgba(255, 250, 253, 0.8) 0%, rgba(255, 245, 250, 0.6) 100%);
        }

        .no-chat-icon {
            font-size: 80px;
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .no-chat-selected h3 {
            color: var(--dark-blue);
            font-size: 1.8rem;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .no-chat-selected p {
            max-width: 500px;
            line-height: 1.6;
            margin-bottom: 24px;
            font-size: 1rem;
            color: var(--muted);
        }

        .tip-box {
            padding: 20px;
            background: linear-gradient(90deg, rgba(123, 45, 168, 0.1), rgba(255, 111, 177, 0.08));
            border-radius: 16px;
            color: var(--purple-1);
            font-weight: 600;
            border: 1px solid rgba(123, 45, 168, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(123, 45, 168, 0.08);
        }

        .tip-box i {
            font-size: 24px;
        }

        /* Modal d'édition - Updated to match theme */
        .edit-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
            animation: fadeIn 200ms ease;
        }

        .edit-modal-content {
            background: linear-gradient(145deg, #ffffff, #fdfaff);
            border-radius: 20px;
            padding: 40px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(123, 45, 168, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.8);
            animation: slideUp 300ms cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .edit-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .edit-modal-header h3 {
            color: var(--dark-blue);
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--purple-1), var(--rose-1));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .close-edit-modal {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--rose-1);
            cursor: pointer;
            padding: 8px;
            transition: var(--transition);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-edit-modal:hover {
            background: rgba(255, 111, 177, 0.1);
            transform: rotate(90deg);
        }

        .edit-modal-textarea {
            width: 100%;
            min-height: 150px;
            padding: 20px;
            border: 1px solid rgba(123, 45, 168, 0.15);
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            resize: vertical;
            margin-bottom: 24px;
            background: rgba(255, 255, 255, 0.95);
            transition: var(--transition);
        }

        .edit-modal-textarea:focus {
            border-color: var(--rose-1);
            outline: none;
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 111, 177, 0.1);
            transform: translateY(-2px);
        }

        .edit-modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .edit-modal-save, .edit-modal-cancel {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
        }

        .edit-modal-save {
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            color: white;
            box-shadow: 0 10px 25px rgba(123, 45, 168, 0.2);
        }

        .edit-modal-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(123, 45, 168, 0.3);
        }

        .edit-modal-cancel {
            background: rgba(123, 45, 168, 0.08);
            color: var(--purple-1);
        }

        .edit-modal-cancel:hover {
            background: rgba(123, 45, 168, 0.15);
            transform: translateY(-3px);
        }

        /* Modal de suppression - Updated to match theme */
        .delete-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 10001;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
            animation: fadeIn 200ms ease;
        }

        .delete-modal-content {
            background: linear-gradient(145deg, #ffffff, #fdfaff);
            border-radius: 20px;
            padding: 40px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(123, 45, 168, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.8);
            animation: slideUp 300ms cubic-bezier(0.16, 1, 0.3, 1);
            text-align: center;
        }

        .delete-modal-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .delete-modal-icon {
            font-size: 48px;
            background: linear-gradient(135deg, var(--danger-red), #ff3d4a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
        }

        .delete-modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin-bottom: 12px;
        }

        .delete-modal-subtitle {
            font-size: 1rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .delete-modal-body {
            margin-bottom: 24px;
        }

        .delete-warning {
            padding: 20px;
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.08), rgba(255, 193, 7, 0.06));
            border: 1px solid rgba(255, 193, 7, 0.15);
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .warning-text {
            color: #856404;
            font-size: 0.95rem;
            line-height: 1.5;
            font-weight: 500;
        }

        .delete-modal-footer {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .delete-modal-cancel,
        .delete-modal-confirm {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
        }

        .delete-modal-cancel {
            background: rgba(123, 45, 168, 0.08);
            color: var(--purple-1);
        }

        .delete-modal-cancel:hover {
            background: rgba(123, 45, 168, 0.15);
            transform: translateY(-3px);
        }

        .delete-modal-confirm {
            background: linear-gradient(135deg, var(--danger-red), #ff3d4a);
            color: white;
            box-shadow: 0 10px 25px rgba(230, 57, 70, 0.2);
        }

        .delete-modal-confirm:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(230, 57, 70, 0.3);
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(123, 45, 168, 0.05);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--rose-1), #ff3d9e);
        }

        /* Responsive */
        @media screen and (max-width: 992px) {
            .messages-container {
                flex-direction: column;
                height: auto;
                min-height: calc(100vh - 160px);
            }
            
            .conversations-sidebar {
                width: 100%;
                max-height: 350px;
            }
            
            .main-content {
                padding: 20px;
            }
        }

        @media screen and (max-width: 768px) {
            .message-form {
                flex-direction: column;
                gap: 12px;
            }
            
            .send-button {
                width: 100%;
                justify-content: center;
            }
            
            .message {
                max-width: 85%;
            }
            
            .edit-modal-content,
            .delete-modal-content {
                padding: 30px 20px;
                width: 95%;
            }
            
            .edit-modal-actions,
            .delete-modal-footer {
                flex-direction: column;
            }
            
            .edit-modal-save,
            .edit-modal-cancel,
            .delete-modal-cancel,
            .delete-modal-confirm {
                width: 100%;
            }
        }

        /* Floating animation for messages */
        .message.sent {
            animation: floatMessage 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes floatMessage {
            0% {
                transform: translateX(100px) scale(0.8);
                opacity: 0;
            }
            100% {
                transform: translateX(0) scale(1);
                opacity: 1;
            }
        }

        /* Notification styling */
        .notification-success,
        .notification-error,
        .notification-info {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            animation: slideInNotification 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
            backdrop-filter: blur(10px);
            max-width: 400px;
        }

        .notification-success {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            border-left: 4px solid #27ae60;
        }

        .notification-error {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border-left: 4px solid #c0392b;
        }

        .notification-info {
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            border-left: 4px solid var(--rose-1);
        }

        @keyframes slideInNotification {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutNotification {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Message typing indicator */
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 12px 16px;
            background: white;
            border-radius: 20px;
            border: 1px solid rgba(123, 45, 168, 0.1);
            align-self: flex-start;
            max-width: 80px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-8px);
            }
        }

        /* Message read status */
        .message-read-status {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-left: 8px;
        }

        .message.received .message-read-status {
            color: rgba(123, 45, 168, 0.6);
        }
    </style>
</head>
<body>
    <?php include '../../header.php'; ?>
    
    <!-- Modal d'édition -->
    <div id="editModal" class="edit-modal">
        <div class="edit-modal-content">
            <div class="edit-modal-header">
                <h3><i class="fas fa-edit"></i> Modifier le message</h3>
                <button class="close-edit-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <textarea id="editModalTextarea" class="edit-modal-textarea" placeholder="Modifiez votre message..."></textarea>
            <div class="edit-modal-actions">
                <button class="edit-modal-cancel" onclick="closeEditModal()">Annuler</button>
                <button class="edit-modal-save" onclick="saveEditedMessage()">Enregistrer</button>
            </div>
        </div>
    </div>
    
    <!-- Modal de suppression simple -->
    <div id="deleteModal" class="delete-modal">
        <div class="delete-modal-content">
            <div class="delete-modal-header">
                <div class="delete-modal-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="delete-modal-title">Supprimer le message</h3>
                <p class="delete-modal-subtitle">Voulez-vous vraiment supprimer ce message ?</p>
            </div>
            
            <div class="delete-modal-body">
                <div class="delete-warning">
                    <div class="warning-text">
                        Cette action est irréversible. Le message sera supprimé de votre vue.
                    </div>
                </div>
            </div>
            
            <div class="delete-modal-footer">
                <button class="delete-modal-cancel" onclick="closeDeleteModal()">Annuler</button>
                <button class="delete-modal-confirm" onclick="confirmDelete()">Supprimer</button>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <div class="messages-container">
            <!-- Sidebar des conversations -->
            <div class="conversations-sidebar">
                <div class="conversations-header">
                    <h2 class="conversations-title">
                        <div class="title-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        Messages
                    </h2>
                    
                    <!-- Barre de recherche -->
                    <div class="search-users">
                        <input type="text" class="search-input" id="userSearch" 
                               placeholder="Rechercher un utilisateur...">
                        <div class="search-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="search-results" id="searchResults"></div>
                    </div>
                </div>
                
                <!-- Liste des conversations -->
                <div class="conversations-list" id="conversationsList">
                    <?php if (empty($conversations)): ?>
                        <div style="text-align: center; padding: 30px 15px; color: var(--muted);">
                            <i class="fas fa-comment-slash" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.3;"></i>
                            <p style="font-size: 1rem; margin-bottom: 8px; font-weight: 600;">Aucune conversation</p>
                            <small>Envoyez un message pour commencer</small>
                        </div>
                    <?php else: ?>
                        <?php foreach ($conversations as $conv): ?>
                            <div class="conversation-item <?= isset($_GET['receiver_id']) && $_GET['receiver_id'] == $conv['user_id'] ? 'active' : '' ?>" 
                                 onclick="openChat(<?= $conv['user_id'] ?>, '<?= htmlspecialchars($conv['username']) ?>')">
                                <div class="user-avatar <?= ($conv['is_online'] ?? false) ? 'online' : '' ?>">
                                    <?= htmlspecialchars(substr($conv['username'], 0, 1)) ?>
                                </div>
                                <div class="conversation-info">
                                    <h4>
                                        <?= htmlspecialchars($conv['username']) ?>
                                        <?php if ($conv['is_online'] ?? false): ?>
                                            <span style="color: var(--success-green); font-size: 0.7rem;">● En ligne</span>
                                        <?php endif; ?>
                                    </h4>
                                    <div class="conversation-preview">
                                        <?= htmlspecialchars($conv['last_message']) ?>
                                        <?php if (strlen($conv['last_message']) > 50): ?>
                                            ...
                                        <?php endif; ?>
                                    </div>
                                    <div class="conversation-time">
                                        <?= date('H:i', strtotime($conv['last_message_time'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Zone de chat -->
            <div class="chat-area">
                <?php if ($current_receiver): ?>
                    <!-- En-tête du chat -->
                    <div class="chat-header">
                        <div class="chat-user-info">
                            <div class="chat-user-avatar <?= ($current_receiver['is_online'] ?? false) ? 'online' : '' ?>">
                                <?= htmlspecialchars(substr($current_receiver['username'], 0, 1)) ?>
                            </div>
                            <div class="chat-user-details">
                                <h3><?= htmlspecialchars($current_receiver['username']) ?></h3>
                                <div class="chat-user-status">
                                    <?php if ($current_receiver['is_online'] ?? false): ?>
                                        <span style="color: var(--success-green);">● En ligne</span>
                                    <?php else: ?>
                                        <span>Hors ligne</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Liste des messages -->
                    <div class="messages-list" id="messagesList">
                        <?php if (empty($messages)): ?>
                            <div style="text-align: center; padding: 40px 20px; color: var(--muted);">
                                <i class="far fa-comment-dots" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.3;"></i>
                                <p style="font-size: 1rem; margin-bottom: 8px; font-weight: 600;">Aucun message</p>
                                <small>Envoyez votre premier message !</small>
                            </div>
                        <?php else: ?>
                            <?php foreach ($messages as $msg): 
                                $is_sent = $msg['sender_id'] == $_SESSION['user_id'];
                                $is_edited = isset($msg['is_edited']) || 
                                            (isset($_SESSION['edited_messages'][$msg['id']]) && 
                                             $_SESSION['edited_messages'][$msg['id']] !== ($msg['original_content'] ?? $msg['content']));
                                
                                // S'assurer que l'ID est correctement formaté
                                $msg_id = $msg['id'];
                            ?>
                                <div class="message <?= $is_sent ? 'sent' : 'received' ?>" 
                                     id="message-<?= $msg_id ?>"
                                     data-message-id="<?= $msg_id ?>"
                                     data-original-content="<?= htmlspecialchars($msg['original_content'] ?? $msg['content']) ?>">
                                    <div class="message-content <?= $is_edited ? 'edited' : '' ?>">
                                        <?= nl2br(htmlspecialchars($msg['content'])) ?>
                                    </div>
                                    <div class="message-time">
                                        <span><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                                        <?php if ($is_sent): ?>
                                            <div class="message-actions">
                                                <button class="edit-message-btn" onclick="openEditModal('<?= $msg_id ?>')">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </button>
                                                <button class="delete-message-btn" onclick="showDeleteModal('<?= $msg_id ?>', this)">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Formulaire d'envoi -->
                    <div class="message-form-container">
                        <form id="messageForm" class="message-form" onsubmit="return sendPrivateMessage(event)">
                            <input type="hidden" id="receiver_id" value="<?= $_GET['receiver_id'] ?? '' ?>">
                            <div class="message-input-container">
                                <textarea id="messageInput" class="message-input" 
                                          placeholder="Écrivez votre message..." 
                                          required rows="1"></textarea>
                                <div class="input-actions">
                                    <button type="button" class="input-action-btn" title="Émojis">
                                        <i class="far fa-smile"></i>
                                    </button>
                                    <button type="button" class="input-action-btn" title="Pièce jointe">
                                        <i class="fas fa-paperclip"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" id="sendButton" class="send-button">
                                <i class="fas fa-paper-plane"></i>
                                <span>Envoyer</span>
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Aucune conversation sélectionnée -->
                    <div class="no-chat-selected">
                        <div>
                            <div class="no-chat-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h3>Sélectionnez une conversation</h3>
                            <p>
                                Choisissez une conversation dans la liste<br>
                                ou recherchez un utilisateur pour commencer
                            </p>
                        </div>
                        <div class="tip-box">
                            <i class="fas fa-lightbulb"></i>
                            <span>Cliquez sur "Marie" pour voir une conversation exemple</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    // Variables globales
    let currentEditingMessageId = null;
    let currentDeletingMessageId = null;
    let currentDeletingButton = null;
    
    // Ouvrir une conversation
    function openChat(userId, username) {
        window.location.href = 'messages.php?receiver_id=' + userId;
    }
    
    // Afficher le modal de suppression
    function showDeleteModal(messageId, button) {
        currentDeletingMessageId = messageId;
        currentDeletingButton = button;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    // Fermer le modal de suppression
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        currentDeletingMessageId = null;
        currentDeletingButton = null;
    }
    
    // Confirmer la suppression
    async function confirmDelete() {
        if (!currentDeletingMessageId) return;
        
        const messageElement = document.getElementById('message-' + currentDeletingMessageId);
        if (!messageElement) {
            closeDeleteModal();
            showNotification('Message non trouvé', 'error');
            return;
        }
        
        // Animation
        messageElement.style.opacity = '0.6';
        
        try {
            const formData = new FormData();
            formData.append('action', 'delete_message');
            formData.append('message_id', currentDeletingMessageId);
            
            const response = await fetch('', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Animation de suppression
                messageElement.style.transition = 'all 0.3s ease';
                messageElement.style.opacity = '0';
                messageElement.style.transform = 'scale(0.9)';
                messageElement.style.maxHeight = '0';
                messageElement.style.margin = '0';
                messageElement.style.padding = '0';
                messageElement.style.overflow = 'hidden';
                
                setTimeout(() => {
                    messageElement.remove();
                    checkIfNoMessages();
                    closeDeleteModal();
                    showNotification('✅ Message supprimé avec succès!', 'success');
                    
                    // Rafraîchir après un court délai
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }, 300);
            } else {
                messageElement.style.opacity = '1';
                showNotification('❌ ' + data.message, 'error');
                closeDeleteModal();
            }
        } catch (error) {
            console.error('Erreur:', error);
            messageElement.style.opacity = '1';
            showNotification('❌ Erreur de connexion', 'error');
            closeDeleteModal();
        }
    }
    
    // Ouvrir le modal d'édition - VERSION CORRIGÉE
    async function openEditModal(messageId) {
        console.log('Ouverture édition message ID:', messageId);
        console.log('Type ID:', typeof messageId);
        
        currentEditingMessageId = messageId;
        
        try {
            // Charger le contenu du message
            const formData = new FormData();
            formData.append('action', 'get_message');
            formData.append('message_id', messageId);
            
            console.log('Envoi requête pour message ID:', messageId);
            
            const response = await fetch('', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            console.log('Réponse reçue:', data);
            
            if (data.success) {
                document.getElementById('editModalTextarea').value = data.message.content;
                document.getElementById('editModal').style.display = 'flex';
                
                // Focus sur le textarea
                setTimeout(() => {
                    const textarea = document.getElementById('editModalTextarea');
                    textarea.focus();
                    textarea.select();
                }, 100);
            } else {
                console.error('Erreur chargement message:', data.message);
                showNotification('❌ Erreur: ' + data.message, 'error');
                
                // Afficher les infos de débogage si disponibles
                if (data.debug_info) {
                    console.error('Infos débogage:', data.debug_info);
                }
            }
        } catch (error) {
            console.error('Erreur fetch:', error);
            showNotification('❌ Erreur de connexion', 'error');
        }
    }
    
    // Fermer le modal d'édition
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
        currentEditingMessageId = null;
        document.getElementById('editModalTextarea').value = '';
    }
    
    // Enregistrer les modifications - VERSION CORRIGÉE
    async function saveEditedMessage() {
        if (!currentEditingMessageId) {
            console.error('Aucun message ID en cours d\'édition');
            return;
        }
        
        const newContent = document.getElementById('editModalTextarea').value.trim();
        
        if (!newContent) {
            showNotification('❌ Le message ne peut pas être vide', 'error');
            return;
        }
        
        console.log('Enregistrement édition pour message ID:', currentEditingMessageId);
        console.log('Nouveau contenu:', newContent);
        
        // Désactiver le bouton
        const saveBtn = document.querySelector('.edit-modal-save');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        saveBtn.disabled = true;
        
        try {
            const formData = new FormData();
            formData.append('action', 'edit_message');
            formData.append('message_id', currentEditingMessageId);
            formData.append('content', newContent);
            
            const response = await fetch('', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            console.log('Réponse édition:', data);
            
            if (data.success) {
                // Mettre à jour l'affichage
                const messageElement = document.getElementById('message-' + currentEditingMessageId);
                if (messageElement) {
                    const contentElement = messageElement.querySelector('.message-content');
                    contentElement.innerHTML = newContent.replace(/\n/g, '<br>');
                    contentElement.classList.add('edited');
                }
                
                closeEditModal();
                showNotification('✅ Message modifié avec succès!', 'success');
                
                // Rafraîchir pour mettre à jour la conversation
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                console.error('Erreur édition:', data.message);
                showNotification('❌ ' + data.message, 'error');
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Erreur:', error);
            showNotification('❌ Erreur de connexion', 'error');
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
    }
    
    // Envoyer un message
    async function sendPrivateMessage(event) {
        event.preventDefault();
        
        const messageInput = document.getElementById('messageInput');
        const receiverId = document.getElementById('receiver_id').value;
        const message = messageInput.value.trim();
        
        if (!message || !receiverId) {
            showNotification('Veuillez entrer un message', 'error');
            return false;
        }
        
        const sendButton = document.getElementById('sendButton');
        sendButton.disabled = true;
        const originalText = sendButton.innerHTML;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        try {
            const formData = new FormData();
            formData.append('action', 'send_message');
            formData.append('receiver_id', receiverId);
            formData.append('content', message);
            
            const response = await fetch('', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Réinitialiser le formulaire
                messageInput.value = '';
                messageInput.style.height = 'auto';
                
                showNotification('✅ Message envoyé avec succès!', 'success');
                
                // Rafraîchir la page pour voir le nouveau message
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                showNotification('❌ Erreur: ' + data.message, 'error');
                sendButton.disabled = false;
                sendButton.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Erreur:', error);
            showNotification('❌ Erreur de connexion', 'error');
            sendButton.disabled = false;
            sendButton.innerHTML = originalText;
        }
        
        return false;
    }
    
    // Rechercher des utilisateurs
    document.getElementById('userSearch').addEventListener('input', function(e) {
        const query = e.target.value;
        const results = document.getElementById('searchResults');
        
        if (query.length < 2) {
            results.style.display = 'none';
            return;
        }
        
        results.innerHTML = '';
        
        const testUsers = [
            {id: 2, username: 'Marie', email: 'marie@campus.com'},
            {id: 3, username: 'Pierre', email: 'pierre@campus.com'},
        ];
        
        const filtered = testUsers.filter(user => 
            user.username.toLowerCase().includes(query.toLowerCase())
        );
        
        if (filtered.length === 0) {
            results.style.display = 'none';
            return;
        }
        
        filtered.forEach(user => {
            const div = document.createElement('div');
            div.className = 'search-result-item';
            div.innerHTML = `
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #7b2da8, #ff6fb1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 16px;">
                    ${user.username.charAt(0)}
                </div>
                <div>
                    <strong>${user.username}</strong><br>
                    <small style="color: #718096;">${user.email}</small>
                </div>
            `;
            div.onclick = () => {
                openChat(user.id, user.username);
                results.style.display = 'none';
                e.target.value = '';
            };
            results.appendChild(div);
        });
        
        results.style.display = 'block';
    });
    
    // Fermer les résultats de recherche
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-users')) {
            document.getElementById('searchResults').style.display = 'none';
        }
        // Fermer les modaux en cliquant à l'extérieur
        if (e.target.classList.contains('edit-modal')) {
            closeEditModal();
        }
        if (e.target.classList.contains('delete-modal')) {
            closeDeleteModal();
        }
    });
    
    // Vérifier s'il n'y a plus de messages
    function checkIfNoMessages() {
        const messagesList = document.getElementById('messagesList');
        if (messagesList && messagesList.children.length === 0) {
            messagesList.innerHTML = `
                <div style="text-align: center; padding: 40px 20px; color: #718096;">
                    <i class="far fa-comment-dots" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.3;"></i>
                    <p style="font-size: 1rem; margin-bottom: 8px; font-weight: 600;">Aucun message</p>
                    <small>Envoyez votre premier message !</small>
                </div>
            `;
        }
    }
    
    // Notification
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        document.querySelectorAll('.notification-success, .notification-error, .notification-info').forEach(n => n.remove());
        
        const notification = document.createElement('div');
        notification.className = `notification-${type}`;
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 12px;">
                ${type === 'success' ? '<i class="fas fa-check-circle"></i>' : 
                  type === 'error' ? '<i class="fas fa-exclamation-circle"></i>' : 
                  '<i class="fas fa-info-circle"></i>'} 
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Slide out animation after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutNotification 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 3000);
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-resize textarea
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                
                const sendButton = document.getElementById('sendButton');
                if (sendButton) {
                    sendButton.disabled = this.value.trim() === '';
                }
            });
            
            // Envoyer avec Enter (sans Shift)
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const sendButton = document.getElementById('sendButton');
                    if (sendButton && !sendButton.disabled) {
                        sendPrivateMessage(e);
                    }
                }
            });
            
            // Focus sur l'input si une conversation est ouverte
            if (document.getElementById('receiver_id').value) {
                setTimeout(() => {
                    messageInput.focus();
                }, 300);
            }
        }
        
        // Scroll to bottom
        const messagesList = document.getElementById('messagesList');
        if (messagesList) {
            messagesList.scrollTop = messagesList.scrollHeight;
        }
        
        // Échap pour fermer les modaux
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (document.getElementById('editModal').style.display === 'flex') {
                    closeEditModal();
                }
                if (document.getElementById('deleteModal').style.display === 'flex') {
                    closeDeleteModal();
                }
            }
        });
        
        // Debug: Afficher tous les messages dans la console
        console.log('Messages chargés:');
        const messageElements = document.querySelectorAll('.message');
        messageElements.forEach(msg => {
            const msgId = msg.getAttribute('data-message-id');
            console.log('ID:', msg.id, 'Data ID:', msgId, 'Type:', typeof msgId);
        });
    });
    </script>
</body>
</html>
