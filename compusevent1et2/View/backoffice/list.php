<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Gestion Événements</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Backoffice - Événements</h1>
    <div class="admin-button"><a href="index.php">Frontoffice</a></div>
    <div class="admin-button"><a href="index.php?entity=reservation&area=admin">Réservations</a></div>
</div>

<div class="container">
    <div class="list-card full">
        <p class="actions"><a class="btn" href="index.php?action=add&area=admin">Ajouter un événement</a></p>
        <table border="1" class="event-table">
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Date</th>
                <th>Lieu</th>
                <th>Capacité</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
            <?php if (!empty($evenements)) : ?>
                <?php foreach ($evenements as $e) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($e['id']); ?></td>
                        <td><?php echo htmlspecialchars($e['title']); ?></td>
                        <td><?php echo htmlspecialchars($e['description']); ?></td>
                        <td><?php echo htmlspecialchars($e['date']); ?></td>
                        <td><?php echo htmlspecialchars($e['location']); ?></td>
                        <td><?php echo htmlspecialchars($e['capacity']); ?></td>
                        <td><?php echo htmlspecialchars($e['category']); ?></td>
                        <td><?php echo htmlspecialchars($e['status']); ?></td>
                        <td>
                            <a href="index.php?action=edit&id=<?php echo $e['id']; ?>&area=admin">Modifier</a> |
                            <a href="index.php?action=delete&id=<?php echo $e['id']; ?>&area=admin" onclick="return confirm('Supprimer ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9">Aucun événement trouvé.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>
