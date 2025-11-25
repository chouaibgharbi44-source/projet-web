<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Ajouter Ressource</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <script type="text/javascript" src="View/assets/validation.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Ajouter une ressource</h1>
    <div class="admin-button"><a href="index.php?entity=ressource&area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form method="post" action="index.php?entity=ressource&action=store&area=admin" onsubmit="return validateRessourceForm(this);">
            <label>Titre:<br /><input type="text" name="titre" /></label><br />
            <label>Description:<br /><textarea name="description" rows="6" cols="50"></textarea></label><br />
            <label>Type de ressource:<br /><input type="text" name="type_ressource" placeholder="Ex: PDF, Tutoriel, Document" /></label><br />
            <label>URL:<br /><input type="text" name="url" placeholder="https://..." /></label><br />
            <label>Auteur:<br /><input type="text" name="auteur" /></label><br />
            <label>Date d'ajout:<br /><input type="text" name="date_ajout" value="<?php echo date('Y-m-d H:i:s'); ?>" /></label><br />
            <input class="btn" type="submit" value="Enregistrer" />
        </form>
    </div>
</div>

</body>
</html>
