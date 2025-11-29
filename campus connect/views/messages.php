<?php
session_start();
// Set current user (for demo - user_id 1)
$_SESSION['user_id'] = 1;
$current_user_id = $_SESSION['user_id'];

require_once '../model/message.php';

// Handle sending messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id']) && isset($_POST['content'])) {
    $receiver_id = (int)$_POST['receiver_id'];
    $content = trim($_POST['content']);
    
    if (!empty($content)) {
        sendMessage($current_user_id, $receiver_id, $content);
    }
    
    // Redirect to avoid form resubmission
    header("Location: messages.php?user_id=" . $receiver_id);
    exit();
}

// Get current conversation user
$current_chat_user_id = $_GET['user_id'] ?? null;
$current_chat_user = null;
if ($current_chat_user_id) {
    $user_info = getUserInfo($current_chat_user_id);
    $current_chat_user = [
        'id' => $current_chat_user_id,
        'username' => $user_info['username'],
        'email' => $user_info['email']
    ];
}

// Get conversations and messages
$conversations = getConversations($current_user_id);
$messages = [];
if ($current_chat_user_id) {
    $messages = getMessages($current_user_id, $current_chat_user_id);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Campus Connect</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .messages-container {
            display: flex;
            height: 70vh;
            gap: 20px;
            margin-top: 20px;
        }
        
        .conversations-list {
            width: 350px;
            background: white;
            border-radius: 8px;
            padding: 15px;
            overflow-y: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .chat-area {
            flex: 1;
            background: white;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .conversation-item {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .conversation-item:hover {
            background: #f8f9fa;
            border-left-color: #3498db;
        }
        
        .conversation-item.active {
            background: #3498db;
            color: white;
            border-left-color: #2980b9;
        }
        
        .conversation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .conversation-name {
            font-weight: bold;
            font-size: 16px;
        }
        
        .conversation-time {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .conversation-preview {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .unread-badge {
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            margin-left: 8px;
        }
        
        .chat-header {
            padding: 20px;
            border-bottom: 1px solid #ecf0f1;
            background: #2c3e50;
            color: white;
            border-radius: 8px 8px 0 0;
        }
        
        .chat-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .messages-list {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: #f8f9fa;
        }
        
        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            position: relative;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .message.sent {
            align-self: flex-end;
            background: #3498db;
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .message.received {
            align-self: flex-start;
            background: white;
            color: #333;
            border: 1px solid #e1e8ed;
            border-bottom-left-radius: 4px;
        }
        
        .message-content {
            margin-bottom: 5px;
            line-height: 1.4;
        }
        
        .message-time {
            font-size: 11px;
            opacity: 0.7;
            text-align: right;
        }
        
        .message-form {
            padding: 20px;
            border-top: 1px solid #ecf0f1;
            display: flex;
            gap: 10px;
            background: white;
        }
        
        .message-form input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #bdc3c7;
            border-radius: 20px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .message-form input:focus {
            border-color: #3498db;
        }
        
        .message-form button {
            padding: 12px 24px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .message-form button:hover {
            background: #2980b9;
        }
        
        .search-users {
            margin-bottom: 15px;
            position: relative;
        }
        
        .search-results {
            position: absolute;
            background: white;
            border: 1px solid #bdc3c7;
            border-radius: 8px;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            top: 100%;
            left: 0;
        }
        
        .search-result-item {
            padding: 12px;
            cursor: pointer;
            border-bottom: 1px solid #ecf0f1;
            transition: background 0.2s;
        }
        
        .search-result-item:hover {
            background: #f8f9fa;
        }
        
        .no-conversation {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #7f8c8d;
            text-align: center;
            flex-direction: column;
            gap: 15px;
        }
        
        .no-conversation-icon {
            font-size: 48px;
            opacity: 0.5;
        }
        
        .conversation-empty {
            padding: 20px;
            text-align: center;
            color: #7f8c8d;
        }
        
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 10px;
            color: #7f8c8d;
            font-style: italic;
        }
        
        .typing-dots {
            display: flex;
            gap: 2px;
        }
        
        .typing-dot {
            width: 6px;
            height: 6px;
            background: #7f8c8d;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }
        
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-5px); }
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>
    
    <div class="main-content">
        <div class="content-section">
            <h2 class="section-title">Messages Privés</h2>
            
            <div class="messages-container">
                <!-- Conversations List -->
                <div class="conversations-list">
                    <div class="search-users">
                        <input type="text" id="userSearch" placeholder="Rechercher un utilisateur..." style="width: 100%; padding: 10px;">
                        <div id="searchResults" class="search-results" style="display: none;"></div>
                    </div>
                    <div id="conversationsList">
                        <?php if (empty($conversations)): ?>
                            <div class="conversation-empty">
                                <p>Aucune conversation</p>
                                <small>Commencez une nouvelle conversation en recherchant un utilisateur</small>
                            </div>
                        <?php else: ?>
                            <?php foreach ($conversations as $conv): ?>
                                <div class="conversation-item <?= $current_chat_user_id == $conv['user_id'] ? 'active' : '' ?>" 
                                     onclick="openChat(<?= $conv['user_id'] ?>, '<?= $conv['username'] ?>')">
                                    <div class="conversation-header">
                                        <div class="conversation-name">
                                            <?= $conv['username'] ?>
                                            <?php if ($conv['unread_count'] > 0): ?>
                                                <span class="unread-badge"><?= $conv['unread_count'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="conversation-time">
                                            <?= date('H:i', strtotime($conv['last_message_time'])) ?>
                                        </div>
                                    </div>
                                    <div class="conversation-preview">
                                        <?= htmlspecialchars($conv['last_message']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Chat Area -->
                <div class="chat-area">
                    <?php if ($current_chat_user): ?>
                        <div class="chat-header">
                            <div class="chat-user-info">
                                <div class="user-avatar">
                                    <?= strtoupper(substr($current_chat_user['username'], 0, 1)) ?>
                                </div>
                                <div>
                                    <h3 style="margin: 0;"><?= $current_chat_user['username'] ?></h3>
                                    <small style="opacity: 0.8;"><?= $current_chat_user['email'] ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="messages-list" id="messagesList">
                            <?php if (empty($messages)): ?>
                                <div class="conversation-empty">
                                    <p>Aucun message</p>
                                    <small>Soyez le premier à envoyer un message!</small>
                                </div>
                            <?php else: ?>
                                <?php foreach ($messages as $msg): 
                                    $is_sent = $msg['sender_id'] == $current_user_id;
                                ?>
                                    <div class="message <?= $is_sent ? 'sent' : 'received' ?>">
                                        <div class="message-content">
                                            <?= htmlspecialchars($msg['content']) ?>
                                        </div>
                                        <div class="message-time">
                                            <?= date('H:i', strtotime($msg['created_at'])) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <!-- Typing indicator (hidden by default) -->
                                <div id="typingIndicator" class="typing-indicator" style="display: none;">
                                    <span><?= $current_chat_user['username'] ?> écrit</span>
                                    <div class="typing-dots">
                                        <div class="typing-dot"></div>
                                        <div class="typing-dot"></div>
                                        <div class="typing-dot"></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <form method="POST" class="message-form" id="messageForm">
                            <input type="hidden" name="receiver_id" value="<?= $current_chat_user['id'] ?>">
                            <input type="text" name="content" placeholder="Tapez votre message..." required id="messageInput">
                            <button type="submit">Envoyer</button>
                        </form>
                    <?php else: ?>
                        <div class="no-conversation">
                            <div class="no-conversation-icon">💬</div>
                            <h3>Sélectionnez une conversation</h3>
                            <p>Choisissez une conversation existante ou commencez-en une nouvelle</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentReceiverId = null;
        let currentReceiverName = null;

        function openChat(userId, username) {
            currentReceiverId = userId;
            currentReceiverName = username;
            window.location.href = `messages.php?user_id=${userId}`;
        }

        // Auto-scroll to bottom of messages
        function scrollToBottom() {
            const messagesList = document.getElementById('messagesList');
            if (messagesList) {
                messagesList.scrollTop = messagesList.scrollHeight;
            }
        }

        // Search users
        document.getElementById('userSearch').addEventListener('input', function(e) {
            const query = e.target.value;
            const results = document.getElementById('searchResults');
            
            if (query.length < 2) {
                results.style.display = 'none';
                return;
            }
            
            // Sample users for search
            const sampleUsers = [
                {id: 2, username: 'Marie', email: 'marie@campus.com'},
                {id: 3, username: 'Pierre', email: 'pierre@campus.com'},
                {id: 4, username: 'Sophie', email: 'sophie@campus.com'},
                {id: 5, username: 'Thomas', email: 'thomas@campus.com'},
                {id: 6, username: 'Laura', email: 'laura@campus.com'}
            ];
            
            const filteredUsers = sampleUsers.filter(user => 
                user.username.toLowerCase().includes(query.toLowerCase()) || 
                user.email.toLowerCase().includes(query.toLowerCase())
            );
            
            results.innerHTML = '';
            
            if (filteredUsers.length === 0) {
                results.style.display = 'none';
                return;
            }
            
            filteredUsers.forEach(user => {
                const div = document.createElement('div');
                div.className = 'search-result-item';
                div.innerHTML = `
                    <strong>${user.username}</strong>
                    <div style="font-size: 12px; color: #666;">${user.email}</div>
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

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-users')) {
                document.getElementById('searchResults').style.display = 'none';
            }
        });

        // Simulate typing indicator
        document.getElementById('messageInput')?.addEventListener('focus', function() {
            if (currentReceiverId) {
                // Show typing indicator after a delay
                setTimeout(() => {
                    const typingIndicator = document.getElementById('typingIndicator');
                    if (typingIndicator) {
                        typingIndicator.style.display = 'flex';
                        scrollToBottom();
                        
                        // Hide after 2 seconds
                        setTimeout(() => {
                            typingIndicator.style.display = 'none';
                        }, 2000);
                    }
                }, 1000);
            }
        });

        // Scroll to bottom when page loads
        document.addEventListener('DOMContentLoaded', function() {
            scrollToBottom();
            
            // Focus on message input if in a conversation
            const messageInput = document.getElementById('messageInput');
            if (messageInput && currentReceiverId) {
                messageInput.focus();
            }
        });

        // Enter key to send message
        document.getElementById('messageInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('messageForm').dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>