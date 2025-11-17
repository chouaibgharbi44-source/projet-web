<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Modifier Événement</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <script type="text/javascript" src="View/assets/validation.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Modifier un événement</h1>
    <div class="admin-button"><a href="index.php?area=admin">Retour</a></div>
</div>

<div class="container">
    <div class="form-card">
        <form method="post" action="index.php?action=update&area=admin" onsubmit="return validateForm(this);">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($evenement['id']); ?>" />
            <label>Titre:<br /><input type="text" name="title" value="<?php echo htmlspecialchars($evenement['title']); ?>" /></label><br />
            <label>Description:<br /><textarea name="description" rows="6" cols="50"><?php echo htmlspecialchars($evenement['description']); ?></textarea></label><br />
            <label>Date & Heure (format: YYYY-MM-DD HH:MM):<br /><input type="text" name="date" placeholder="2025-12-15 14:00" value="<?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($evenement['date']))); ?>" /></label><br />
            <label>Lieu:<br /><input type="text" name="location" value="<?php echo htmlspecialchars($evenement['location']); ?>" /></label><br />
            <label>Capacité:<br /><input type="number" name="capacity" value="<?php echo htmlspecialchars($evenement['capacity']); ?>" /></label><br />
            <label>Catégorie:<br /><input type="text" name="category" value="<?php echo htmlspecialchars($evenement['category']); ?>" /></label><br />
            <label>Image (URL):<br /><input type="text" name="image" value="<?php echo htmlspecialchars($evenement['image']); ?>" /></label><br />
            <label>Statut:<br />
                <select name="status">
                    <option value="approved" <?php echo $evenement['status']=='approved'?'selected':''; ?>>Approuvé</option>
                    <option value="pending" <?php echo $evenement['status']=='pending'?'selected':''; ?>>En attente</option>
                    <option value="rejected" <?php echo $evenement['status']=='rejected'?'selected':''; ?>>Rejeté</option>
                    <option value="cancelled" <?php echo $evenement['status']=='cancelled'?'selected':''; ?>>Annulé</option>
                </select>
            </label><br />
            <input class="btn" type="submit" value="Mettre à jour" />
        </form>
    </div>
</div>

</body>
</html>
