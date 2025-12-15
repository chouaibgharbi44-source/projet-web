<?php include ROOT . '/views/layout/header.php'; ?>
<?php $title = "Résultat du Quiz"; ?>

<h2>📊 Résultat du Quiz</h2>
<div class="card">
    <h3>Score final : <span style="color: <?= $score >= $total/2 ? 'green' : 'red' ?>"><?= $score ?>/<?= $total ?></span></h3>
    <p><?= round(($score / $total) * 100) ?>% de bonnes réponses</p>
</div>

<br>
<h3>Détail des réponses :</h3>

<?php foreach ($results as $r): ?>
<div class="card" style="border-left: 4px solid <?= $r['is_correct'] ? 'green' : 'red' ?>;">
    <h4><?= htmlspecialchars($r['question']['title']) ?></h4>
    <p><strong>Votre réponse :</strong> 
        <?= $r['user_answer'] ? $r['user_answer'] : '<em>Non répondue</em>' ?>
    </p>
    <p><strong>Bonne réponse :</strong> <?= $r['correct_letter'] ?></p>
</div>
<?php endforeach; ?>

<a href="<?= BASE_URL ?>" class="btn btn-secondary">← Retour à la liste</a>
<!-- Télécharger le certificat -->
<div style="margin-top: 2rem; text-align: center;">
    <a href="<?= BASE_URL ?>/certificate.php?quiz_id=<?= $quiz['id'] ?>&score=<?= $score ?>&total=<?= $total ?>" 
       class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 1.1rem;">
        📄 Télécharger mon certificat
    </a>
</div>

<!-- Son de feedback -->
<audio id="sound-correct" src="<?= BASE_URL ?>/assets/sounds/correct.mp3"></audio>
<audio id="sound-incorrect" src="<?= BASE_URL ?>/assets/sounds/incorrect.mp3"></audio>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sound = document.getElementById(<?= $score >= $total/2 ? "'sound-correct'" : "'sound-incorrect'" ?>);
    sound.play().catch(e => console.log("Audio play blocked by browser"));
});
</script>

<?php include ROOT . '/views/layout/footer.php'; ?>