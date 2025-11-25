<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/models/Question.php';
// ... reste du code

class QuestionController {
    private $questionModel;

    public function __construct() {
        $this->questionModel = new Question($GLOBALS['pdo']);
    }

    public function index() {
        $questions = $this->questionModel->getAll();
        require_once ROOT . '/views/front/quiz_list.php';
    }
}
?>