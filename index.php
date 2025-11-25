<?php
require_once __DIR__ . '/Control/matiereController.php';
require_once __DIR__ . '/Control/ressourceController.php';

// Déterminer l'entité et le contrôleur à utiliser
$entity = isset($_GET['entity']) ? $_GET['entity'] : 'matiere';

if ($entity === 'ressource') {
    $controller = new RessourceController();
} else {
    $controller = new MatiereController();
}

$controller->handleRequest();
