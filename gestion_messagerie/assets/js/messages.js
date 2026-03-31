// messages.js - VERSION CORRIGÉE ET SIMPLIFIÉE
let currentReceiverId = null;
let currentUsername = null;

// Load conversations
function loadConversations() {
    fetch('../controller/messagesC.php?action=get_conversations')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            
            const container = document.getElementById('conversationsList');
            container.innerHTML = '';
            
            if (data.length === 0) {
                container.innerHTML = '<p>Aucune conversation</p>';
                return;
            }
            
            data.forEach(conv => {
                const div = document.createElement('div');
                div.className = 'conversation-item';
                div.innerHTML = `
                    <strong>${conv.username}</strong>
                    <p style="font-size: 12px; margin: 5px 0;">${conv.last_message}</p>
                    <small>${new Date(conv.last_message_time).toLocaleString()}</small>
                    ${conv.unread_count > 0 ? `<span class="unread-badge">${conv.unread_count}</span>` : ''}
                `;
                div.onclick = () => openChat(conv.user_id, conv.username);
                container.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Erreur chargement conversations:', error);
        });
}

// Open chat with user
function openChat(userId, username) {
    currentReceiverId = userId;
    currentUsername = username;
    
    // Mettre à jour l'interface
    const chatUserElement = document.getElementById('currentChatUser');
    const messageForm = document.getElementById('messageForm');
    
    if (chatUserElement) {
        chatUserElement.textContent = username;
    }
    
    if (messageForm) {
        messageForm.style.display = 'flex';
    }
    
    // Highlight selected conversation
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
    });
    event.target.closest('.conversation-item').classList.add('active');
    
    // Charger les messages
    loadMessages(userId);
}

// Load messages - VERSION SIMPLIFIÉE
function loadMessages(receiverId) {
    fetch(`../controller/messagesC.php?action=get_messages&receiver_id=${receiverId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Erreur chargement messages:', data.error);
                return;
            }
            
            const container = document.getElementById('messagesList');
            if (!container) {
                console.error('Element messagesList non trouvé');
                return;
            }
            
            container.innerHTML = '';
            
            if (data.length === 0) {
                container.innerHTML = '<p class="no-messages">Aucun message. Commencez la conversation!</p>';
                return;
            }
            
            data.forEach(msg => {
                const isSent = msg.sender_id == window.currentUserId; // Vous devez définir currentUserId dans votre HTML
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
                messageDiv.id = `message-${msg.id}`;
                
                messageDiv.innerHTML = `
                    <div class="message-content" id="message-content-${msg.id}">
                        ${msg.content}
                    </div>
                    <div class="message-time">
                        <small>${new Date(msg.created_at).toLocaleString('fr-FR', { 
                            hour: '2-digit', 
                            minute: '2-digit',
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        })}</small>
                        ${isSent ? `
                            <div class="message-actions">
                                <button class="edit-btn" onclick="editMessage(${msg.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-btn" onclick="deleteMessage(${msg.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        ` : ''}
                    </div>
                `;
                
                container.appendChild(messageDiv);
            });
            
            // Scroll to bottom
            container.scrollTop = container.scrollHeight;
        })
        .catch(error => {
            console.error('Erreur chargement messages:', error);
        });
}

// Send message - VERSION SIMPLIFIÉE
function sendMessage() {
    const input = document.getElementById('messageInput');
    const content = input.value.trim();
    
    if (!content || !currentReceiverId) {
        alert('Veuillez entrer un message et sélectionner un destinataire');
        return;
    }
    
    const formData = new FormData();
    formData.append('receiver_id', currentReceiverId);
    formData.append('content', content);
    
    fetch('../controller/messagesC.php?action=send_message', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Erreur: ' + data.error);
        } else {
            input.value = '';
            loadMessages(currentReceiverId);
            loadConversations();
            showNotification('Message envoyé avec succès', 'success');
        }
    })
    .catch(error => {
        console.error('Erreur envoi message:', error);
        showNotification('Erreur lors de l\'envoi', 'error');
    });
}

// Delete message - VERSION SIMPLIFIÉE
function deleteMessage(messageId) {
    if (!confirm('Voulez-vous vraiment supprimer ce message ?')) {
        return;
    }
    
    const messageElement = document.getElementById(`message-${messageId}`);
    if (!messageElement) {
        console.error('Message non trouvé dans le DOM');
        return;
    }
    
    // Animation de suppression immédiate
    messageElement.style.transition = 'all 0.3s ease';
    messageElement.style.opacity = '0.5';
    
    fetch('deletemessage.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `message_id=${messageId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Animation de suppression
            messageElement.style.opacity = '0';
            messageElement.style.height = '0';
            messageElement.style.margin = '0';
            messageElement.style.padding = '0';
            messageElement.style.overflow = 'hidden';
            
            setTimeout(() => {
                if (messageElement.parentNode) {
                    messageElement.remove();
                }
                
                // Vérifier s'il reste des messages
                const messagesList = document.getElementById('messagesList');
                if (messagesList && messagesList.children.length === 0) {
                    messagesList.innerHTML = '<p class="no-messages">Aucun message. Commencez la conversation!</p>';
                }
                
                showNotification('Message supprimé avec succès', 'success');
            }, 300);
        } else {
            // Annuler l'animation
            messageElement.style.opacity = '1';
            showNotification('Erreur: ' + (data.message || 'Impossible de supprimer'), 'error');
        }
    })
    .catch(error => {
        console.error('Erreur suppression:', error);
        messageElement.style.opacity = '1';
        showNotification('Erreur de connexion', 'error');
    });
}

// Edit message - VERSION SIMPLIFIÉE
function editMessage(messageId) {
    const messageElement = document.getElementById(`message-${messageId}`);
    if (!messageElement) {
        console.error('Message non trouvé');
        return;
    }
    
    const contentElement = messageElement.querySelector('.message-content');
    const currentContent = contentElement.textContent;
    
    const newContent = prompt('Modifiez votre message:', currentContent);
    
    if (newContent === null || newContent.trim() === '' || newContent === currentContent) {
        return;
    }
    
    // Mise à jour optimiste
    contentElement.textContent = newContent;
    contentElement.style.opacity = '0.7';
    
    fetch('updatemessage.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `message_id=${messageId}&content=${encodeURIComponent(newContent)}`
    })
    .then(response => response.json())
    .then(data => {
        contentElement.style.opacity = '1';
        
        if (data.success) {
            showNotification('Message modifié avec succès', 'success');
        } else {
            // Revenir à l'ancien contenu
            contentElement.textContent = currentContent;
            showNotification('Erreur: ' + (data.message || 'Impossible de modifier'), 'error');
        }
    })
    .catch(error => {
        console.error('Erreur modification:', error);
        contentElement.textContent = currentContent;
        contentElement.style.opacity = '1';
        showNotification('Erreur de connexion', 'error');
    });
}

// Search users
function searchUsers() {
    const searchInput = document.getElementById('userSearch');
    const query = searchInput.value.trim();
    const results = document.getElementById('searchResults');
    
    if (!results) return;
    
    if (query.length < 2) {
        results.style.display = 'none';
        return;
    }
    
    fetch(`../controller/messagesC.php?action=search_users&query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            results.innerHTML = '';
            
            if (data.error || data.length === 0) {
                results.innerHTML = '<div class="no-results">Aucun utilisateur trouvé</div>';
                results.style.display = 'block';
                return;
            }
            
            data.forEach(user => {
                const div = document.createElement('div');
                div.className = 'search-result-item';
                div.innerHTML = `
                    <strong>${user.username}</strong>
                    <small>${user.email}</small>
                `;
                div.onclick = () => {
                    openChat(user.id, user.username);
                    searchInput.value = '';
                    results.style.display = 'none';
                };
                results.appendChild(div);
            });
            
            results.style.display = 'block';
        })
        .catch(error => {
            console.error('Erreur recherche:', error);
        });
}

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            ${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'} ${message}
        </div>
    `;
    
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
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Définir l'ID utilisateur actuel depuis PHP
    if (typeof currentUserId === 'undefined') {
        console.warn('currentUserId non défini. Vérifiez votre code PHP.');
    }
    
    // Charger les conversations
    loadConversations();
    
    // Configuration de la recherche
    const searchInput = document.getElementById('userSearch');
    if (searchInput) {
        searchInput.addEventListener('input', searchUsers);
    }
    
    // Configuration de l'envoi de message
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }
    
    // Configuration du bouton d'envoi
    const sendButton = document.getElementById('sendButton');
    if (sendButton) {
        sendButton.addEventListener('click', sendMessage);
    }
    
    // Fermer les résultats de recherche en cliquant ailleurs
    document.addEventListener('click', function(e) {
        const searchContainer = document.querySelector('.search-users');
        const results = document.getElementById('searchResults');
        
        if (results && searchContainer && !searchContainer.contains(e.target)) {
            results.style.display = 'none';
        }
    });
    
    // Rafraîchir périodiquement
    setInterval(() => {
        if (currentReceiverId) {
            loadMessages(currentReceiverId);
        }
        loadConversations();
    }, 30000);
});

// CSS animations
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
    
    .message {
        transition: all 0.3s ease;
    }
    
    .message.sent {
        align-self: flex-end;
        background: linear-gradient(135deg, #4361ee, #3a56d4);
        color: white;
        border-radius: 18px 18px 4px 18px;
        padding: 10px 15px;
        margin: 5px 0;
        max-width: 70%;
    }
    
    .message.received {
        align-self: flex-start;
        background: #f0f2f5;
        color: #333;
        border-radius: 18px 18px 18px 4px;
        padding: 10px 15px;
        margin: 5px 0;
        max-width: 70%;
    }
    
    .message-time {
        font-size: 11px;
        opacity: 0.7;
        margin-top: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .message-actions {
        display: flex;
        gap: 5px;
        margin-left: 10px;
    }
    
    .edit-btn, .delete-btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        cursor: pointer;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        transition: all 0.2s;
    }
    
    .message.sent .edit-btn:hover,
    .message.sent .delete-btn:hover {
        background: rgba(255,255,255,0.3);
    }
    
    .message.received .edit-btn,
    .message.received .delete-btn {
        background: rgba(0,0,0,0.1);
        color: #333;
    }
    
    .message.received .edit-btn:hover,
    .message.received .delete-btn:hover {
        background: rgba(0,0,0,0.2);
    }
    
    .conversation-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .conversation-item:hover,
    .conversation-item.active {
        background: #f5f5f5;
    }
    
    .unread-badge {
        background: #e74c3c;
        color: white;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 10px;
        float: right;
    }
    
    .search-result-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .search-result-item:hover {
        background: #f5f5f5;
    }
    
    .no-messages {
        text-align: center;
        color: #999;
        padding: 20px;
    }
    
    .no-results {
        padding: 10px;
        color: #999;
        text-align: center;
    }
`;
document.head.appendChild(style);