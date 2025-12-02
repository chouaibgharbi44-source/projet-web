<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Modifier Ressource</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=3" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<div class="topbar admin">
    <h1>Modifier une ressource</h1>
    <div class="admin-button"><a href="index.php?entity=ressource&amp;area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form method="post" action="index.php?entity=ressource&amp;action=update&amp;area=admin" onsubmit="return validateRessourceForm(this);">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($ressource['id']); ?>" />
            <label>Matière associée:<br />
                <select name="matiere_id" required>
                    <option value="">Sélectionnez une matière</option>
                    <?php if (!empty($matieres)) foreach ($matieres as $m): ?>
                        <option value="<?php echo (int)$m['id']; ?>" <?php echo (isset($ressource['matiere_id']) && $ressource['matiere_id'] == $m['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['nom_matiere']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label><br />
            <label>Titre:<br /><input type="text" name="titre" value="<?php echo htmlspecialchars($ressource['titre']); ?>" required /></label><br />
            <label>Description:<br /><textarea name="description" rows="6" cols="50" required><?php echo htmlspecialchars($ressource['description']); ?></textarea></label><br />
            <label>Type de ressource:<br /><input type="text" name="type_ressource" value="<?php echo htmlspecialchars($ressource['type_ressource']); ?>" /></label><br />
            <label>URL:<br /><input type="text" name="url" value="<?php echo htmlspecialchars($ressource['url']); ?>" /></label><br />
            <label>Auteur:<br /><input type="text" name="auteur" value="<?php echo htmlspecialchars($ressource['auteur']); ?>" /></label><br />
            <label>Date d'ajout:<br /><input type="text" name="date_ajout" value="<?php echo htmlspecialchars($ressource['date_ajout']); ?>" /></label><br />
            <input class="btn" type="submit" value="Mettre à jour" />
        </form>
    </div>
</div>

<script type="text/javascript">
function validateRessourceForm(form) {
    const id = form.id.value.trim();
    const titre = form.titre.value.trim();
    const description = form.description.value.trim();

    let errors = [];

    // Validation : id requis pour la mise à jour
    if (!id) {
        errors.push("L'identifiant de la ressource est manquant.");
    }

    // Validation : titre doit avoir au moins 1 minuscule ET 1 majuscule
    if (!/[a-z]/.test(titre) || !/[A-Z]/.test(titre)) {
        errors.push("Le titre doit contenir au moins une lettre minuscule et une lettre majuscule.");
    }

    // Validation : description > 20 caractères
    if (description.length <= 20) {
        errors.push("La description doit contenir plus de 20 caractères.");
    }

    if (errors.length > 0) {
        alert("Erreurs de validation :\n\n• " + errors.join("\n• "));
        return false;
    }

    return true;
}
</script>

</body>
</html>