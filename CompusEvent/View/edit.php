<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Modifier un événement</title>
</head>
<body>
<h1>Modifier un événement</h1>
<form method="post" action="index.php?action=update">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($evenement['id']); ?>" />
    <label>Titre: <input type="text" name="title" value="<?php echo htmlspecialchars($evenement['title']); ?>" /></label><br />
    <label>Description: <textarea name="description"><?php echo htmlspecialchars($evenement['description']); ?></textarea></label><br />
    <label>Date & Heure: <input type="text" name="date" placeholder="2025-12-15 14:00" value="<?php echo date('Y-m-d H:i', strtotime($evenement['date'])); ?>" /></label><br />
    <label>Lieu: <input type="text" name="location" value="<?php echo htmlspecialchars($evenement['location']); ?>" /></label><br />
    <input type="submit" value="Mettre à jour" />
</form>
<p><a href="index.php">Retour à la liste</a></p>
</body>
</html>