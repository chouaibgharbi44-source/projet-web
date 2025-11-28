<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Ajouter Réservation</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <script type="text/javascript" src="View/assets/validation.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Ajouter une réservation</h1>
    <div class="admin-button"><a href="index.php?entity=reservation&area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form method="post" action="index.php?entity=reservation&action=store&area=admin" onsubmit="return validateForm(this);">
            <label>Événement:<br />
                <select name="event_id" required>
                    <?php foreach ($events as $ev): ?>
                        <option value="<?php echo $ev['id']; ?>"><?php echo htmlspecialchars($ev['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </label><br />

            <label>Nom:<br /><input type="text" name="name" required /></label><br />
            <label>Email:<br /><input type="email" name="email" /></label><br />
            <label>Places:<br /><input type="number" name="seats" value="1" min="1" /></label><br />
            <label>Statut:<br />
                <select name="status">
                    <option value="pending">En attente</option>
                    <option value="confirmed">Confirmé</option>
                    <option value="cancelled">Annulé</option>
                </select>
            </label><br />

            <input class="btn" type="submit" value="Enregistrer" />
        </form>
    </div>
</div>

</body>
</html>
