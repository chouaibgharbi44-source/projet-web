<?php
define('ROOT', __DIR__);
define('BASE_URL', '/gestionquizz');
session_start();

// Vérifier l'ID du quiz
$quiz_id = $_GET['id'] ?? null;
if (!$quiz_id || !is_numeric($quiz_id)) {
    die("Quiz non spécifié.");
}

// Charger le quiz et ses questions
require_once ROOT . '/config/database.php';
require_once ROOT . '/models/Quiz.php';
require_once ROOT . '/models/Question.php';

$quizModel = new Quiz($GLOBALS['pdo']);
$questionModel = new Question($GLOBALS['pdo']);

$quiz = $quizModel->getById($quiz_id);
$questions = $questionModel->getByQuizId($quiz_id);

if (!$quiz) {
    die("Quiz introuvable.");
}

// Inclure la vue
require_once ROOT . '/views/front/quiz_play.php';
?>