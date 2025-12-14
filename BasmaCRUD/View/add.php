<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Ajouter un événement</title>
</head>
<body>
<h1>Ajouter un événement</h1>
<form method="post" action="index.php?action=store">
    <label>Titre: <input type="text" name="title" /></label><br />
    <label>Description: <textarea name="description"></textarea></label><br />
    <label>Date & Heure (format: YYYY-MM-DD HH:MM): <input type="text" name="date" placeholder="2025-12-15 14:00" value="<?php echo date('Y-m-d H:i'); ?>" /></label><br />
    <label>Lieu: <input type="text" name="location" /></label><br />
    <input type="submit" value="Enregistrer" />
</form>
<p><a href="index.php">Retour à la liste</a></p>
</body>
</html>