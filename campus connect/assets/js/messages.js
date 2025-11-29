let currentReceiverId = null;

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
        });
}

// Open chat with user
function openChat(userId, username) {
    currentReceiverId = userId;
    document.getElementById('currentChatUser').textContent = username;
    document.getElementById('messageForm').style.display = 'flex';
    
    // Highlight selected conversation
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
    });
    event.target.closest('.conversation-item').classList.add('active');
    
    loadMessages(userId);
}

// Load messages
function loadMessages(receiverId) {
    fetch(`../controller/messagesC.php?action=get_messages&receiver_id=${receiverId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            
            const container = document.getElementById('messagesList');
            container.innerHTML = '';
            
            if (data.length === 0) {
                container.innerHTML = '<p>Aucun message. Commencez la conversation!</p>';
                return;
            }
            
            data.forEach(msg => {
                const messageDiv = document.createElement('div');
                const isSent = msg.sender_id == <?php echo $_SESSION['user_id']; ?>;
                messageDiv.className = `message ${isSent ? 'sent' : 'received'}`;
                messageDiv.innerHTML = `
                    <div>${msg.content}</div>
                    <small style="font-size: 10px; opacity: 0.7;">${new Date(msg.created_at).toLocaleString()}</small>
                `;
                container.appendChild(messageDiv);
            });
            
            container.scrollTop = container.scrollHeight;
        });
}

// Send message
function sendMessage() {
    const input = document.getElementById('messageInput');
    const content = input.value.trim();
    
    if (!content || !currentReceiverId) return;
    
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
            alert(data.error);
        } else {
            input.value = '';
            loadMessages(currentReceiverId);
            loadConversations(); // Refresh conversations list
        }
    });
}

// Search users
document.getElementById('userSearch').addEventListener('input', function(e) {
    const query = e.target.value;
    const results = document.getElementById('searchResults');
    
    if (query.length < 2) {
        results.style.display = 'none';
        return;
    }
    
    fetch(`../controller/messagesC.php?action=search_users&query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            results.innerHTML = '';
            
            if (data.error || data.length === 0) {
                results.style.display = 'none';
                return;
            }
            
            data.forEach(user => {
                const div = document.createElement('div');
                div.className = 'search-result-item';
                div.textContent = `${user.username} (${user.email})`;
                div.onclick = () => {
                    openChat(user.id, user.username);
                    results.style.display = 'none';
                    e.target.value = '';
                };
                results.appendChild(div);
            });
            
            results.style.display = 'block';
        });
});

// Close search results when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.search-users')) {
        document.getElementById('searchResults').style.display = 'none';
    }
});

// Enter key to send message
document.getElementById('messageInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Load conversations on page load
document.addEventListener('DOMContentLoaded', loadConversations);

// Refresh conversations every 30 seconds
setInterval(loadConversations, 30000);