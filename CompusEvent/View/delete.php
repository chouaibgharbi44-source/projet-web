<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Supprimer un événement</title>
</head>
<body>
<h1>Supprimer un événement</h1>
<p>Êtes-vous sûr de vouloir supprimer l'événement <strong><?php echo htmlspecialchars($evenement['title']); ?></strong> ?</p>
<form method="get" action="index.php">
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($evenement['id']); ?>" />
    <input type="submit" value="Confirmer" />
</form>
<p><a href="index.php">Annuler</a></p>
</body>
</html>