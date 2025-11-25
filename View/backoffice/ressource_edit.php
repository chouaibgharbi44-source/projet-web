<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Modifier Ressource</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <script type="text/javascript" src="View/assets/validation.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Modifier une ressource</h1>
    <div class="admin-button"><a href="index.php?entity=ressource&area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form method="post" action="index.php?entity=ressource&action=update&area=admin" onsubmit="return validateRessourceForm(this);">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($ressource['id']); ?>" />
            <label>Titre:<br /><input type="text" name="titre" value="<?php echo htmlspecialchars($ressource['titre']); ?>" /></label><br />
            <label>Description:<br /><textarea name="description" rows="6" cols="50"><?php echo htmlspecialchars($ressource['description']); ?></textarea></label><br />
            <label>Type de ressource:<br /><input type="text" name="type_ressource" value="<?php echo htmlspecialchars($ressource['type_ressource']); ?>" /></label><br />
            <label>URL:<br /><input type="text" name="url" value="<?php echo htmlspecialchars($ressource['url']); ?>" /></label><br />
            <label>Auteur:<br /><input type="text" name="auteur" value="<?php echo htmlspecialchars($ressource['auteur']); ?>" /></label><br />
            <label>Date d'ajout:<br /><input type="text" name="date_ajout" value="<?php echo htmlspecialchars($ressource['date_ajout']); ?>" /></label><br />
            <input class="btn" type="submit" value="Mettre à jour" />
        </form>
    </div>
</div>

</body>
</html>
