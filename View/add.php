<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Ajouter une matière</title>
</head>
<body>
<h1>Ajouter une matière</h1>
<form method="post" action="index.php?action=store">
    <label>Nom matière: <input type="text" name="nom_matiere" /></label><br />
    <label>Titre: <input type="text" name="titre" /></label><br />
    <label>Description: <textarea name="description"></textarea></label><br />
    <label>Date d'ajout: <input type="text" name="date_ajout" value="<?php echo date('Y-m-d H:i:s'); ?>" /></label><br />
    <label>Niveau difficulté: <input type="text" name="niveau_difficulte" /></label><br />
    <input type="submit" value="Enregistrer" />
</form>
<p><a href="index.php">Retour à la liste</a></p>
</body>
</html>