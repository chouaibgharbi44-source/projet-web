<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Modifier Réservation</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <script type="text/javascript" src="View/assets/validation.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Modifier une réservation</h1>
    <div class="admin-button"><a href="index.php?entity=reservation&area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form method="post" action="index.php?entity=reservation&action=update&area=admin" onsubmit="return validateForm(this);">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($reservation['id']); ?>" />
            <label>Événement:<br />
                <select name="event_id" required>
                    <?php foreach ($events as $ev): ?>
                        <option value="<?php echo $ev['id']; ?>" <?php echo $ev['id']==$reservation['event_id']?'selected':''; ?>><?php echo htmlspecialchars($ev['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label><br />

            <label>Nom:<br /><input type="text" name="name" value="<?php echo htmlspecialchars($reservation['name']); ?>" required /></label><br />
            <label>Email:<br /><input type="email" name="email" value="<?php echo htmlspecialchars($reservation['email']); ?>" /></label><br />
            <label>Places:<br /><input type="number" name="seats" value="<?php echo htmlspecialchars($reservation['seats']); ?>" min="1" /></label><br />
            <label>Statut:<br />
                <select name="status">
                    <option value="pending" <?php echo $reservation['status']=='pending'?'selected':''; ?>>En attente</option>
                    <option value="confirmed" <?php echo $reservation['status']=='confirmed'?'selected':''; ?>>Confirmé</option>
                    <option value="cancelled" <?php echo $reservation['status']=='cancelled'?'selected':''; ?>>Annulé</option>
                </select>
            </label><br />

            <input class="btn" type="submit" value="Mettre à jour" />
        </form>
    </div>
</div>

</body>
</html>
