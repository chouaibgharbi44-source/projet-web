<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Modifier une matière</title>
</head>
<body>
<h1>Modifier une matière</h1>
<form method="post" action="index.php?action=update">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($matiere['id']); ?>" />
    <label>Nom matière: <input type="text" name="nom_matiere" value="<?php echo htmlspecialchars($matiere['nom_matiere']); ?>" /></label><br />
    <label>Titre: <input type="text" name="titre" value="<?php echo htmlspecialchars($matiere['titre']); ?>" /></label><br />
    <label>Description: <textarea name="description"><?php echo htmlspecialchars($matiere['description']); ?></textarea></label><br />
    <label>Date d'ajout: <input type="text" name="date_ajout" value="<?php echo htmlspecialchars($matiere['date_ajout']); ?>" /></label><br />
    <label>Niveau difficulté: <input type="text" name="niveau_difficulte" value="<?php echo htmlspecialchars($matiere['niveau_difficulte']); ?>" /></label><br />
    <input type="submit" value="Mettre à jour" />
</form>
<p><a href="index.php">Retour à la liste</a></p>
</body>
</html>