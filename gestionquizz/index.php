<?php
define('ROOT', __DIR__);
define('BASE_URL', '/gestionquizz');
session_start();
require_once 'controllers/QuestionController.php';

$controller = new QuestionController();
$controller->index();
?>