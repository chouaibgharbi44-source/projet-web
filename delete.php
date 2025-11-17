<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Supprimer une matière</title>
</head>
<body>
<h1>Supprimer une matière</h1>
<p>Êtes-vous sûr de vouloir supprimer la matière <strong><?php echo htmlspecialchars($matiere['titre']); ?></strong> ?</p>
<form method="get" action="index.php">
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($matiere['id']); ?>" />
    <input type="submit" value="Confirmer" />
</form>
<p><a href="index.php">Annuler</a></p>
</body>
</html>