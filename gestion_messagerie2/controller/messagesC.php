<?php
session_start();
require_once '../model/message.php';

// Handle requests
$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

try {
    switch ($action) {
        case 'get_conversations':
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'error' => 'Not authenticated']);
                break;
            }
            $conversations = getConversations($_SESSION['user_id']);
            echo json_encode(['success' => true, 'conversations' => $conversations]);
            break;
            
        case 'get_messages':
            if (!isset($_SESSION['user_id']) || !isset($_GET['receiver_id'])) {
                echo json_encode(['success' => false, 'error' => 'Missing data']);
                break;
            }
            $messages = getMessages($_SESSION['user_id'], (int)$_GET['receiver_id']);
            echo json_encode(['success' => true, 'messages' => $messages]);
            break;
            
        case 'send_message':
            if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'error' => 'Invalid request']);
                break;
            }
            
            $receiver_id = isset($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : 0;
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            
            if (!$receiver_id || empty($content)) {
                echo json_encode(['success' => false, 'error' => 'Invalid data']);
                break;
            }
            
            // Send the message
            $result = sendMessage($_SESSION['user_id'], $receiver_id, $content);
            
            if ($result['success']) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Message sent successfully',
                    'sender_id' => $_SESSION['user_id'],
                    'receiver_id' => $receiver_id,
                    'content' => $content,
                    'created_at' => date('Y-m-d H:i:s'),
                    'message_id' => $result['id'] ?? null
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to send message']);
            }
            break;
            
        case 'search_users':
            $query = $_GET['query'] ?? '';
            $users = searchUsers($query, $_SESSION['user_id'] ?? null);
            echo json_encode(['success' => true, 'users' => $users]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    error_log("MessagesC error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Server error']);
}
?>