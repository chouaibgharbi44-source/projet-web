<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/models/Question.php';

class AdminQuestionController {
    private $questionModel;

    public function __construct() {
        $this->questionModel = new Question($GLOBALS['pdo']);
    }

    public function index() {
        $questions = $this->questionModel->getAll();
        require_once ROOT . '/views/admin/list_questions.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateForm($_POST);

            if (empty($errors)) {
                $success = $this->questionModel->create($_POST);
                if ($success) {
                    $_SESSION['message'] = "Question ajoutée avec succès !";
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
        require_once ROOT . '/views/admin/form_question.php';
    }

    public function edit($id) {
        $question = $this->questionModel->getById($id);
        if (!$question) {
            $_SESSION['error'] = "Question introuvable.";
            header('Location: index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateForm($_POST);

            if (empty($errors)) {
                $success = $this->questionModel->update($id, $_POST);
                if ($success) {
                    $_SESSION['message'] = "Question mise à jour !";
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

        require_once '../../views/admin/form_question.php';
    }

    public function delete($id) {
        $success = $this->questionModel->delete($id);
        if ($success) {
            $_SESSION['message'] = "Question supprimée.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
        header('Location: index.php');
        exit;
    }

    private function validateForm($data) {
        $errors = [];

        if (empty(trim($data['title']))) {
            $errors['title'] = "Le titre est requis.";
        } elseif (strlen($data['title']) < 3) {
            $errors['title'] = "Le titre doit contenir au moins 3 caractères.";
        }

        if (empty(trim($data['correct_answer']))) {
            $errors['correct_answer'] = "La bonne réponse est requise.";
        }

        if (empty(trim($data['option_a']))) {
            $errors['option_a'] = "L'option A est requise.";
        }

        if (empty(trim($data['option_b']))) {
            $errors['option_b'] = "L'option B est requise.";
        }

        if (empty($data['category'])) {
            $errors['category'] = "La catégorie est requise.";
        }

        return $errors;
    }
}