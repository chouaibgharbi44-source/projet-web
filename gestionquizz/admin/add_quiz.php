<?php
define('ROOT', __DIR__ . '/..');
define('BASE_URL', '/gestionquizz');
session_start();
require_once ROOT . '/controllers/AdminQuizController.php';

$controller = new AdminQuizController();
$controller->add();
?>