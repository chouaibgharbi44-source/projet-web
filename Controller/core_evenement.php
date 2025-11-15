<?php
/**
 * core_evenement.php - Contrôleur des événements avec PDO
 * Inclus par les vues FrontOffice et BackOffice
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../Model/Event.php';

$db = Database::getInstance();
$eventModel = new Event();

// Normalize redirect targets so relative filenames posted from views
// (ex: "evenemnt.html") resolve to the original page location instead
// of being interpreted relative to this controller (which caused /Controller/... 404s).
function resolve_redirect_target($redirect) {
    $redirect = trim((string)$redirect);
    if ($redirect === '') return '/';
    // Absolute URL or absolute path — leave as-is
    if (preg_match('#^https?://#i', $redirect) || strpos($redirect, '/') === 0) return $redirect;
    // If we have an HTTP_REFERER, resolve relative redirect against it
    if (!empty($_SERVER['HTTP_REFERER'])) {
        $parts = parse_url($_SERVER['HTTP_REFERER']);
        $ref_path = $parts['path'] ?? '';
        $base = rtrim(dirname($ref_path), '/\\');
        if ($base === '.' || $base === '') $base = '';
        return $base . '/' . ltrim($redirect, '/');
    }
    // Fallback: make it absolute at webroot
    return '/' . ltrim($redirect, '/');
}
function ev_list($filter = 'all', $search = '') {
    global $eventModel;
    return $eventModel->getAll($filter, $search);
}

function ev_get($id) {
    global $eventModel;
    return $eventModel->getById($id);
}

function ev_create($data) {
    global $eventModel;
    return $eventModel->create($data);
}

function ev_update($id, $data) {
    global $eventModel;
    $data['id'] = $id;
    return $eventModel->update($data);
}

function ev_delete($id) {
    global $eventModel;
    return $eventModel->delete($id);
}

function ev_join($event_id, $user_id) {
    global $eventModel;
    return $eventModel->join($event_id, $user_id);
}

function ev_leave($event_id, $user_id) {
    global $eventModel;
    return $eventModel->leave($event_id, $user_id);
}

// Wrappers de compatibilité (noms legacy)
function listEvents($filter = 'all', $search = '') { return ev_list($filter, $search); }
function getEvent($id) { return ev_get($id); }
function createEvent($data) { return ev_create($data); }
function updateEvent($id, $data) { return ev_update($id, $data); }
function deleteEvent($id) { return ev_delete($id); }
function joinEvent($event_id, $user_id) { return ev_join($event_id, $user_id); }
function leaveEvent($event_id, $user_id) { return ev_leave($event_id, $user_id); }

// Gestionnaire de requêtes POST (formulaires)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $redirect = $_POST['redirect'] ?? ($_SERVER['HTTP_REFERER'] ?? '/');
    // Resolve relative redirect targets (avoid redirects like /Controller/evenemnt.html)
    $redirect = resolve_redirect_target($redirect);

    try {
        $actionResult = null;
        switch ($action) {
            case 'create':
                $actionResult = ev_create($_POST);
                break;
            case 'update':
                if (isset($_POST['id'])) $actionResult = ev_update($_POST['id'], $_POST);
                break;
            case 'delete':
                if (isset($_POST['id'])) $actionResult = ev_delete($_POST['id']);
                break;
            case 'join':
                if (isset($_POST['event_id']) && isset($_POST['user_id'])) $actionResult = ev_join($_POST['event_id'], $_POST['user_id']);
                break;
            case 'leave':
                if (isset($_POST['event_id']) && isset($_POST['user_id'])) $actionResult = ev_leave($_POST['event_id'], $_POST['user_id']);
                break;
        }

        // Construire la redirection selon le résultat
        $sep = (strpos($redirect, '?') === false) ? '?' : '&';
        if ($actionResult === true || $actionResult === null) {
            // null means no explicit failure and no return value (legacy); treat as success
            header('Location: ' . $redirect . $sep . 'success=1');
        } else {
            // If controller returned an array with success flag, use it
            if (is_array($actionResult) && isset($actionResult['success']) && $actionResult['success'] === true) {
                header('Location: ' . $redirect . $sep . 'success=1');
            } else {
                $errMsg = is_string($actionResult) ? $actionResult : (is_array($actionResult) && isset($actionResult['message']) ? $actionResult['message'] : 'Action échouée.');
                header('Location: ' . $redirect . $sep . 'error=' . urlencode($errMsg));
            }
        }
        exit;
    } catch (Exception $e) {
        header('Location: ' . $redirect . '?error=' . urlencode($e->getMessage()));
        exit;
    }
}

?>
