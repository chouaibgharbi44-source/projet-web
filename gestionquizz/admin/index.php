<?php
define('ROOT', __DIR__ . '/..');
define('BASE_URL', '/gestionquizz');
session_start();
?>

<?php include ROOT . '/views/layout/header.php'; ?>

<h2> Administration – Campus Connect</h2>

<div class="card">
    <h3> Gestion des Questions</h3>
    <p>Créez, modifiez ou supprimez des questions interactives.</p>
    <a href="<?= BASE_URL ?>/admin/list_questions.php" class="btn btn-primary">Voir les questions</a>
</div>

<div class="card">
    <h3> Gestion des Quiz</h3>
    <p>Organisez des quiz avec durée et catégorie.</p>
    <a href="<?= BASE_URL ?>/admin/list_quizzes.php" class="btn btn-primary">Gérer les quiz</a>
</div>

<?php include ROOT . '/views/layout/footer.php'; ?>