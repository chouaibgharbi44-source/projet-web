<?php
require_once ROOT . '/config/database.php';
require_once ROOT . '/models/Quiz.php';
require_once ROOT . '/models/Question.php';

class QuizResultController {
    public function show() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL);
            exit;
        }

        $quiz_id = $_POST['quiz_id'] ?? null;
        $userAnswers = $_POST['answers'] ?? [];

        if (!$quiz_id) {
            die("Quiz non spécifié.");
        }

        $quizModel = new Quiz($GLOBALS['pdo']);
        $questionModel = new Question($GLOBALS['pdo']);

        $quiz = $quizModel->getById($quiz_id);
        $questions = $questionModel->getByQuizId($quiz_id);

        $score = 0;
        $total = count($questions);
        $results = [];

        foreach ($questions as $q) {
            $userAnswer = $userAnswers[$q['id']] ?? null;
            $correctAnswer = $q['correct_answer'];

            // Déterminer la lettre correspondant à la bonne réponse
            $correctLetter = null;
            if ($q['option_a'] === $correctAnswer) $correctLetter = 'A';
            elseif ($q['option_b'] === $correctAnswer) $correctLetter = 'B';
            elseif ($q['option_c'] === $correctAnswer) $correctLetter = 'C';
            elseif ($q['option_d'] === $correctAnswer) $correctLetter = 'D';

            $isCorrect = ($userAnswer === $correctLetter);
            if ($isCorrect) $score++;

            $results[] = [
                'question' => $q,
                'user_answer' => $userAnswer,
                'correct_letter' => $correctLetter,
                'is_correct' => $isCorrect
            ];
        }

        require_once ROOT . '/views/front/quiz_result.php';
    }
}
?>