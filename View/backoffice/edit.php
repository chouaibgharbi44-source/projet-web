<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Modifier Matière</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <script type="text/javascript" src="View/assets/validation.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Modifier une matière</h1>
    <div class="admin-button"><a href="index.php?area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form method="post" action="index.php?action=update&area=admin" onsubmit="return validateForm(this);">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($matiere['id']); ?>" />
            <label>Nom matière:<br /><input type="text" name="nom_matiere" value="<?php echo htmlspecialchars($matiere['nom_matiere']); ?>" /></label><br />
            <label>Titre:<br /><input type="text" name="titre" value="<?php echo htmlspecialchars($matiere['titre']); ?>" /></label><br />
            <label>Description:<br /><textarea name="description" rows="6" cols="50"><?php echo htmlspecialchars($matiere['description']); ?></textarea></label><br />
            <label>Date d'ajout:<br /><input type="text" name="date_ajout" value="<?php echo htmlspecialchars($matiere['date_ajout']); ?>" /></label><br />
            <label>Niveau difficulté:<br /><input type="text" name="niveau_difficulte" value="<?php echo htmlspecialchars($matiere['niveau_difficulte']); ?>" /></label><br />
            <input class="btn" type="submit" value="Mettre à jour" />
        </form>
    </div>
</div>

</body>
</html>
