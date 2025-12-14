
<?php
session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/Control/matiereController.php';
require_once __DIR__ . '/Control/ressourceController.php';

// Simple admin logout
if (isset($_GET['admin_logout'])) {
    unset($_SESSION['is_admin']);
    header('Location: index.php');
    exit;
}

$requestedArea = isset($_REQUEST['area']) ? $_REQUEST['area'] : 'front';

// ✅ ACCÈS LIBRE AU BACKOFFICE — suppression de la protection
if ($requestedArea === 'admin') {
    $_SESSION['is_admin'] = true; // Force la connexion, sans mot de passe
}

// Déterminer l'entité et le contrôleur à utiliser
$entity = isset($_GET['entity']) ? $_GET['entity'] : 'matiere';

if ($entity === 'ressource') {
    $controller = new RessourceController();
} else {
    $controller = new MatiereController();
}

$controller->handleRequest();