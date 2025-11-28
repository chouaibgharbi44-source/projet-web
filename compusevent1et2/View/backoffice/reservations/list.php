<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Gestion Réservations</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Backoffice - Réservations</h1>
    <div class="admin-button"><a href="index.php?area=admin">Événements</a></div>
    <div class="admin-button"><a href="index.php">Frontoffice</a></div>
</div>

<div class="container">
    <div class="list-card full">
        <p class="actions"><a class="btn" href="index.php?entity=reservation&action=add&area=admin">Ajouter une réservation</a></p>
        <table border="1" class="event-table">
            <tr>
                <th>ID</th>
                <th>Événement</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Places</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            <?php if (!empty($reservations)) : ?>
                <?php foreach ($reservations as $r) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['id']); ?></td>
                        <td><?php echo htmlspecialchars($r['event_title'] ?? $r['event_id']); ?></td>
                        <td><?php echo htmlspecialchars($r['name']); ?></td>
                        <td><?php echo htmlspecialchars($r['email']); ?></td>
                        <td><?php echo htmlspecialchars($r['seats']); ?></td>
                        <td><?php echo htmlspecialchars($r['status']); ?></td>
                        <td>
                            <a href="index.php?entity=reservation&action=edit&id=<?php echo $r['id']; ?>&area=admin">Modifier</a> |
                            <a href="index.php?entity=reservation&action=delete&id=<?php echo $r['id']; ?>&area=admin" onclick="return confirm('Supprimer ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Aucune réservation trouvée.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>
