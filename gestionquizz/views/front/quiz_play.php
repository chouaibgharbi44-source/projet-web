<?php include ROOT . '/views/layout/header.php'; ?>
<?php $title = "Jouer - " . htmlspecialchars($quiz['title']); ?>

<h2>🎯 <?= htmlspecialchars($quiz['title']) ?></h2>
<p><?= htmlspecialchars($quiz['description']) ?></p>
<p><strong>Durée :</strong> <?= $quiz['duration'] ?> min</p>

<?php if (!empty($questions)): ?>
    <div class="card">
        <?php foreach ($questions as $q): ?>
        <div class="question">
            <h3><?= htmlspecialchars($q['title']) ?></h3>
            <div class="options">
                <p>A) <?= htmlspecialchars($q['option_a']) ?></p>
                <p>B) <?= htmlspecialchars($q['option_b']) ?></p>
                <?php if (!empty($q['option_c'])): ?>
                    <p>C) <?= htmlspecialchars($q['option_c']) ?></p>
                <?php endif; ?>
                <?php if (!empty($q['option_d'])): ?>
                    <p>D) <?= htmlspecialchars($q['option_d']) ?></p>
                <?php endif; ?>
            </div>
            <!-- Plus tard : formulaire de réponse -->
        </div>
        <hr>
        <?php endforeach; ?>
    </div>

    <a href="<?= BASE_URL ?>" class="btn btn-secondary">← Retour à la liste</a>
<?php else: ?>
    <div class="alert alert-warning">Ce quiz n'a pas encore de questions.</div>
    <a href="<?= BASE_URL ?>" class="btn btn-secondary">← Retour</a>
<?php endif; ?>

<?php include ROOT . '/views/layout/footer.php'; ?>