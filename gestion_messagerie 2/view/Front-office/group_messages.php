<?php
session_start();

// Définir l'utilisateur pour la démo
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'Vous';
}

// Inclure les fonctions des groupes - CORRECTION DU CHEMIN
$groupFile = __DIR__ . '/../../model/group.php';
if (file_exists($groupFile)) {
    require_once $groupFile;
} else {
    // Essayer un autre chemin possible
    $groupFile = __DIR__ . '/../../../model/group.php';
    if (file_exists($groupFile)) {
        require_once $groupFile;
    } else {
        die("Erreur: Impossible de trouver le fichier group.php");
    }
}

// Récupérer le groupe courant
$current_group_id = $_GET['group_id'] ?? null;
$current_group = null;
if ($current_group_id) {
    $current_group = getGroup($current_group_id);
}

// Récupérer tous les groupes et messages
$groups = getAllGroups();
$group_messages = [];
if ($current_group_id) {
    $group_messages = getGroupMessages($current_group_id);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages de Groupe - Campus Connect</title>
    
    <!-- Include your existing styles -->
    <link rel="stylesheet" href="/campus connect/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Your custom styles for the groups page */
        :root {
            --primary-blue: #4361ee;
            --primary-dark: #3a56d4;
            --secondary-purple: #7209b7;
            --accent-pink: #f72585;
            --light-bg: #f8f9fa;
            --dark-text: #2d3748;
            --gray-text: #718096;
            --light-gray: #e2e8f0;
            --white: #ffffff;
            --success-green: #2ecc71;
            --danger-red: #e63946;
            --border-radius: 12px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            min-height: 100vh;
        }

        /* Main content area */
        .main-content {
            padding: 20px;
            max-width: 1400px;
            margin: 20px auto;
        }

        .content-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--shadow);
            margin-top: 20px;
        }

        /* Page title */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-gray);
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-title h1 {
            color: var(--dark-text);
            font-size: 1.8rem;
            font-weight: 700;
        }

        .page-icon {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.5rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--light-gray);
            color: var(--gray-text);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .back-button:hover {
            background: var(--primary-blue);
            color: var(--white);
            transform: translateX(-5px);
        }

        .group-messages-container {
            display: flex;
            height: calc(100vh - 220px);
            gap: 20px;
            margin-top: 20px;
        }
        
        /* Sidebar des groupes */
        .groups-list {
            width: 320px;
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 20px;
            overflow-y: auto;
            box-shadow: var(--shadow);
            border: 1px solid var(--light-gray);
            display: flex;
            flex-direction: column;
        }
        
        .groups-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-gray);
        }
        
        .groups-title {
            color: var(--dark-text);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .title-icon {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
        }
        
        .groups-subtitle {
            color: var(--gray-text);
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        .groups-scroll {
            flex: 1;
            overflow-y: auto;
            padding-right: 5px;
        }
        
        /* Items de groupe */
        .group-item {
            display: flex;
            padding: 15px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 10px;
            align-items: center;
            border: 2px solid transparent;
            background: var(--white);
        }
        
        .group-item:hover {
            background: var(--light-bg);
            border-color: var(--light-gray);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .group-item.active {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(114, 9, 183, 0.1));
            border-color: rgba(67, 97, 238, 0.2);
        }
        
        .group-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
            font-size: 18px;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .group-info {
            flex: 1;
            min-width: 0;
        }
        
        .group-name {
            font-weight: 600;
            font-size: 15px;
            color: var(--dark-text);
            margin-bottom: 5px;
        }
        
        .group-description {
            font-size: 0.85rem;
            color: var(--gray-text);
            line-height: 1.4;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            margin-bottom: 8px;
        }
        
        .group-stats {
            display: flex;
            gap: 12px;
            font-size: 0.8rem;
            color: var(--gray-text);
        }
        
        .group-stat {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .group-stat i {
            font-size: 0.7rem;
        }
        
        /* Zone de chat */
        .group-chat-area {
            flex: 1;
            background: var(--white);
            border-radius: var(--border-radius);
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow);
            border: 1px solid var(--light-gray);
            overflow: hidden;
        }
        
        /* En-tête du chat */
        .group-chat-header {
            padding: 20px 25px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .group-info-header {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .group-chat-avatar {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
            font-size: 20px;
        }
        
        .group-details h3 {
            margin: 0 0 5px 0;
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        .group-members {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Liste des messages */
        .group-messages-list {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: linear-gradient(180deg, var(--light-bg) 0%, var(--white) 100%);
        }
        
        .group-message {
            max-width: 75%;
            padding: 15px 18px;
            border-radius: 18px;
            position: relative;
            animation: messageSlideIn 0.3s ease;
            line-height: 1.5;
            word-wrap: break-word;
        }
        
        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .group-message.sent {
            align-self: flex-end;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-dark));
            color: var(--white);
            border-bottom-right-radius: 5px;
        }
        
        .group-message.received {
            align-self: flex-start;
            background: var(--white);
            color: var(--dark-text);
            border: 1px solid var(--light-gray);
            border-bottom-left-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .message-user {
            font-size: 0.85rem;
            margin-bottom: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .group-message.sent .message-user {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .group-message.received .message-user {
            color: var(--gray-text);
        }
        
        .message-user-avatar {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
            font-size: 12px;
        }
        
        .group-message-content {
            font-size: 0.95rem;
            line-height: 1.5;
            word-break: break-word;
        }
        
        .group-message-time {
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
        
        .group-message-actions {
            display: flex;
            gap: 6px;
        }
        
        .edit-group-message-btn,
        .delete-group-message-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .group-message.sent .edit-group-message-btn:hover,
        .group-message.sent .delete-group-message-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
        
        .group-message.received .edit-group-message-btn,
        .group-message.received .delete-group-message-btn {
            background: rgba(0, 0, 0, 0.05);
            color: var(--gray-text);
        }
        
        .group-message.received .edit-group-message-btn:hover {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-blue);
        }
        
        .group-message.received .delete-group-message-btn:hover {
            background: rgba(230, 57, 70, 0.1);
            color: var(--danger-red);
        }
        
        /* Formulaire d'envoi */
        .group-message-form-container {
            padding: 20px;
            background: var(--white);
            border-top: 1px solid var(--light-gray);
        }
        
        .group-message-form {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }
        
        .group-message-input-container {
            flex: 1;
            position: relative;
        }
        
        .group-message-input {
            width: 100%;
            padding: 15px 20px;
            padding-right: 60px;
            border: 2px solid var(--light-gray);
            border-radius: 25px;
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition);
            background: var(--light-bg);
            resize: none;
            min-height: 50px;
            max-height: 120px;
            font-family: inherit;
            line-height: 1.5;
        }
        
        .group-message-input:focus {
            border-color: var(--primary-blue);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .group-input-actions {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 8px;
        }
        
        .group-input-action-btn {
            background: none;
            border: none;
            color: var(--gray-text);
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        
        .group-input-action-btn:hover {
            background: var(--light-gray);
            color: var(--primary-blue);
        }
        
        .group-send-button {
            padding: 15px 25px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            color: var(--white);
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        
        .group-send-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.25);
        }
        
        .group-send-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Aucun groupe sélectionné */
        .no-group-selected {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--gray-text);
            text-align: center;
            flex-direction: column;
            gap: 20px;
            padding: 40px;
        }
        
        .no-group-icon {
            font-size: 60px;
            color: var(--light-gray);
            margin-bottom: 10px;
        }
        
        .no-group-selected h3 {
            color: var(--dark-text);
            font-size: 1.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .no-group-selected p {
            max-width: 400px;
            line-height: 1.5;
            margin-bottom: 20px;
            font-size: 1rem;
        }
        
        /* Style pour la suppression */
        .message-deleting {
            opacity: 0.5;
            transform: scale(0.95);
            transition: all 0.3s ease;
        }
        
        /* Modal d'édition */
        .edit-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            animation: fadeIn 0.3s ease forwards;
        }
        
        @keyframes fadeIn {
            to { opacity: 1; }
        }
        
        .edit-modal {
            background: white;
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            transform: translateY(-20px);
            animation: slideUp 0.3s ease forwards;
        }
        
        @keyframes slideUp {
            to { transform: translateY(0); }
        }
        
        .edit-modal-header {
            padding: 20px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-purple));
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .edit-modal-header h3 {
            margin: 0;
            font-size: 1.2rem;
        }
        
        .edit-modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }
        
        .edit-modal-body {
            padding: 20px;
        }
        
        .edit-modal-textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid var(--light-gray);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .edit-modal-textarea:focus {
            border-color: var(--primary-blue);
        }
        
        .edit-modal-footer {
            padding: 15px 20px;
            background: var(--light-bg);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .edit-modal-btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        
        .edit-modal-cancel {
            background: var(--light-gray);
            color: var(--gray-text);
        }
        
        .edit-modal-save {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-dark));
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .group-messages-container {
                flex-direction: column;
                height: auto;
            }
            
            .groups-list {
                width: 100%;
                max-height: 300px;
            }
        }
        
        @media (max-width: 768px) {
            .group-message {
                max-width: 85%;
            }
            
            .group-chat-header {
                padding: 15px;
            }
            
            .group-messages-list {
                padding: 15px;
            }
            
            .group-message-form-container {
                padding: 15px;
            }
            
            .group-message-form {
                flex-direction: column;
            }
            
            .group-send-button {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Scrollbar */
        .groups-scroll::-webkit-scrollbar,
        .group-messages-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .groups-scroll::-webkit-scrollbar-track,
        .group-messages-list::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: 3px;
        }
        
        .groups-scroll::-webkit-scrollbar-thumb,
        .group-messages-list::-webkit-scrollbar-thumb {
            background: var(--primary-blue);
            border-radius: 3px;
        }
        
        .groups-scroll::-webkit-scrollbar-thumb:hover,
        .group-messages-list::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    <?php
    // Try multiple paths for header.php
    $headerPaths = [
        __DIR__ . '/../../../header.php', // Go up 3 levels to root
        $_SERVER['DOCUMENT_ROOT'] . '/campus connect/header.php', // Absolute path
        '/campus connect/header.php' // Web path
    ];
    
    $headerIncluded = false;
    foreach ($headerPaths as $headerPath) {
        if (file_exists($headerPath)) {
            include $headerPath;
            $headerIncluded = true;
            break;
        }
    }
    
    // If header not found, show a simple one
    if (!$headerIncluded) {
        echo '
        <div style="background: #4361ee; padding: 15px 20px; color: white;">
            <div style="max-width: 1400px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 1.5rem; font-weight: bold;">CAMPUS CONNECT</div>
                <div>
                    <a href="/campus connect/index.php" style="color: white; margin-left: 20px; text-decoration: none;">Accueil</a>
                    <a href="/campus connect/view/Front-office/group_messages.php" style="color: white; margin-left: 20px; text-decoration: none; background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 4px;">Groupes</a>
                </div>
            </div>
        </div>';
    }
    ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header with Back Button -->
        <div class="page-header">
            <div class="page-title">
                <div class="page-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h1>Messages de Groupe</h1>
            </div>
            <a href="/campus connect/index.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Retour à l'accueil
            </a>
        </div>
        
        <div class="content-section">
            <div class="group-messages-container">
                <!-- Sidebar des groupes -->
                <div class="groups-list">
                    <div class="groups-header">
                        <h2 class="groups-title">
                            <div class="title-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            Groupes de Discussion
                            <span style="background: var(--accent-pink); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; margin-left: 8px;">
                                <?= count($groups) ?>
                            </span>
                        </h2>
                        <p class="groups-subtitle">
                            Rejoignez des groupes thématiques et échangez avec la communauté
                        </p>
                    </div>
                    
                    <div class="groups-scroll">
                        <?php if (empty($groups)): ?>
                            <div style="text-align: center; padding: 30px 15px; color: var(--gray-text);">
                                <i class="fas fa-users-slash" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.3;"></i>
                                <p style="font-size: 1rem; margin-bottom: 8px; font-weight: 600;">Aucun groupe disponible</p>
                                <small>Créez un nouveau groupe pour commencer</small>
                            </div>
                        <?php else: ?>
                            <?php foreach ($groups as $group): ?>
                                <div class="group-item <?= $current_group_id == $group['id'] ? 'active' : '' ?>" 
                                     onclick="openGroupChat(<?= $group['id'] ?>)">
                                    <div class="group-avatar">
                                        <?= htmlspecialchars(substr($group['name'], 0, 1)) ?>
                                    </div>
                                    <div class="group-info">
                                        <div class="group-name">
                                            <?= htmlspecialchars($group['name']) ?>
                                        </div>
                                        <div class="group-description">
                                            <?= htmlspecialchars($group['description'] ?? 'Description du groupe') ?>
                                        </div>
                                        <div class="group-stats">
                                            <div class="group-stat">
                                                <i class="fas fa-users"></i>
                                                <span><?= $group['member_count'] ?? 0 ?> membres</span>
                                            </div>
                                            <div class="group-stat">
                                                <i class="fas fa-comment"></i>
                                                <span><?= $group['message_count'] ?? 0 ?> messages</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Zone de chat -->
                <div class="group-chat-area">
                    <?php if ($current_group): ?>
                        <!-- En-tête du chat -->
                        <div class="group-chat-header">
                            <div class="group-info-header">
                                <div class="group-chat-avatar">
                                    <?= htmlspecialchars(substr($current_group['name'], 0, 1)) ?>
                                </div>
                                <div class="group-details">
                                    <h3><?= htmlspecialchars($current_group['name']) ?></h3>
                                    <div class="group-members">
                                        <i class="fas fa-users"></i>
                                        <?= $current_group['member_count'] ?? 0 ?> membres
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Liste des messages -->
                        <div class="group-messages-list" id="groupMessagesList">
                            <?php if (empty($group_messages)): ?>
                                <div style="text-align: center; padding: 40px 20px; color: var(--gray-text);">
                                    <i class="far fa-comments" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.3;"></i>
                                    <p style="font-size: 1rem; margin-bottom: 8px; font-weight: 600;">Aucun message dans ce groupe</p>
                                    <small>Soyez le premier à envoyer un message !</small>
                                </div>
                            <?php else: ?>
                                <?php foreach ($group_messages as $msg): 
                                    $is_sent = $msg['user_id'] == $_SESSION['user_id'];
                                ?>
                                    <div class="group-message <?= $is_sent ? 'sent' : 'received' ?>" 
                                         id="group-message-<?= $msg['id'] ?>"
                                         data-id="<?= $msg['id'] ?>">
                                        <div class="message-user">
                                            <div class="message-user-avatar">
                                                <?= htmlspecialchars(substr($msg['username'] ?? '?', 0, 1)) ?>
                                            </div>
                                            <span><?= htmlspecialchars($msg['username'] ?? 'Utilisateur') ?></span>
                                            <?php if ($is_sent): ?>
                                                <span style="font-size: 10px; opacity: 0.8;">(vous)</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="group-message-content" id="group-message-content-<?= $msg['id'] ?>">
                                            <?= nl2br(htmlspecialchars($msg['content'])) ?>
                                        </div>
                                        <div class="group-message-time">
                                            <span><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                                            <?php if ($is_sent): ?>
                                                <div class="group-message-actions">
                                                    <button class="edit-group-message-btn" onclick="editGroupMessage(<?= $msg['id'] ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="delete-group-message-btn" onclick="deleteGroupMessage(<?= $msg['id'] ?>, this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Formulaire d'envoi -->
                        <div class="group-message-form-container">
                            <form id="groupMessageForm" class="group-message-form" onsubmit="return sendGroupMessage(event)">
                                <input type="hidden" id="group_id" value="<?= $current_group['id'] ?>">
                                <div class="group-message-input-container">
                                    <textarea id="groupMessageInput" class="group-message-input" 
                                              placeholder="Écrivez votre message au groupe..." 
                                              required rows="1"></textarea>
                                    <div class="group-input-actions">
                                        <button type="button" class="group-input-action-btn" title="Émojis">
                                            <i class="far fa-smile"></i>
                                        </button>
                                        <button type="button" class="group-input-action-btn" title="Pièce jointe">
                                            <i class="fas fa-paperclip"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="submit" id="groupSendButton" class="group-send-button">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Envoyer</span>
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- Aucun groupe sélectionné -->
                        <div class="no-group-selected">
                            <div>
                                <div class="no-group-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3>Sélectionnez un groupe</h3>
                                <p>
                                    Choisissez un groupe dans la liste<br>
                                    pour commencer à discuter avec ses membres
                                </p>
                            </div>
                            <div style="padding: 15px; background: rgba(67, 97, 238, 0.1); border-radius: var(--border-radius); color: var(--primary-blue);">
                                <i class="fas fa-lightbulb"></i>
                                Cliquez sur un groupe pour commencer
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Variables globales
    let currentEditingMessageId = null;
    
    // Ouvrir le chat d'un groupe
    function openGroupChat(groupId) {
        window.location.href = 'group_messages.php?group_id=' + groupId;
    }
    
    // Redimensionner automatiquement le textarea
    function autoResizeTextarea(textarea) {
        textarea.style.height = 'auto';
        const maxHeight = 120;
        textarea.style.height = Math.min(textarea.scrollHeight, maxHeight) + 'px';
        
        // Activer/désactiver le bouton d'envoi
        const sendButton = document.getElementById('groupSendButton');
        if (sendButton) {
            sendButton.disabled = textarea.value.trim() === '';
        }
    }
    
    // Scroll vers le bas
    function scrollToBottom() {
        const messagesList = document.getElementById('groupMessagesList');
        if (messagesList) {
            messagesList.scrollTop = messagesList.scrollHeight;
        }
    }
    
    // Envoyer un message de groupe
    async function sendGroupMessage(event) {
        event.preventDefault();
        
        const messageInput = document.getElementById('groupMessageInput');
        const groupId = document.getElementById('group_id').value;
        const message = messageInput.value.trim();
        
        if (!message || !groupId) {
            showNotification('Veuillez entrer un message', 'error');
            return false;
        }
        
        // Désactiver le bouton d'envoi
        const sendButton = document.getElementById('groupSendButton');
        const originalText = sendButton.innerHTML;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        sendButton.disabled = true;
        
        // Ajout optimiste du message
        const messagesList = document.getElementById('groupMessagesList');
        const tempId = 'temp-' + Date.now();
        
        const newMessage = document.createElement('div');
        newMessage.className = 'group-message sent';
        newMessage.id = 'group-message-' + tempId;
        newMessage.dataset.id = tempId;
        newMessage.innerHTML = `
            <div class="message-user">
                <div class="message-user-avatar"><?= substr($_SESSION['username'] ?? 'Y', 0, 1) ?></div>
                <span><?= htmlspecialchars($_SESSION['username'] ?? 'Vous') ?></span>
                <span style="font-size: 10px; opacity: 0.8;">(vous)</span>
            </div>
            <div class="group-message-content" id="group-message-content-${tempId}">
                ${message.replace(/\n/g, '<br>')}
            </div>
            <div class="group-message-time">
                <span>${new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</span>
                <div class="group-message-actions">
                    <button class="edit-group-message-btn" onclick="editGroupMessage('${tempId}')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="delete-group-message-btn" onclick="deleteGroupMessage('${tempId}', this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        // Supprimer le message "aucun message" s'il existe
        const noMessagesDiv = messagesList.querySelector('div[style*="text-align: center"]');
        if (noMessagesDiv) {
            noMessagesDiv.remove();
        }
        
        messagesList.appendChild(newMessage);
        messageInput.value = '';
        messageInput.style.height = 'auto';
        scrollToBottom();
        
        try {
            // Envoyer au serveur
            const formData = new FormData();
            formData.append('group_id', groupId);
            formData.append('content', message);
            
            const response = await fetch('addgroupmessage.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Mettre à jour avec l'ID réel
                if (data.message_id) {
                    newMessage.id = 'group-message-' + data.message_id;
                    newMessage.dataset.id = data.message_id;
                    
                    const contentDiv = newMessage.querySelector('.group-message-content');
                    if (contentDiv) {
                        contentDiv.id = 'group-message-content-' + data.message_id;
                    }
                    
                    // Mettre à jour les boutons
                    const editBtn = newMessage.querySelector('.edit-group-message-btn');
                    if (editBtn) {
                        editBtn.setAttribute('onclick', `editGroupMessage(${data.message_id})`);
                    }
                    
                    const deleteBtn = newMessage.querySelector('.delete-group-message-btn');
                    if (deleteBtn) {
                        deleteBtn.setAttribute('onclick', `deleteGroupMessage(${data.message_id}, this)`);
                    }
                }
                
                showNotification('Message envoyé avec succès', 'success');
            } else {
                // Supprimer le message optimiste
                newMessage.remove();
                showNotification('Erreur: ' + (data.message || 'Échec de l\'envoi'), 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            newMessage.remove();
            showNotification('Erreur de connexion', 'error');
        } finally {
            // Réactiver le bouton
            sendButton.innerHTML = originalText;
            sendButton.disabled = false;
        }
        
        return false;
    }
    
    // Supprimer un message de groupe
    async function deleteGroupMessage(messageId, button) {
        // Confirmation
        if (!confirm('Voulez-vous vraiment supprimer ce message ?')) {
            return;
        }
        
        const messageElement = document.getElementById('group-message-' + messageId);
        if (!messageElement) {
            showNotification('Message non trouvé', 'error');
            return;
        }
        
        // Désactiver le bouton
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        }
        
        // Animation de suppression
        messageElement.classList.add('message-deleting');
        
        // Si c'est un message temporaire, supprimer directement
        if (typeof messageId === 'string' && messageId.startsWith('temp-')) {
            setTimeout(() => {
                messageElement.remove();
                checkIfNoMessages();
                showNotification('Message supprimé', 'success');
            }, 300);
            return;
        }
        
        try {
            // Envoyer la requête de suppression
            const formData = new FormData();
            formData.append('message_id', messageId);
            
            const response = await fetch('deletegroupmessage.php', {
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
    
    // Vérifier s'il n'y a plus de messages
    function checkIfNoMessages() {
        const messagesList = document.getElementById('groupMessagesList');
        if (messagesList && messagesList.children.length === 0) {
            messagesList.innerHTML = `
                <div style="text-align: center; padding: 40px 20px; color: var(--gray-text);">
                    <i class="far fa-comments" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.3;"></i>
                    <p style="font-size: 1rem; margin-bottom: 8px; font-weight: 600;">Aucun message dans ce groupe</p>
                    <small>Soyez le premier à envoyer un message !</small>
                </div>
            `;
        }
    }
    
    // Modifier un message de groupe
    function editGroupMessage(messageId) {
        const messageElement = document.getElementById('group-message-' + messageId);
        if (!messageElement) {
            showNotification('Message non trouvé', 'error');
            return;
        }
        
        const contentElement = messageElement.querySelector('.group-message-content');
        const currentContent = contentElement.textContent;
        
        currentEditingMessageId = messageId;
        
        // Créer le modal d'édition
        const modal = document.createElement('div');
        modal.className = 'edit-modal-overlay';
        modal.innerHTML = `
            <div class="edit-modal">
                <div class="edit-modal-header">
                    <h3><i class="fas fa-edit"></i> Modifier le message</h3>
                    <button class="edit-modal-close" onclick="closeEditModal()">&times;</button>
                </div>
                <div class="edit-modal-body">
                    <textarea class="edit-modal-textarea" placeholder="Modifiez votre message...">${currentContent}</textarea>
                </div>
                <div class="edit-modal-footer">
                    <button class="edit-modal-btn edit-modal-cancel" onclick="closeEditModal()">Annuler</button>
                    <button class="edit-modal-btn edit-modal-save" onclick="saveEditedMessage()">Enregistrer</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
        
        // Focus sur le textarea
        setTimeout(() => {
            const textarea = modal.querySelector('.edit-modal-textarea');
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
        
        const textarea = modal.querySelector('.edit-modal-textarea');
        const newContent = textarea.value.trim();
        
        if (!newContent) {
            showNotification('Le message ne peut pas être vide', 'error');
            return;
        }
        
        if (newContent.length > 2000) {
            showNotification('Le message est trop long (max 2000 caractères)', 'error');
            return;
        }
        
        // Désactiver le bouton
        const saveButton = modal.querySelector('.edit-modal-save');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        saveButton.disabled = true;
        
        try {
            // Mise à jour optimiste
            const messageElement = document.getElementById('group-message-' + currentEditingMessageId);
            if (messageElement) {
                const contentElement = messageElement.querySelector('.group-message-content');
                contentElement.textContent = newContent;
            }
            
            // Si c'est un message temporaire, fermer simplement le modal
            if (typeof currentEditingMessageId === 'string' && currentEditingMessageId.startsWith('temp-')) {
                closeEditModal();
                showNotification('Message modifié', 'success');
                return;
            }
            
            // Envoyer la requête de modification
            const formData = new FormData();
            formData.append('message_id', currentEditingMessageId);
            formData.append('content', newContent);
            
            const response = await fetch('updategroupmessage.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                closeEditModal();
                showNotification('Message modifié avec succès', 'success');
            } else {
                // Revenir à l'ancien contenu
                if (messageElement) {
                    const contentElement = messageElement.querySelector('.group-message-content');
                    const oldContent = contentElement.dataset.originalContent || '';
                    contentElement.textContent = oldContent;
                }
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
    
    // Afficher une notification
    function showNotification(message, type = 'info') {
        // Supprimer les anciennes notifications
        const oldNotifications = document.querySelectorAll('.notification');
        oldNotifications.forEach(n => n.remove());
        
        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                ${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'} ${message}
            </div>
        `;
        
        // Style de la notification
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            padding: 12px 20px;
            border-radius: 6px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease;
        `;
        
        if (type === 'success') {
            notification.style.background = 'linear-gradient(135deg, #2ecc71, #27ae60)';
        } else if (type === 'error') {
            notification.style.background = 'linear-gradient(135deg, #e74c3c, #c0392b)';
        } else {
            notification.style.background = 'linear-gradient(135deg, #4361ee, #3a56d4)';
        }
        
        document.body.appendChild(notification);
        
        // Supprimer après 3 secondes
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 3000);
    }
    
    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll vers le bas au chargement
        scrollToBottom();
        
        // Configurer le textarea
        const messageInput = document.getElementById('groupMessageInput');
        if (messageInput) {
            // Auto-resize
            messageInput.addEventListener('input', function() {
                autoResizeTextarea(this);
            });
            
            // Envoyer avec Enter (pas Shift+Enter)
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const sendButton = document.getElementById('groupSendButton');
                    if (sendButton && !sendButton.disabled) {
                        sendGroupMessage(e);
                    }
                }
            });
        }
        
        // Initialiser le bouton d'envoi
        const sendButton = document.getElementById('groupSendButton');
        if (sendButton && messageInput) {
            sendButton.disabled = messageInput.value.trim() === '';
        }
    });
    
    // Ajouter les animations CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    </script>
</body>
</html>