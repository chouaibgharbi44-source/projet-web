<?php
define('ROOT', __DIR__ . '/..');
define('BASE_URL', '/gestionquizz');
session_start();
require_once ROOT . '/controllers/AdminQuizController.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['error'] = "ID manquant.";
    header('Location: index.php');
    exit;
}

$controller = new AdminQuizController();
$controller->delete($id);
?>