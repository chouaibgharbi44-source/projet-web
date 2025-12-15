<?php
if (!defined('ROOT')) {
    define('ROOT', __DIR__ . '/..');
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/gestionquizz');
}
session_start();
require_once ROOT . '/controllers/AdminQuestionController.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['error'] = "ID manquant.";
    header('Location: index.php');
    exit;
}

$controller = new AdminQuestionController();
$controller->delete($id);
?>