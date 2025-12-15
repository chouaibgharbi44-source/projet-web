<?php
// views/messages.php - VERSION AVEC NOUVELLE MISE EN PAGE (comme group_messages.php)
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
    <title>Messages Privés - Campus Connect</title>
    
    <!-- Import Poppins font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Modern pink theme using Poppins and 3-color gradient */
        :root {
            --purple-1: #7b2da8;    /* deep purple */
            --rose-1: #ff6fb1;      /* vivid rose */
            --dark-blue: #0b2545;   /* deep navy/blue */
            --muted: #3a2a3a;
            --surface: rgba(255, 255, 255, 0.9);
            --glass: rgba(255, 255, 255, 0.6);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            color: var(--muted);
            background: radial-gradient(1200px 600px at 10% 10%, rgba(123, 45, 168, 0.08), transparent 12%),
                radial-gradient(1000px 500px at 90% 90%, rgba(255, 111, 177, 0.06), transparent 12%),
                linear-gradient(135deg, #fffafc 0%, #fff 100%);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* Top Header (Matches group_messages.php) */
        .topbar {
            background: linear-gradient(90deg, var(--purple-1), var(--rose-1), var(--dark-blue));
            padding: 14px 22px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 8px 30px rgba(255, 77, 140, 0.12);
            position: relative;
        }

        .topbar::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 6px;
            background: linear-gradient(90deg, rgba(123, 45, 168, 0.6), rgba(255, 111, 177, 0.5), rgba(11, 37, 69, 0.4));
            opacity: 0.8;
            transform-origin: left center;
            animation: slideGradient 6s linear infinite;
        }

        @keyframes slideGradient {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(0%); }
            100% { transform: translateX(100%); }
        }

        .topbar h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .admin-button a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.12);
            transition: background 220ms ease, transform 120ms ease;
        }

        .admin-button a:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-2px);
        }

        /* Main Container */
        .container {
            padding: 28px;
            display: flex;
            gap: 28px;
            height: calc(100vh - 70px);
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Conversations Sidebar */
        .conversations-sidebar {
            width: 360px;
            background: var(--surface);
            border-radius: 20px;
            padding: 24px;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(123, 45, 168, 0.08);
            border: 1px solid rgba(255, 111, 177, 0.1);
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }

        .conversations-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(123, 45, 168, 0.1);
        }

        .conversations-title {
            color: var(--dark-blue);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .title-icon {
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .search-users {
            position: relative;
            margin-top: 16px;
        }

        .search-input {
            width: 100%;
            padding: 14px 50px 14px 18px;
            border: 1px solid rgba(123, 45, 168, 0.15);
            border-radius: 12px;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s ease;
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
            box-shadow: 0 20px 50px rgba(123, 45, 168, 0.15);
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
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-result-item:hover {
            background: linear-gradient(90deg, rgba(123, 45, 168, 0.06), rgba(255, 111, 177, 0.04));
            transform: translateX(4px);
        }

        /* Conversation Items */
        .conversations-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .conversation-item {
            display: flex;
            padding: 18px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            align-items: center;
            border: 2px solid transparent;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .conversation-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--purple-1), var(--rose-1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .conversation-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(123, 45, 168, 0.15);
            border-color: rgba(255, 111, 177, 0.2);
        }

        .conversation-item:hover::before {
            opacity: 1;
        }

        .conversation-item.active {
            background: linear-gradient(135deg, rgba(123, 45, 168, 0.08), rgba(255, 111, 177, 0.06));
            border-color: rgba(255, 111, 177, 0.3);
        }

        .conversation-item.active::before {
            opacity: 1;
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
            background: #2ecc71;
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
            margin-bottom: 10px;
        }

        .conversation-time {
            font-size: 0.75rem;
            color: var(--rose-1);
            font-weight: 600;
            white-space: nowrap;
            margin-top: 4px;
        }

        /* Chat Area */
        .chat-area {
            flex: 1;
            background: var(--surface);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(123, 45, 168, 0.08);
            border: 1px solid rgba(255, 111, 177, 0.1);
            overflow: hidden;
        }

        /* Chat Header */
        .chat-header {
            padding: 20px 28px;
            background: linear-gradient(90deg, var(--purple-1), var(--rose-1));
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-user-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .chat-user-avatar {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
            position: relative;
            backdrop-filter: blur(10px);
        }

        .chat-user-avatar.online::after {
            content: '';
            position: absolute;
            bottom: 4px;
            right: 4px;
            width: 12px;
            height: 12px;
            background: #2ecc71;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .chat-user-details h3 {
            margin: 0 0 6px 0;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .chat-user-status {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Messages List */
        .messages-list {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
            background: linear-gradient(180deg, #fdfbfd 0%, #f9f5ff 100%);
        }

        .message {
            max-width: 70%;
            padding: 16px 20px;
            border-radius: 20px;
            position: relative;
            animation: messageSlideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            line-height: 1.5;
            word-wrap: break-word;
            backdrop-filter: blur(10px);
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
            background: linear-gradient(135deg, var(--rose-1), var(--purple-1));
            color: white;
            border-bottom-right-radius: 8px;
            box-shadow: 0 8px 25px rgba(255, 111, 177, 0.2);
        }

        .message.received {
            align-self: flex-start;
            background: white;
            color: var(--dark-blue);
            border: 1px solid rgba(123, 45, 168, 0.1);
            border-bottom-left-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
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
            margin-top: 8px;
            text-align: right;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .message-actions {
            display: flex;
            gap: 6px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .message:hover .message-actions {
            opacity: 1;
        }

        .edit-btn, .delete-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            backdrop-filter: blur(10px);
        }

        .message.sent .edit-btn:hover,
        .message.sent .delete-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        .message.received .edit-btn,
        .message.received .delete-btn {
            background: rgba(0, 0, 0, 0.05);
            color: var(--muted);
        }

        .message.received .edit-btn:hover {
            background: rgba(123, 45, 168, 0.1);
            color: var(--purple-1);
        }

        .message.received .delete-btn:hover {
            background: rgba(220, 20, 60, 0.1);
            color: #dc143c;
        }

        /* Message Form */
        .message-form-container {
            padding: 20px 24px;
            background: white;
            border-top: 1px solid rgba(123, 45, 168, 0.1);
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
            border: 2px solid rgba(123, 45, 168, 0.1);
            border-radius: 16px;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.3s ease;
            background: #fdfaff;
            resize: none;
            min-height: 60px;
            max-height: 120px;
            font-family: 'Poppins', sans-serif;
            line-height: 1.5;
        }

        .message-input:focus {
            border-color: var(--rose-1);
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 111, 177, 0.15);
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

        .action-btn {
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: rgba(123, 45, 168, 0.1);
            color: var(--purple-1);
            transform: translateY(-2px);
        }

        /* Send Button */
        .send-button {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            color: white;
            border: none;
            padding: 16px 28px;
            border-radius: 16px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            box-shadow: 0 10px 30px rgba(123, 45, 168, 0.2);
        }

        .send-button:hover:not(:disabled) {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(123, 45, 168, 0.25);
        }

        .send-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .send-button:after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 420ms ease, height 420ms ease, opacity 420ms ease;
            opacity: 0;
        }

        .send-button:active:after {
            width: 300px;
            height: 300px;
            opacity: 1;
            transition: 0s;
        }

        /* No Chat Selected */
        .no-chat {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--muted);
            text-align: center;
            flex-direction: column;
            gap: 24px;
            padding: 60px;
        }

        .no-chat-icon {
            font-size: 80px;
            color: rgba(123, 45, 168, 0.1);
            margin-bottom: 16px;
        }

        .no-chat h3 {
            color: var(--dark-blue);
            font-size: 1.8rem;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .no-chat p {
            max-width: 400px;
            line-height: 1.5;
            margin-bottom: 24px;
            font-size: 1rem;
            opacity: 0.8;
        }

        .no-chat-tip {
            padding: 16px 24px;
            background: linear-gradient(135deg, rgba(123, 45, 168, 0.08), rgba(255, 111, 177, 0.06));
            border-radius: 16px;
            color: var(--purple-1);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(123, 45, 168, 0.1);
        }

        /* Empty Messages */
        .no-messages {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }

        .no-messages-icon {
            font-size: 60px;
            color: rgba(123, 45, 168, 0.1);
            margin-bottom: 16px;
        }

        .no-messages h4 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-blue);
        }

        .no-messages p {
            opacity: 0.8;
        }

        /* Scrollbar Styling */
        .conversations-sidebar::-webkit-scrollbar,
        .messages-list::-webkit-scrollbar {
            width: 6px;
        }

        .conversations-sidebar::-webkit-scrollbar-track,
        .messages-list::-webkit-scrollbar-track {
            background: rgba(123, 45, 168, 0.05);
            border-radius: 3px;
        }

        .conversations-sidebar::-webkit-scrollbar-thumb,
        .messages-list::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--purple-1), var(--rose-1));
            border-radius: 3px;
        }

        .conversations-sidebar::-webkit-scrollbar-thumb:hover,
        .messages-list::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--rose-1), var(--purple-1));
        }

        /* Responsive Design */
        @media screen and (max-width: 1200px) {
            .container {
                flex-direction: column;
                height: auto;
            }
            
            .conversations-sidebar {
                width: 100%;
                max-height: 350px;
            }
        }

        @media screen and (max-width: 768px) {
            .container {
                padding: 16px;
                gap: 16px;
            }
            
            .message {
                max-width: 85%;
            }
            
            .message-form {
                flex-direction: column;
            }
            
            .send-button {
                width: 100%;
                justify-content: center;
            }
            
            .chat-header,
            .message-form-container {
                padding: 16px;
            }
            
            .messages-list {
                padding: 16px;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Notification Toast */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            animation: slideInRight 0.3s ease, slideOutRight 0.3s ease 2.7s forwards;
            backdrop-filter: blur(10px);
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOutRight {
            to { transform: translateX(100%); opacity: 0; }
        }

        .notification.success {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .notification.error {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .notification.info {
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
        }

        /* Message Deleting Animation */
        .message-deleting {
            opacity: 0.5;
            transform: scale(0.95);
            transition: all 0.3s ease;
        }

        /* Edit Modal */
        .edit-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            backdrop-filter: blur(4px);
            animation: fadeIn 200ms ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .edit-modal {
            background: linear-gradient(145deg, #ffffff, #fdfaff);
            border-radius: 24px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(123, 45, 168, 0.25);
            overflow: hidden;
            animation: slideUp 300ms cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        @keyframes slideUp {
            from {
                transform: translateY(40px) scale(0.95);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .edit-modal-header {
            padding: 24px;
            background: linear-gradient(90deg, var(--purple-1), var(--rose-1));
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .edit-modal-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: transform 0.2s ease;
        }

        .modal-close:hover {
            transform: rotate(90deg);
        }

        .edit-modal-body {
            padding: 24px;
        }

        .edit-textarea {
            width: 100%;
            padding: 16px;
            border: 2px solid rgba(123, 45, 168, 0.15);
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            resize: vertical;
            min-height: 120px;
            outline: none;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
        }

        .edit-textarea:focus {
            border-color: var(--rose-1);
            box-shadow: 0 0 0 4px rgba(255, 111, 177, 0.15);
            background: white;
        }

        .edit-modal-footer {
            padding: 20px 24px;
            background: #f9f5ff;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .modal-btn {
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .modal-btn.cancel {
            background: transparent;
            color: var(--muted);
            border: 1px solid rgba(123, 45, 168, 0.1);
        }

        .modal-btn.cancel:hover {
            background: rgba(123, 45, 168, 0.05);
            border-color: rgba(123, 45, 168, 0.2);
        }

        .modal-btn.save {
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            color: white;
            box-shadow: 0 8px 20px rgba(123, 45, 168, 0.2);
        }

        .modal-btn.save:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(123, 45, 168, 0.25);
        }

        .modal-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }
    </style>
</head>
<body>
    <!-- Top Header (like group_messages.php) -->
    <div class="topbar">
        <h1>💬 Messages Privés - Campus Connect</h1>
        <div class="admin-button">
            <a href="/campus connect/index.php">🏠 Retour à l'accueil</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Conversations Sidebar -->
        <div class="conversations-sidebar">
            <div class="conversations-header">
                <h2 class="conversations-title">
                    <div class="title-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    Conversations
                    <span style="background: var(--rose-1); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; margin-left: 8px;">
                        <?= count($conversations) ?>
                    </span>
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
                    <div class="no-messages" style="padding: 30px 15px;">
                        <div class="no-messages-icon">
                            <i class="fas fa-comment-slash"></i>
                        </div>
                        <h4>Aucune conversation</h4>
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
                                        <span style="color: #2ecc71; font-size: 0.7rem;">● En ligne</span>
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
        
        <!-- Chat Area -->
        <div class="chat-area">
            <?php if ($current_receiver): ?>
                <!-- Chat Header -->
                <div class="chat-header">
                    <div class="chat-user-info">
                        <div class="chat-user-avatar <?= ($current_receiver['is_online'] ?? false) ? 'online' : '' ?>">
                            <?= htmlspecialchars(substr($current_receiver['username'], 0, 1)) ?>
                        </div>
                        <div class="chat-user-details">
                            <h3><?= htmlspecialchars($current_receiver['username']) ?></h3>
                            <div class="chat-user-status">
                                <?php if ($current_receiver['is_online'] ?? false): ?>
                                    <span style="color: #2ecc71;">● En ligne</span>
                                <?php else: ?>
                                    <span>Hors ligne</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Messages List -->
                <div class="messages-list" id="messagesList">
                    <?php if (empty($messages)): ?>
                        <div class="no-messages">
                            <div class="no-messages-icon">
                                <i class="far fa-comment-dots"></i>
                            </div>
                            <h4>Aucun message</h4>
                            <p>Envoyez votre premier message !</p>
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
                                            <button class="edit-btn" onclick="editMessage('<?= $msg_id ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="delete-btn" onclick="deleteMessage('<?= $msg_id ?>', this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Message Form -->
                <div class="message-form-container">
                    <form id="messageForm" class="message-form" onsubmit="return sendPrivateMessage(event)">
                        <input type="hidden" id="receiver_id" value="<?= $_GET['receiver_id'] ?? '' ?>">
                        <div class="message-input-container">
                            <textarea id="messageInput" class="message-input" 
                                      placeholder="Écrivez votre message..." 
                                      required rows="1"></textarea>
                            <div class="input-actions">
                                <button type="button" class="action-btn" title="Émojis">
                                    <i class="far fa-smile"></i>
                                </button>
                                <button type="button" class="action-btn" title="Pièce jointe">
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
                <!-- No Chat Selected -->
                <div class="no-chat">
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
                    <div class="no-chat-tip">
                        <i class="fas fa-lightbulb"></i>
                        Cliquez sur "Marie" pour voir une conversation exemple
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    // Variables globales
    let currentEditingMessageId = null;
    
    // Ouvrir une conversation
    function openChat(userId, username) {
        window.location.href = 'messages.php?receiver_id=' + userId;
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
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin loading"></i>';
        
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
                
                showNotification('Message envoyé avec succès', 'success');
                
                // Rafraîchir la page pour voir le nouveau message
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                showNotification('Erreur: ' + data.message, 'error');
                sendButton.disabled = false;
                sendButton.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Erreur:', error);
            showNotification('Erreur de connexion', 'error');
            sendButton.disabled = false;
            sendButton.innerHTML = originalText;
        }
        
        return false;
    }
    
    // Supprimer un message
    async function deleteMessage(messageId, button) {
        // Confirmation
        if (!confirm('Voulez-vous vraiment supprimer ce message ?')) {
            return;
        }
        
        const messageElement = document.getElementById('message-' + messageId);
        if (!messageElement) {
            showNotification('Message non trouvé', 'error');
            return;
        }
        
        // Désactiver le bouton
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin loading"></i>';
        }
        
        // Animation de suppression
        messageElement.classList.add('message-deleting');
        
        try {
            // Envoyer la requête de suppression
            const formData = new FormData();
            formData.append('action', 'delete_message');
            formData.append('message_id', messageId);
            
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
                    showNotification('Message supprimé avec succès', 'success');
                    
                    // Rafraîchir après un court délai
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }, 300);
            } else {
                messageElement.classList.remove('message-deleting');
                if (button) {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-trash"></i>';
                }
                showNotification('Erreur: ' + data.message, 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            messageElement.classList.remove('message-deleting');
            if (button) {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-trash"></i>';
            }
            showNotification('Erreur de connexion', 'error');
        }
    }
    
    // Modifier un message
    function editMessage(messageId) {
        const messageElement = document.getElementById('message-' + messageId);
        if (!messageElement) {
            showNotification('Message non trouvé', 'error');
            return;
        }
        
        const contentElement = messageElement.querySelector('.message-content');
        const currentContent = contentElement.textContent;
        
        currentEditingMessageId = messageId;
        
        // Créer le modal d'édition
        const modal = document.createElement('div');
        modal.className = 'edit-modal-overlay';
        modal.innerHTML = `
            <div class="edit-modal">
                <div class="edit-modal-header">
                    <h3><i class="fas fa-edit"></i> Modifier le message</h3>
                    <button class="modal-close" onclick="closeEditModal()">&times;</button>
                </div>
                <div class="edit-modal-body">
                    <textarea class="edit-textarea" placeholder="Modifiez votre message...">${currentContent}</textarea>
                </div>
                <div class="edit-modal-footer">
                    <button class="modal-btn cancel" onclick="closeEditModal()">Annuler</button>
                    <button class="modal-btn save" onclick="saveEditedMessage()">Enregistrer</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
        
        // Focus sur le textarea
        setTimeout(() => {
            const textarea = modal.querySelector('.edit-textarea');
            textarea.focus();
            textarea.select();
        }, 100);
    }
    
    // Fermer le modal d'édition
    function closeEditModal() {
        const modal = document.querySelector('.edit-modal-overlay');
        if (modal) {
            modal.remove();
            document.body.style.overflow = '';
            currentEditingMessageId = null;
        }
    }
    
    // Sauvegarder le message modifié
    async function saveEditedMessage() {
        if (!currentEditingMessageId) return;
        
        const modal = document.querySelector('.edit-modal-overlay');
        if (!modal) return;
        
        const textarea = modal.querySelector('.edit-textarea');
        const newContent = textarea.value.trim();
        
        if (!newContent) {
            showNotification('Le message ne peut pas être vide', 'error');
            return;
        }
        
        // Désactiver le bouton
        const saveButton = modal.querySelector('.modal-btn.save');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin loading"></i>';
        saveButton.disabled = true;
        
        try {
            // Envoyer la requête de modification
            const formData = new FormData();
            formData.append('action', 'edit_message');
            formData.append('message_id', currentEditingMessageId);
            formData.append('content', newContent);
            
            const response = await fetch('', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Mettre à jour l'affichage
                const messageElement = document.getElementById('message-' + currentEditingMessageId);
                if (messageElement) {
                    const contentElement = messageElement.querySelector('.message-content');
                    contentElement.innerHTML = newContent.replace(/\n/g, '<br>');
                    contentElement.classList.add('edited');
                }
                
                closeEditModal();
                showNotification('Message modifié avec succès', 'success');
                
                // Rafraîchir pour mettre à jour la conversation
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                console.error('Erreur édition:', data.message);
                showNotification('Erreur: ' + data.message, 'error');
                saveButton.innerHTML = originalText;
                saveButton.disabled = false;
            }
        } catch (error) {
            console.error('Erreur:', error);
            showNotification('Erreur de connexion', 'error');
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        }
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
    
    // Vérifier s'il n'y a plus de messages
    function checkIfNoMessages() {
        const messagesList = document.getElementById('messagesList');
        if (messagesList && messagesList.children.length === 0) {
            messagesList.innerHTML = `
                <div class="no-messages">
                    <div class="no-messages-icon">
                        <i class="far fa-comment-dots"></i>
                    </div>
                    <h4>Aucun message</h4>
                    <p>Envoyez votre premier message !</p>
                </div>
            `;
        }
    }
    
    // Afficher une notification
    function showNotification(message, type = 'info') {
        // Supprimer les anciennes notifications
        const oldNotifications = document.querySelectorAll('.notification');
        oldNotifications.forEach(n => n.remove());
        
        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div>${message}</div>
        `;
        
        document.body.appendChild(notification);
        
        // Supprimer après 3 secondes
        setTimeout(() => {
            notification.remove();
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
    });
    </script>
    
    <!-- Font Awesome Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
