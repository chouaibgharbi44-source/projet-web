<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Ajouter Événement</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <script type="text/javascript" src="View/assets/validation.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Ajouter un événement</h1>
    <div class="admin-button"><a href="index.php?area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form method="post" action="index.php?action=store&area=admin" onsubmit="return validateForm(this);">
            <label>Titre:<br /><input type="text" name="title" required /></label><br />
            <label>Description:<br /><textarea name="description" rows="6" cols="50" required></textarea></label><br />
            <label>Date & Heure (format: YYYY-MM-DD HH:MM):<br /><input type="text" name="date" placeholder="2025-12-15 14:00" value="<?php echo date('Y-m-d H:i'); ?>" required /></label><br />
            <label>Lieu:<br /><input type="text" name="location" required /></label><br />
            <label>Capacité:<br /><input type="number" name="capacity" /></label><br />
            <label>Catégorie:<br /><input type="text" name="category" value="Autre" /></label><br />
            <label>Image (URL):<br /><input type="text" name="image" /></label><br />
            <label>Statut:<br />
                <select name="status">
                    <option value="approved">Approuvé</option>
                    <option value="pending">En attente</option>
                    <option value="rejected">Rejeté</option>
                    <option value="cancelled">Annulé</option>
                </select>
            </label><br />
            <input type="hidden" name="created_by" value="1" />
            <input class="btn" type="submit" value="Enregistrer" />
        </form>
    </div>
</div>

</body>
</html>
