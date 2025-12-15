<?php
class Question {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM questions ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $quiz_id = $data['quiz_id'] ?? null;

        $stmt = $this->pdo->prepare("
            INSERT INTO questions (
                title,
                description,
                correct_answer,
                option_a,
                option_b,
                option_c,
                option_d,
                category,
                quiz_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['correct_answer'],
            $data['option_a'],
            $data['option_b'],
            $data['option_c'] ?? null,
            $data['option_d'] ?? null,
            $data['category'],
            $quiz_id
        ]);
    }

    public function update($id, $data) {
        $quiz_id = $data['quiz_id'] ?? null;

        $stmt = $this->pdo->prepare("
            UPDATE questions SET
                title = ?,
                description = ?,
                correct_answer = ?,
                option_a = ?,
                option_b = ?,
                option_c = ?,
                option_d = ?,
                category = ?
                quiz_id = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['correct_answer'],
            $data['option_a'],
            $data['option_b'],
            $data['option_c'] ?? null,
            $data['option_d'] ?? null,
            $data['category'],
            $quiz_id,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM questions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByQuizId($quiz_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM questions WHERE quiz_id = ? ORDER BY created_at");
        $stmt->execute([$quiz_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countByQuizId($quiz_id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM questions WHERE quiz_id = ?");
        $stmt->execute([$quiz_id]);
        return $stmt->fetchColumn();
    }    

}
?>