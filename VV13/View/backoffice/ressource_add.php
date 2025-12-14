<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Ajouter Ressource</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .error {
            color: #d32f2f;
            font-size: 0.85em;
            margin-top: 4px;
            display: block;
        }
    </style>
</head>
<body>
<div class="topbar admin">
    <h1>Ajouter une ressource</h1>
    <div class="admin-button"><a href="index.php?entity=ressource&area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form id="ressourceForm" method="post" action="index.php?entity=ressource&action=store&area=admin">
            <label>Matière associée:<br />
                <select name="matiere_id" id="matiere_id">
                    <option value="">Sélectionnez une matière</option>
                    <?php if (!empty($matieres)) foreach ($matieres as $m): ?>
                        <option value="<?php echo (int)$m['id']; ?>"><?php echo htmlspecialchars($m['nom_matiere']); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="error" id="error-matiere_id"></span>
            </label><br /><br />

            <label>Titre:<br />
                <input type="text" name="titre" id="titre" />
                <span class="error" id="error-titre"></span>
            </label><br /><br />

            <label>Description:<br />
                <textarea name="description" id="description" rows="6" cols="50"></textarea>
                <span class="error" id="error-description"></span>
            </label><br /><br />

            <label>Type de ressource:<br />
                <input type="text" name="type_ressource" placeholder="Ex: PDF, Tutoriel, Document" />
            </label><br /><br />

            <label>URL:<br />
                <input type="text" name="url" id="url" placeholder="https://..." />
                <span class="error" id="error-url"></span>
            </label><br /><br />

            <label>Auteur:<br />
                <input type="text" name="auteur" />
            </label><br /><br />

            <label>Date d'ajout:<br />
                <input type="text" name="date_ajout" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly />
            </label><br /><br />

            <input class="btn" type="submit" value="Enregistrer" />
        </form>
    </div>
</div>

<script>
document.getElementById('ressourceForm').addEventListener('submit', function(e) {
    let isValid = true;

    // 1. Matière associée (obligatoire)
    const matiereId = document.getElementById('matiere_id').value;
    const matiereError = document.getElementById('error-matiere_id');
    if (matiereId === '') {
        matiereError.textContent = 'Veuillez sélectionner une matière.';
        isValid = false;
    } else {
        matiereError.textContent = '';
    }

    // 2. Titre : au moins une majuscule ET une minuscule
    const titre = document.getElementById('titre').value.trim();
    const titreError = document.getElementById('error-titre');
    if (titre === '') {
        titreError.textContent = 'Le titre est obligatoire.';
        isValid = false;
    } else {
        const hasUpper = /[A-Z]/.test(titre);
        const hasLower = /[a-z]/.test(titre);
        if (!hasUpper || !hasLower) {
            titreError.textContent = 'Le titre doit contenir au moins une majuscule et une minuscule.';
            isValid = false;
        } else {
            titreError.textContent = '';
        }
    }

    // 3. Description : au moins 20 caractères
    const description = document.getElementById('description').value;
    const descError = document.getElementById('error-description');
    if (description.length < 20) {
        descError.textContent = 'La description doit contenir au moins 20 caractères.';
        isValid = false;
    } else {
        descError.textContent = '';
    }

    // 4. URL (si fournie, doit être valide)
    const url = document.getElementById('url').value.trim();
    const urlError = document.getElementById('error-url');
    if (url !== '' && !/^https?:\/\/.+/i.test(url)) {
        urlError.textContent = 'Veuillez entrer une URL valide (commençant par http:// ou https://).';
        isValid = false;
    } else {
        urlError.textContent = '';
    }

    if (!isValid) {
        e.preventDefault();
    }
});
</script>
</body>
</html>