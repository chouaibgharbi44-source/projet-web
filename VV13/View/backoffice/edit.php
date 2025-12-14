<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Modifier Matière</title>
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
    <h1>Modifier une matière</h1>
    <div class="admin-button"><a href="index.php?area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form id="matiereForm" method="post" action="index.php?action=update&area=admin">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($matiere['id']); ?>" />

            <label>Nom matière:<br />
                <input type="text" name="nom_matiere" id="nom_matiere" 
                       value="<?php echo htmlspecialchars($matiere['nom_matiere']); ?>" />
                <span class="error" id="error-nom_matiere"></span>
            </label><br /><br />

            <label>Titre:<br />
                <input type="text" name="titre" id="titre" 
                       value="<?php echo htmlspecialchars($matiere['titre']); ?>" />
                <span class="error" id="error-titre"></span>
            </label><br /><br />

            <label>Description:<br />
                <textarea name="description" id="description" rows="6" cols="50"><?php echo htmlspecialchars($matiere['description']); ?></textarea>
                <span class="error" id="error-description"></span>
            </label><br /><br />

            <label>Date d'ajout:<br />
                <input type="text" name="date_ajout" 
                       value="<?php echo htmlspecialchars($matiere['date_ajout']); ?>" readonly />
            </label><br /><br />

            <label>Niveau difficulté:<br />
                <input type="text" name="niveau_difficulte" 
                       value="<?php echo htmlspecialchars($matiere['niveau_difficulte']); ?>" />
            </label><br /><br />

            <input class="btn" type="submit" value="Mettre à jour" />
        </form>
    </div>
</div>

<script>
document.getElementById('matiereForm').addEventListener('submit', function(e) {
    let isValid = true;

    // 1. Nom matière (obligatoire)
    const nomMatiere = document.getElementById('nom_matiere').value.trim();
    const nomMatiereError = document.getElementById('error-nom_matiere');
    if (nomMatiere === '') {
        nomMatiereError.textContent = 'Le nom de la matière est obligatoire.';
        isValid = false;
    } else {
        nomMatiereError.textContent = '';
    }

    // 2. Titre : au moins une majuscule ET une minuscule
    const titre = document.getElementById('titre').value;
    const titreError = document.getElementById('error-titre');
    const hasUpper = /[A-Z]/.test(titre);
    const hasLower = /[a-z]/.test(titre);
    if (!hasUpper || !hasLower) {
        titreError.textContent = 'Le titre doit contenir au moins une majuscule et une minuscule.';
        isValid = false;
    } else {
        titreError.textContent = '';
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

    if (!isValid) {
        e.preventDefault(); // Bloque l'envoi si validation échoue
    }
});
</script>
</body>
</html>
