<?php include ROOT . '/views/layout/header.php'; ?>
<?php $title = isset($quiz) ? "Modifier le quiz" : "Créer un quiz"; ?>

<h2><?= $title ?></h2>

<?php if (isset($_SESSION['errors'])): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form method="POST" action="">
    <div class="form-group">
        <label for="title">Titre du quiz *</label>
        <input type="text" id="title" name="title" value="<?= isset($_SESSION['form_data']['title']) ? htmlspecialchars($_SESSION['form_data']['title']) : (isset($quiz) ? htmlspecialchars($quiz['title']) : '') ?>">
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4"><?= isset($_SESSION['form_data']['description']) ? htmlspecialchars($_SESSION['form_data']['description']) : (isset($quiz) ? htmlspecialchars($quiz['description']) : '') ?></textarea>
    </div>

    <div class="form-group">
        <label for="duration">Durée (en minutes) *</label>
        <input type="number" id="duration" name="duration" value="<?= isset($_SESSION['form_data']['duration']) ? (int)$_SESSION['form_data']['duration'] : (isset($quiz) ? (int)$quiz['duration'] : '10') ?>">
    </div>

    <div class="form-group">
        <label for="category">Catégorie *</label>
        <select id="category" name="category">
            <option value="">-- Sélectionner --</option>
            <option value="Math" <?= isset($_SESSION['form_data']['category']) && $_SESSION['form_data']['category'] == 'Math' ? 'selected' : (isset($quiz) && $quiz['category'] == 'Math' ? 'selected' : '') ?>>Math</option>
            <option value="Informatique" <?= isset($_SESSION['form_data']['category']) && $_SESSION['form_data']['category'] == 'Informatique' ? 'selected' : (isset($quiz) && $quiz['category'] == 'Informatique' ? 'selected' : '') ?>>Informatique</option>
            <option value="Langues" <?= isset($_SESSION['form_data']['category']) && $_SESSION['form_data']['category'] == 'Langues' ? 'selected' : (isset($quiz) && $quiz['category'] == 'Langues' ? 'selected' : '') ?>>Langues</option>
            <option value="Général" <?= isset($_SESSION['form_data']['category']) && $_SESSION['form_data']['category'] == 'Général' ? 'selected' : (isset($quiz) && $quiz['category'] == 'Général' ? 'selected' : '') ?>>Général</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary"><?= isset($quiz) ? 'Modifier' : 'Créer' ?></button>
    <a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Annuler</a>
</form>

<script src="<?= BASE_URL ?>/assets/js/validation.js"></script>
<?php include ROOT . '/views/layout/footer.php'; ?>