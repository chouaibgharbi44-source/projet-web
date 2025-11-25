<?php include ROOT . '/views/layout/header.php'; ?>
<?php $title = isset($question) ? "Modifier la question" : "Ajouter une question"; ?>

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
        <label for="title">Titre de la question *</label>
        <input type="text" id="title" name="title" value="<?= isset($_SESSION['form_data']['title']) ? htmlspecialchars($_SESSION['form_data']['title']) : (isset($question) ? htmlspecialchars($question['title']) : '') ?>">
    </div>

    <div class="form-group">
        <label for="description">Description (optionnel)</label>
        <textarea id="description" name="description" rows="4"><?= isset($_SESSION['form_data']['description']) ? htmlspecialchars($_SESSION['form_data']['description']) : (isset($question) ? htmlspecialchars($question['description']) : '') ?></textarea>
    </div>

    <div class="form-group">
        <label for="correct_answer">Bonne réponse *</label>
        <input type="text" id="correct_answer" name="correct_answer" value="<?= isset($_SESSION['form_data']['correct_answer']) ? htmlspecialchars($_SESSION['form_data']['correct_answer']) : (isset($question) ? htmlspecialchars($question['correct_answer']) : '') ?>">
    </div>

    <div class="form-group">
        <label for="option_a">Option A *</label>
        <input type="text" id="option_a" name="option_a" value="<?= isset($_SESSION['form_data']['option_a']) ? htmlspecialchars($_SESSION['form_data']['option_a']) : (isset($question) ? htmlspecialchars($question['option_a']) : '') ?>">
    </div>

    <div class="form-group">
        <label for="option_b">Option B *</label>
        <input type="text" id="option_b" name="option_b" value="<?= isset($_SESSION['form_data']['option_b']) ? htmlspecialchars($_SESSION['form_data']['option_b']) : (isset($question) ? htmlspecialchars($question['option_b']) : '') ?>">
    </div>

    <div class="form-group">
        <label for="option_c">Option C (optionnel)</label>
        <input type="text" id="option_c" name="option_c" value="<?= isset($_SESSION['form_data']['option_c']) ? htmlspecialchars($_SESSION['form_data']['option_c']) : (isset($question) ? htmlspecialchars($question['option_c']) : '') ?>">
    </div>

    <div class="form-group">
        <label for="option_d">Option D (optionnel)</label>
        <input type="text" id="option_d" name="option_d" value="<?= isset($_SESSION['form_data']['option_d']) ? htmlspecialchars($_SESSION['form_data']['option_d']) : (isset($question) ? htmlspecialchars($question['option_d']) : '') ?>">
    </div>

    <div class="form-group">
        <label for="category">Catégorie *</label>
        <select id="category" name="category">
            <option value="">-- Sélectionner --</option>
            <option value="Math" <?= isset($_SESSION['form_data']['category']) && $_SESSION['form_data']['category'] == 'Math' ? 'selected' : (isset($question) && $question['category'] == 'Math' ? 'selected' : '') ?>>Math</option>
            <option value="Informatique" <?= isset($_SESSION['form_data']['category']) && $_SESSION['form_data']['category'] == 'Informatique' ? 'selected' : (isset($question) && $question['category'] == 'Informatique' ? 'selected' : '') ?>>Informatique</option>
            <option value="Langues" <?= isset($_SESSION['form_data']['category']) && $_SESSION['form_data']['category'] == 'Langues' ? 'selected' : (isset($question) && $question['category'] == 'Langues' ? 'selected' : '') ?>>Langues</option>
            <option value="Général" <?= isset($_SESSION['form_data']['category']) && $_SESSION['form_data']['category'] == 'Général' ? 'selected' : (isset($question) && $question['category'] == 'Général' ? 'selected' : '') ?>>Général</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary"><?= isset($question) ? 'Modifier' : 'Ajouter' ?></button>
</form>
<a href="<?= BASE_URL ?>/admin" class="btn btn-secondary">Annuler</a>
</form>
<?php include ROOT . '/views/layout/footer.php'; ?>