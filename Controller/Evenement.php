<?php
/**
 * Controller: Evenement.php - Contrôleur principal avec PDO
 * Gère le CRUD complet des événements
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../Model/Event.php';

// Initialiser la base de données
$db = Database::getInstance();
$eventModel = new Event();

/**
 * Fonctions helper pour la compatibilité avec les vues existantes
 */

function listEvents($filter = 'all', $search = '') {
    global $eventModel;
    return $eventModel->getAll($filter, $search);
}

function getEvent($id) {
    global $eventModel;
    return $eventModel->getById($id);
}

function createEvent($data) {
    global $eventModel;
    return $eventModel->create($data);
}

function updateEvent($id, $data) {
    global $eventModel;
    $data['id'] = $id;
    return $eventModel->update($data);
}

function deleteEvent($id) {
    global $eventModel;
    return $eventModel->delete($id);
}

function joinEvent($event_id, $user_id) {
    global $eventModel;
    return $eventModel->join($event_id, $user_id);
}

function leaveEvent($event_id, $user_id) {
    global $eventModel;
    return $eventModel->leave($event_id, $user_id);
}

function getParticipants($event_id) {
    global $eventModel;
    return $eventModel->getParticipants($event_id);
}

/**
 * Gestion des requêtes POST (formulaires)
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $redirect = $_POST['redirect'] ?? ($_SERVER['HTTP_REFERER'] ?? '/BasmaEvent/FrontOffice/evenemnt.php');

    try {
        switch ($action) {
            case 'create':
                createEvent($_POST);
                break;
            
            case 'update':
                if (isset($_POST['id'])) {
                    updateEvent($_POST['id'], $_POST);
                }
                break;
            
            case 'delete':
                if (isset($_POST['id'])) {
                    deleteEvent($_POST['id']);
                }
                break;
            
            case 'join':
                if (isset($_POST['event_id']) && isset($_POST['user_id'])) {
                    joinEvent($_POST['event_id'], $_POST['user_id']);
                }
                break;
            
            case 'leave':
                if (isset($_POST['event_id']) && isset($_POST['user_id'])) {
                    leaveEvent($_POST['event_id'], $_POST['user_id']);
                }
                break;
        }
        
        // Redirection après succès
        header('Location: ' . $redirect);
        exit;
    } catch (Exception $e) {
        // En cas d'erreur, rediriger avec message
        header('Location: ' . $redirect . '?error=' . urlencode($e->getMessage()));
        exit;
    }
}

/**
 * Gestion des requêtes GET (API simple - JSON)
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    header('Content-Type: application/json; charset=utf-8');

    try {
        switch ($action) {
            case 'list':
                $filter = $_GET['filter'] ?? 'all';
                $search = $_GET['search'] ?? '';
                $events = listEvents($filter, $search);
                echo json_encode(['success' => true, 'data' => $events]);
                break;
            
            case 'detail':
                if (isset($_GET['id'])) {
                    $event = getEvent($_GET['id']);
                    if ($event) {
                        echo json_encode(['success' => true, 'data' => $event]);
                    } else {
                        http_response_code(404);
                        echo json_encode(['success' => false, 'message' => 'Événement non trouvé']);
                    }
                }
                break;
            
            case 'participants':
                if (isset($_GET['event_id'])) {
                    $participants = getParticipants($_GET['event_id']);
                    echo json_encode(['success' => true, 'data' => $participants]);
                }
                break;
            
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

?>
