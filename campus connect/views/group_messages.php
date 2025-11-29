<?php
session_start();
// For demo, set current user
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'Jean';

require_once '../model/group.php';

$current_group = null;
if (isset($_GET['group_id'])) {
    $group_id = (int)$_GET['group_id'];
    $current_group = getGroup($group_id);
}

// Handle sending messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['group_id']) && isset($_POST['content'])) {
    $group_id = (int)$_POST['group_id'];
    $content = trim($_POST['content']);
    
    if (!empty($content)) {
        addGroupMessage($group_id, $_SESSION['user_id'], $_SESSION['username'], $content);
    }
    
    // Redirect to avoid form resubmission
    header("Location: group_messages.php?group_id=" . $group_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groupes - Campus Connect</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .groups-container {
            display: flex;
            height: 75vh;
            gap: 20px;
            margin-top: 20px;
        }
        
        .groups-list {
            width: 350px;
            background: white;
            border-radius: 8px;
            padding: 15px;
            overflow-y: auto;
        }
        
        .group-chat-area {
            flex: 1;
            background: white;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
        }
        
        .group-item {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            cursor: pointer;
            transition: background 0.3s;
            border-left: 4px solid #3498db;
        }
        
        .group-item:hover {
            background: #f8f9fa;
        }
        
        .group-item.active {
            background: #3498db;
            color: white;
        }
        
        .group-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .group-description {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 8px;
        }
        
        .group-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }
        
        .group-subject {
            background: #e74c3c;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .chat-header {
            padding: 20px;
            border-bottom: 1px solid #ecf0f1;
            background: #2c3e50;
            color: white;
            border-radius: 8px 8px 0 0;
        }
        
        .group-info {
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .group-title {
            font-size: 20px;
            margin: 0;
        }
        
        .group-stats {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .messages-list {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            position: relative;
        }
        
        .message.own {
            align-self: flex-end;
            background: #3498db;
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .message.other {
            align-self: flex-start;
            background: #ecf0f1;
            color: #333;
            border-bottom-left-radius: 4px;
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
        }
        
        .message-username {
            font-weight: bold;
        }
        
        .message-time {
            opacity: 0.7;
        }
        
        .message-form {
            padding: 20px;
            border-top: 1px solid #ecf0f1;
            display: flex;
            gap: 10px;
        }
        
        .message-form input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #bdc3c7;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .message-form button {
            padding: 12px 24px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .message-form button:hover {
            background: #2980b9;
        }
        
        .search-groups {
            margin-bottom: 15px;
        }
        
        .search-results {
            position: absolute;
            background: white;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            width: 320px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .search-result-item {
            padding: 12px;
            cursor: pointer;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .search-result-item:hover {
            background: #f8f9fa;
        }
        
        .no-group-selected {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #7f8c8d;
            text-align: center;
        }
        
        .subject-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #3498db;
            color: white;
            border-radius: 15px;
            font-size: 12px;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>
    
    <div class="main-content">
        <div class="content-section">
            <h2 class="section-title">Groupes de Discussion</h2>
            
            <div class="groups-container">
                <!-- Groups List -->
                <div class="groups-list">
                    <div class="search-groups">
                        <input type="text" id="groupSearch" placeholder="Rechercher un groupe..." style="width: 100%; padding: 10px;">
                        <div id="searchResults" class="search-results" style="display: none;"></div>
                    </div>
                    <div id="groupsList">
                        <?php
                        $groups = getAllGroups();
                        foreach ($groups as $group): ?>
                            <div class="group-item <?= $current_group && $current_group['id'] == $group['id'] ? 'active' : '' ?>" 
                                 onclick="selectGroup(<?= $group['id'] ?>)">
                                <div class="group-name">
                                    <span class="subject-badge"><?= $group['subject'] ?></span>
                                    <?= $group['name'] ?>
                                </div>
                                <div class="group-description"><?= $group['description'] ?></div>
                                <div class="group-meta">
                                    <span>👥 <?= $group['member_count'] ?> membres</span>
                                    <span>Créé le <?= date('d/m/Y', strtotime($group['created_at'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Group Chat Area -->
                <div class="group-chat-area">
                    <?php if ($current_group): ?>
                        <div class="chat-header">
                            <div class="group-info">
                                <div>
                                    <h3 class="group-title"><?= $current_group['name'] ?></h3>
                                    <div class="group-stats">
                                        <?= $current_group['description'] ?> • 👥 <?= $current_group['member_count'] ?> membres
                                    </div>
                                </div>
                                <div class="group-subject"><?= $current_group['subject'] ?></div>
                            </div>
                        </div>
                        
                        <div class="messages-list" id="messagesList">
                            <?php
                            $messages = getGroupMessages($current_group['id']);
                            if (empty($messages)) {
                                echo '<div class="no-group-selected">
                                    <div>
                                        <h3>Soyez le premier à parler!</h3>
                                        <p>Commencez la conversation dans ce groupe</p>
                                    </div>
                                </div>';
                            } else {
                                foreach ($messages as $msg): 
                                    $is_own = $msg['user_id'] == $_SESSION['user_id'];
                            ?>
                                <div class="message <?= $is_own ? 'own' : 'other' ?>">
                                    <div class="message-header">
                                        <span class="message-username"><?= $msg['username'] ?></span>
                                        <span class="message-time"><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                                    </div>
                                    <div class="message-content"><?= htmlspecialchars($msg['content']) ?></div>
                                </div>
                            <?php endforeach;
                            }
                            ?>
                        </div>
                        
                        <form method="POST" class="message-form">
                            <input type="hidden" name="group_id" value="<?= $current_group['id'] ?>">
                            <input type="text" name="content" placeholder="Écrivez votre message..." required>
                            <button type="submit">Envoyer</button>
                        </form>
                    <?php else: ?>
                        <div class="no-group-selected">
                            <div>
                                <h3>Sélectionnez un groupe</h3>
                                <p>Choisissez un groupe de discussion pour commencer à échanger</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectGroup(groupId) {
            window.location.href = 'group_messages.php?group_id=' + groupId;
        }

        // Auto-scroll to bottom of messages
        document.addEventListener('DOMContentLoaded', function() {
            const messagesList = document.getElementById('messagesList');
            if (messagesList) {
                messagesList.scrollTop = messagesList.scrollHeight;
            }
        });

        // Search groups
        document.getElementById('groupSearch').addEventListener('input', function(e) {
            const query = e.target.value;
            const results = document.getElementById('searchResults');
            
            if (query.length < 2) {
                results.style.display = 'none';
                return;
            }
            
            // Sample search results
            const sampleGroups = [
                {id: 1, name: 'Programmation', subject: 'Informatique', description: 'Discussions sur la programmation'},
                {id: 2, name: 'Mathématiques', subject: 'Maths', description: 'Aide en mathématiques'},
                {id: 3, name: 'Physique-Chimie', subject: 'Sciences', description: 'Échanges scientifiques'},
                {id: 4, name: 'Histoire-Géo', subject: 'Humanités', description: 'Discussions historiques'},
                {id: 5, name: 'Langues Étrangères', subject: 'Langues', description: 'Pratique des langues'},
                {id: 6, name: 'Projets Étudiants', subject: 'Projets', description: 'Coordination des projets'}
            ];
            
            const filteredGroups = sampleGroups.filter(group => 
                group.name.toLowerCase().includes(query.toLowerCase()) || 
                group.description.toLowerCase().includes(query.toLowerCase()) ||
                group.subject.toLowerCase().includes(query.toLowerCase())
            );
            
            results.innerHTML = '';
            
            if (filteredGroups.length === 0) {
                results.style.display = 'none';
                return;
            }
            
            filteredGroups.forEach(group => {
                const div = document.createElement('div');
                div.className = 'search-result-item';
                div.innerHTML = `
                    <strong>${group.name}</strong> 
                    <span style="background: #e74c3c; color: white; padding: 1px 6px; border-radius: 10px; font-size: 10px; margin-left: 8px;">${group.subject}</span>
                    <div style="font-size: 12px; color: #666; margin-top: 4px;">${group.description}</div>
                `;
                div.onclick = () => {
                    selectGroup(group.id);
                    results.style.display = 'none';
                    e.target.value = '';
                };
                results.appendChild(div);
            });
            
            results.style.display = 'block';
        });

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-groups')) {
                document.getElementById('searchResults').style.display = 'none';
            }
        });
    </script>
</body>
</html>