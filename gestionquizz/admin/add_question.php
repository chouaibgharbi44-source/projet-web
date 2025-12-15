<?php
if (!defined('ROOT')) {
    define('ROOT', __DIR__ . '/..');
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/gestionquizz');
}
session_start();
require_once ROOT . '/controllers/AdminQuestionController.php';

$controller = new AdminQuestionController();
$controller->add();
?>