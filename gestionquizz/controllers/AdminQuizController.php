<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/models/Quiz.php';

class AdminQuizController {
    private $quizModel;

    public function __construct() {
        $this->quizModel = new Quiz($GLOBALS['pdo']);
    }

    public function index() {
        $quizzes = $this->quizModel->getAll();
        require_once ROOT . '/views/admin/list_quizzes.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateForm($_POST);
            if (empty($errors)) {
                if ($this->quizModel->create($_POST)) {
                    $_SESSION['message'] = "Quiz ajouté avec succès !";
                    header('Location: index.php');
                    exit;
                } else {
                    $_SESSION['error'] = "Erreur lors de l'ajout.";
                }
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['form_data'] = $_POST;
            }
        }
        require_once ROOT . '/views/admin/form_quiz.php';
    }

    public function edit($id) {
        $quiz = $this->quizModel->getById($id);
        if (!$quiz) {
            $_SESSION['error'] = "Quiz introuvable.";
            header('Location: index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateForm($_POST);
            if (empty($errors)) {
                if ($this->quizModel->update($id, $_POST)) {
                    $_SESSION['message'] = "Quiz mis à jour !";
                    header('Location: index.php');
                    exit;
                } else {
                    $_SESSION['error'] = "Erreur lors de la mise à jour.";
                }
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['form_data'] = $_POST;
            }
        }
        require_once ROOT . '/views/admin/form_quiz.php';
    }

    public function delete($id) {
        $this->quizModel->delete($id);
        $_SESSION['message'] = "Quiz supprimé.";
        header('Location: index.php');
        exit;
    }

    private function validateForm($data) {
        $errors = [];
        if (empty(trim($data['title']))) {
            $errors['title'] = "Le titre est requis.";
        }
        if (empty(trim($data['duration'])) || !is_numeric($data['duration']) || $data['duration'] <= 0) {
            $errors['duration'] = "La durée doit être un nombre positif.";
        }
        if (empty($data['category'])) {
            $errors['category'] = "La catégorie est requise.";
        }
        return $errors;
    }
}
?>