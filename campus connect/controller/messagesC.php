<?php
session_start();
require_once '../model/message.php'; // We'll create this next

class MessagesController {
    
    // Get all conversations for the current user
    public function getConversations() {
        if (!isset($_SESSION['user_id'])) {
            return ['error' => 'User not authenticated'];
        }
        
        $user_id = $_SESSION['user_id'];
        return getConversations($user_id);
    }
    
    // Get messages between current user and another user
    public function getMessages() {
        if (!isset($_SESSION['user_id'])) {
            return ['error' => 'User not authenticated'];
        }
        
        if (!isset($_GET['receiver_id'])) {
            return ['error' => 'Receiver ID required'];
        }
        
        $user_id = $_SESSION['user_id'];
        $receiver_id = (int)$_GET['receiver_id'];
        
        return getMessages($user_id, $receiver_id);
    }
    
    // Send a new message
    public function sendMessage() {
        if (!isset($_SESSION['user_id'])) {
            return ['error' => 'User not authenticated'];
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['error' => 'Invalid request method'];
        }
        
        if (!isset($_POST['receiver_id']) || !isset($_POST['content']) || empty($_POST['content'])) {
            return ['error' => 'Receiver ID and message content required'];
        }
        
        $sender_id = $_SESSION['user_id'];
        $receiver_id = (int)$_POST['receiver_id'];
        $content = trim($_POST['content']);
        
        if (sendMessage($sender_id, $receiver_id, $content)) {
            return ['success' => 'Message sent successfully'];
        } else {
            return ['error' => 'Failed to send message'];
        }
    }
    
    // Search users for messaging
    public function searchUsers() {
        if (!isset($_GET['query'])) {
            return ['error' => 'Search query required'];
        }
        
        $query = $_GET['query'];
        return searchUsers($query, isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
    }
}

// Handle requests
$controller = new MessagesController();
$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'get_conversations':
        echo json_encode($controller->getConversations());
        break;
        
    case 'get_messages':
        echo json_encode($controller->getMessages());
        break;
        
    case 'send_message':
        echo json_encode($controller->sendMessage());
        break;
        
    case 'search_users':
        echo json_encode($controller->searchUsers());
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}