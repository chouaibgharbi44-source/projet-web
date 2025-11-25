<?php include ROOT . '/views/layout/header.php'; ?>
<?php $title = "Liste des Quiz"; ?>

<h2> Liste des Questions</h2>

<?php if (!empty($questions)): ?>
    <?php foreach ($questions as $q): ?>
        <div class="card">
            <h3><?= htmlspecialchars($q['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($q['description'])) ?></p>
            <p><strong>Catégorie :</strong> <?= htmlspecialchars($q['category']) ?></p>
            <p><strong>Bonne réponse :</strong> <?= htmlspecialchars($q['correct_answer']) ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info">Aucune question disponible pour le moment.</div>
<?php endif; ?>
<?php include ROOT . '/views/layout/footer.php'; ?>