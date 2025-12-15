<?php
session_start();

// Définir l'utilisateur pour la démo
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'Vous';
}

// Inclure les fonctions des groupes
$groupFile = __DIR__ . '/../../model/group.php';
if (file_exists($groupFile)) {
    require_once $groupFile;
} else {
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

        /* Top Header (Matches your existing theme) */
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

        /* Groups Sidebar */
        .groups-list {
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

        .groups-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(123, 45, 168, 0.1);
        }

        .groups-title {
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

        .groups-subtitle {
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.5;
            opacity: 0.8;
        }

        /* Group Items */
        .group-item {
            display: flex;
            padding: 18px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 12px;
            align-items: center;
            border: 2px solid transparent;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .group-item::before {
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

        .group-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(123, 45, 168, 0.15);
            border-color: rgba(255, 111, 177, 0.2);
        }

        .group-item:hover::before {
            opacity: 1;
        }

        .group-item.active {
            background: linear-gradient(135deg, rgba(123, 45, 168, 0.08), rgba(255, 111, 177, 0.06));
            border-color: rgba(255, 111, 177, 0.3);
        }

        .group-item.active::before {
            opacity: 1;
        }

        .group-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
            margin-right: 16px;
            flex-shrink: 0;
            box-shadow: 0 6px 20px rgba(123, 45, 168, 0.2);
        }

        .group-info {
            flex: 1;
            min-width: 0;
        }

        .group-name {
            font-weight: 600;
            font-size: 15px;
            color: var(--dark-blue);
            margin-bottom: 6px;
        }

        .group-description {
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

        .group-stats {
            display: flex;
            gap: 16px;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .group-stat {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        .group-stat i {
            color: var(--rose-1);
            font-size: 0.8rem;
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

        .chat-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .chat-avatar {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
            backdrop-filter: blur(10px);
        }

        .chat-details h3 {
            margin: 0 0 6px 0;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .chat-members {
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

        .message-user {
            font-size: 0.85rem;
            margin-bottom: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .message.sent .message-user {
            color: rgba(255, 255, 255, 0.9);
        }

        .message.received .message-user {
            color: var(--muted);
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 12px;
        }

        .message-content {
            font-size: 0.95rem;
            line-height: 1.5;
            word-break: break-word;
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

        /* Button ripple effect */
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

        /* No Group Selected */
        .no-group {
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

        .no-group-icon {
            font-size: 80px;
            color: rgba(123, 45, 168, 0.1);
            margin-bottom: 16px;
        }

        .no-group h3 {
            color: var(--dark-blue);
            font-size: 1.8rem;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .no-group p {
            max-width: 400px;
            line-height: 1.5;
            margin-bottom: 24px;
            font-size: 1rem;
            opacity: 0.8;
        }

        .no-group-tip {
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
        .groups-list::-webkit-scrollbar,
        .messages-list::-webkit-scrollbar {
            width: 6px;
        }

        .groups-list::-webkit-scrollbar-track,
        .messages-list::-webkit-scrollbar-track {
            background: rgba(123, 45, 168, 0.05);
            border-radius: 3px;
        }

        .groups-list::-webkit-scrollbar-thumb,
        .messages-list::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--purple-1), var(--rose-1));
            border-radius: 3px;
        }

        .groups-list::-webkit-scrollbar-thumb:hover,
        .messages-list::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--rose-1), var(--purple-1));
        }

        /* Responsive Design */
        @media screen and (max-width: 1200px) {
            .container {
                flex-direction: column;
                height: auto;
            }
            
            .groups-list {
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
    <!-- Top Header -->
    <div class="topbar">
        <h1>📚 Messages de Groupe - Campus Connect</h1>
        <div class="admin-button">
            <a href="../../index.php">🏠 Retour à l'accueil</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Groups Sidebar -->
        <div class="groups-list">
            <div class="groups-header">
                <h2 class="groups-title">
                    <div class="title-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    Groupes de Discussion
                    <span style="background: var(--rose-1); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; margin-left: 8px;">
                        <?= count($groups) ?>
                    </span>
                </h2>
                <p class="groups-subtitle">
                    Rejoignez des groupes thématiques et échangez avec la communauté
                </p>
            </div>
            
            <div class="groups-scroll">
                <?php if (empty($groups)): ?>
                    <div class="no-group" style="padding: 30px 15px;">
                        <div class="no-group-icon">
                            <i class="fas fa-users-slash"></i>
                        </div>
                        <h4>Aucun groupe disponible</h4>
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
        
        <!-- Chat Area -->
        <div class="chat-area">
            <?php if ($current_group): ?>
                <!-- Chat Header -->
                <div class="chat-header">
                    <div class="chat-info">
                        <div class="chat-avatar">
                            <?= htmlspecialchars(substr($current_group['name'], 0, 1)) ?>
                        </div>
                        <div class="chat-details">
                            <h3><?= htmlspecialchars($current_group['name']) ?></h3>
                            <div class="chat-members">
                                <i class="fas fa-users"></i>
                                <?= $current_group['member_count'] ?? 0 ?> membres
                                • <?= $current_group['message_count'] ?? 0 ?> messages
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Messages List -->
                <div class="messages-list" id="messagesList">
                    <?php if (empty($group_messages)): ?>
                        <div class="no-messages">
                            <div class="no-messages-icon">
                                <i class="far fa-comments"></i>
                            </div>
                            <h4>Aucun message dans ce groupe</h4>
                            <p>Soyez le premier à envoyer un message !</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($group_messages as $msg): 
                            $is_sent = $msg['user_id'] == $_SESSION['user_id'];
                        ?>
                            <div class="message <?= $is_sent ? 'sent' : 'received' ?>" 
                                 id="message-<?= $msg['id'] ?>"
                                 data-id="<?= $msg['id'] ?>">
                                <div class="message-user">
                                    <div class="user-avatar">
                                        <?= htmlspecialchars(substr($msg['username'] ?? '?', 0, 1)) ?>
                                    </div>
                                    <span><?= htmlspecialchars($msg['username'] ?? 'Utilisateur') ?></span>
                                    <?php if ($is_sent): ?>
                                        <span style="font-size: 10px; opacity: 0.8;">(vous)</span>
                                    <?php endif; ?>
                                </div>
                                <div class="message-content" id="message-content-<?= $msg['id'] ?>">
                                    <?= nl2br(htmlspecialchars($msg['content'])) ?>
                                </div>
                                <div class="message-time">
                                    <span><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                                    <?php if ($is_sent): ?>
                                        <div class="message-actions">
                                            <button class="edit-btn" onclick="editMessage(<?= $msg['id'] ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="delete-btn" onclick="deleteMessage(<?= $msg['id'] ?>, this)">
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
                    <form id="messageForm" class="message-form" onsubmit="return sendMessage(event)">
                        <input type="hidden" id="group_id" value="<?= $current_group['id'] ?>">
                        <div class="message-input-container">
                            <textarea id="messageInput" class="message-input" 
                                      placeholder="Écrivez votre message au groupe..." 
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
                <!-- No Group Selected -->
                <div class="no-group">
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
                    <div class="no-group-tip">
                        <i class="fas fa-lightbulb"></i>
                        Cliquez sur un groupe pour commencer
                    </div>
                </div>
            <?php endif; ?>
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
        const sendButton = document.getElementById('sendButton');
        if (sendButton) {
            sendButton.disabled = textarea.value.trim() === '';
        }
    }
    
    // Scroll vers le bas
    function scrollToBottom() {
        const messagesList = document.getElementById('messagesList');
        if (messagesList) {
            messagesList.scrollTop = messagesList.scrollHeight;
        }
    }
    
    // Envoyer un message
    async function sendMessage(event) {
        event.preventDefault();
        
        const messageInput = document.getElementById('messageInput');
        const groupId = document.getElementById('group_id').value;
        const message = messageInput.value.trim();
        
        if (!message || !groupId) {
            showNotification('Veuillez entrer un message', 'error');
            return false;
        }
        
        // Désactiver le bouton d'envoi
        const sendButton = document.getElementById('sendButton');
        const originalText = sendButton.innerHTML;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin loading"></i>';
        sendButton.disabled = true;
        
        // Ajout optimiste du message
        const messagesList = document.getElementById('messagesList');
        const tempId = 'temp-' + Date.now();
        
        const newMessage = document.createElement('div');
        newMessage.className = 'message sent';
        newMessage.id = 'message-' + tempId;
        newMessage.dataset.id = tempId;
        newMessage.innerHTML = `
            <div class="message-user">
                <div class="user-avatar"><?= substr($_SESSION['username'] ?? 'Y', 0, 1) ?></div>
                <span><?= htmlspecialchars($_SESSION['username'] ?? 'Vous') ?></span>
                <span style="font-size: 10px; opacity: 0.8;">(vous)</span>
            </div>
            <div class="message-content" id="message-content-${tempId}">
                ${message.replace(/\n/g, '<br>')}
            </div>
            <div class="message-time">
                <span>${new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</span>
                <div class="message-actions">
                    <button class="edit-btn" onclick="editMessage('${tempId}')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="delete-btn" onclick="deleteMessage('${tempId}', this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        // Supprimer le message "aucun message" s'il existe
        const noMessagesDiv = messagesList.querySelector('.no-messages');
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
                    newMessage.id = 'message-' + data.message_id;
                    newMessage.dataset.id = data.message_id;
                    
                    const contentDiv = newMessage.querySelector('.message-content');
                    if (contentDiv) {
                        contentDiv.id = 'message-content-' + data.message_id;
                    }
                    
                    // Mettre à jour les boutons
                    const editBtn = newMessage.querySelector('.edit-btn');
                    if (editBtn) {
                        editBtn.setAttribute('onclick', `editMessage(${data.message_id})`);
                    }
                    
                    const deleteBtn = newMessage.querySelector('.delete-btn');
                    if (deleteBtn) {
                        deleteBtn.setAttribute('onclick', `deleteMessage(${data.message_id}, this)`);
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
        const messagesList = document.getElementById('messagesList');
        if (messagesList && messagesList.children.length === 0) {
            messagesList.innerHTML = `
                <div class="no-messages">
                    <div class="no-messages-icon">
                        <i class="far fa-comments"></i>
                    </div>
                    <h4>Aucun message dans ce groupe</h4>
                    <p>Soyez le premier à envoyer un message !</p>
                </div>
            `;
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
        
        if (newContent.length > 2000) {
            showNotification('Le message est trop long (max 2000 caractères)', 'error');
            return;
        }
        
        // Désactiver le bouton
        const saveButton = modal.querySelector('.modal-btn.save');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin loading"></i>';
        saveButton.disabled = true;
        
        try {
            // Mise à jour optimiste
            const messageElement = document.getElementById('message-' + currentEditingMessageId);
            if (messageElement) {
                const contentElement = messageElement.querySelector('.message-content');
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
                    const contentElement = messageElement.querySelector('.message-content');
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
        // Scroll vers le bas au chargement
        scrollToBottom();
        
        // Configurer le textarea
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            // Auto-resize
            messageInput.addEventListener('input', function() {
                autoResizeTextarea(this);
            });
            
            // Envoyer avec Enter (pas Shift+Enter)
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const sendButton = document.getElementById('sendButton');
                    if (sendButton && !sendButton.disabled) {
                        sendMessage(e);
                    }
                }
            });
        }
        
        // Initialiser le bouton d'envoi
        const sendButton = document.getElementById('sendButton');
        if (sendButton && messageInput) {
            sendButton.disabled = messageInput.value.trim() === '';
        }
    });
    </script>
    
    <!-- Font Awesome Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>