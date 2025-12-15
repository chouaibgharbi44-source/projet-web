<?php
define('ROOT', __DIR__);
define('BASE_URL', '/gestionquizz');
session_start();

// Charger les quiz
require_once ROOT . '/config/database.php';
require_once ROOT . '/models/Quiz.php';

$quizModel = new Quiz($GLOBALS['pdo']);
$quizzes = $quizModel->getAll();

// Inclure la vue
require_once ROOT . '/views/front/quiz_list.php';
?>