<?php include ROOT . '/views/layout/header.php'; ?>
<?php $title = "Jouer - " . htmlspecialchars($quiz['title']); ?>

<h2>🎯 <?= htmlspecialchars($quiz['title']) ?></h2>
<p><?= htmlspecialchars($quiz['description']) ?></p>
<p><strong>Durée :</strong> <?= $quiz['duration'] ?> min</p>

<?php if (!empty($questions)): ?>
    <!-- Timer -->
    <div id="timer-box" style="background: var(--primary-purple); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center;">
        <h3 style="color: var(--accent-pink); margin: 0;">
            ⏳ Temps restant : <span id="time-left"><?= $quiz['duration'] * 60 ?></span> secondes
        </h3>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/quiz_result.php">
        <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">

        <?php foreach ($questions as $q): ?>
        <div class="card">
            <h3><?= htmlspecialchars($q['title']) ?></h3>
            <div class="options">
                <?php if (!empty($q['option_a'])): ?>
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="A" required> A) <?= htmlspecialchars($q['option_a']) ?></label><br>
                <?php endif; ?>
                <?php if (!empty($q['option_b'])): ?>
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="B" required> B) <?= htmlspecialchars($q['option_b']) ?></label><br>
                <?php endif; ?>
                <?php if (!empty($q['option_c'])): ?>
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="C"> C) <?= htmlspecialchars($q['option_c']) ?></label><br>
                <?php endif; ?>
                <?php if (!empty($q['option_d'])): ?>
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="D"> D) <?= htmlspecialchars($q['option_d']) ?></label><br>
                <?php endif; ?>
            </div>
            <hr>
        </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Voir mon score</button>
    </form>

    <a href="<?= BASE_URL ?>" class="btn btn-secondary">← Retour</a>

    <!-- Timer script -->
    <script>
    let timeLeft = <?= $quiz['duration'] * 60 ?>;
    const timeDisplay = document.getElementById('time-left');
    const countdown = setInterval(() => {
        timeLeft--;
        timeDisplay.textContent = timeLeft;
        if (timeLeft <= 10) timeDisplay.style.color = 'red';
        if (timeLeft <= 0) {
            clearInterval(countdown);
            alert("⏰ Temps écoulé !");
            document.querySelector('form').submit();
        }
    }, 1000);
    </script>
<?php else: ?>
    <div class="alert alert-warning">Ce quiz n'a pas encore de questions.</div>
    <a href="<?= BASE_URL ?>" class="btn btn-secondary">← Retour</a>
<?php endif; ?>

<?php include ROOT . '/views/layout/footer.php'; ?>