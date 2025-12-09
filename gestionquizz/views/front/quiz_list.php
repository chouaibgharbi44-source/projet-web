<?php include ROOT . '/views/layout/header.php'; ?>
<?php $title = "Nos Quiz"; ?>

<h2>📚 Nos Quiz Disponibles</h2>

<?php if (!empty($quizzes)): ?>
    <?php foreach ($quizzes as $quiz): ?>
        <div class="card">
            <h3><?= htmlspecialchars($quiz['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($quiz['description'])) ?></p>
            <p><strong>Durée :</strong> <?= (int)$quiz['duration'] ?> minutes</p>
            <p><strong>Catégorie :</strong> <?= htmlspecialchars($quiz['category']) ?></p>
            <a href="<?= BASE_URL ?>/play.php?id=<?= $quiz['id'] ?>" class="btn btn-primary">Jouer</a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info">Aucun quiz disponible pour le moment.</div>
<?php endif; ?>

<?php include ROOT . '/views/layout/footer.php'; ?>