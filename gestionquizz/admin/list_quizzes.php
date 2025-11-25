<?php
// Define ROOT and BASE_URL
define('ROOT', __DIR__ . '/..');
define('BASE_URL', '/gestionquizz');
session_start();

// Load the controller
require_once ROOT . '/controllers/AdminQuizController.php';

// Run the index action
$controller = new AdminQuizController();
$controller->index();
?>