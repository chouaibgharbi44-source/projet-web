<?php
define('ROOT', __DIR__);
define('BASE_URL', '/gestionquizz');
session_start();

require_once ROOT . '/controllers/QuizResultController.php';

$controller = new QuizResultController();
$controller->show();
?>