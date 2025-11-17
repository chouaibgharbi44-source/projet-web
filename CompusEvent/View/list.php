<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Liste des événements</title>
</head>
<body>
<h1>Liste des événements</h1>
<p><a href="index.php?action=add">Ajouter un événement</a></p>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Date</th>
        <th>Lieu</th>
        <th>Capacité</th>
        <th>Actions</th>
    </tr>
    <?php if (!empty($evenements)) : ?>
        <?php foreach ($evenements as $m) : ?>
            <tr>
                <td><?php echo htmlspecialchars($m['id']); ?></td>
                <td><?php echo htmlspecialchars($m['title']); ?></td>
                <td><?php echo htmlspecialchars($m['description']); ?></td>
                <td><?php echo htmlspecialchars($m['description']); ?></td>
                <td><?php echo htmlspecialchars($m['date']); ?></td>
                <td><?php echo htmlspecialchars($m['location']); ?></td>
                <td><?php echo htmlspecialchars($m['capacity']); ?></td>
                <td>
                    <a href="index.php?action=edit&id=<?php echo $m['id']; ?>">Modifier</a> |
                    <a href="index.php?action=delete&id=<?php echo $m['id']; ?>" onclick="return confirm('Supprimer ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="9">Aucun événement trouvé.</td></tr>
    <?php endif; ?>
</table>
</body>
</html>