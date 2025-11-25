<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Ajouter Matière</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <script type="text/javascript" src="View/assets/validation.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Ajouter une matière</h1>
    <div class="admin-button"><a href="index.php?area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form method="post" action="index.php?action=store&area=admin" onsubmit="return validateForm(this);">
            <label>Nom matière:<br /><input type="text" name="nom_matiere" /></label><br />
            <label>Titre:<br /><input type="text" name="titre" /></label><br />
            <label>Description:<br /><textarea name="description" rows="6" cols="50"></textarea></label><br />
            <label>Date d'ajout:<br /><input type="text" name="date_ajout" value="<?php echo date('Y-m-d H:i:s'); ?>" /></label><br />
            <label>Niveau difficulté:<br /><input type="text" name="niveau_difficulte" /></label><br />
            <input class="btn" type="submit" value="Enregistrer" />
        </form>
    </div>
</div>

</body>
</html>
