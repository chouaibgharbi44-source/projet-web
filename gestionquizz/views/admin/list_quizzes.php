<?php include ROOT . '/views/layout/header.php'; ?>
<?php $title = "Administration - Quiz"; ?>

<h2> Gestion des Quiz</h2>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<a href="<?= BASE_URL ?>/admin/add_quiz.php" class="btn btn-primary">➕ Créer un quiz</a>

<table class="table">
    <thead>
        <tr>
            <th>Titre</th>
            <th>Durée (min)</th>
            <th>Catégorie</th>
            <th>Créé le</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quizzes as $q): ?>
        <tr>
            <td><?= htmlspecialchars($q['title']) ?></td>
            <td><?= (int)$q['duration'] ?></td>
            <td><?= htmlspecialchars($q['category']) ?></td>
            <td><?= $q['created_at'] ?></td>
            <td>
                <a href="<?= BASE_URL ?>/admin/edit_quiz.php?id=<?= $q['id'] ?>" class="btn btn-secondary">✏️ Modifier</a>
                <a href="<?= BASE_URL ?>/admin/delete_quiz.php?id=<?= $q['id'] ?>" onclick="return confirm('Supprimer ?')" class="btn btn-danger">🗑️ Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include ROOT . '/views/layout/footer.php'; ?>