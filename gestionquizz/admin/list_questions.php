<?php
define('ROOT', __DIR__ . '/..');
define('BASE_URL', '/gestionquizz');
session_start();
require_once ROOT . '/controllers/AdminQuestionController.php';

$controller = new AdminQuestionController();
$controller->index();
?>