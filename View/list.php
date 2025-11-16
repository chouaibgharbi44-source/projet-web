<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Liste des matières</title>
</head>
<body>
<h1>Liste des matières</h1>
<p><a href="index.php?action=add">Ajouter une matière</a></p>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Date d'ajout</th>
        <th>Niveau</th>
        <th>Actions</th>
    </tr>
    <?php if (!empty($matieres)) : ?>
        <?php foreach ($matieres as $m) : ?>
            <tr>
                <td><?php echo htmlspecialchars($m['id']); ?></td>
                <td><?php echo htmlspecialchars($m['nom_matiere']); ?></td>
                <td><?php echo htmlspecialchars($m['titre']); ?></td>
                <td><?php echo htmlspecialchars($m['description']); ?></td>
                <td><?php echo htmlspecialchars($m['date_ajout']); ?></td>
                <td><?php echo htmlspecialchars($m['niveau_difficulte']); ?></td>
                <td>
                    <a href="index.php?action=edit&id=<?php echo $m['id']; ?>">Modifier</a> |
                    <a href="index.php?action=delete&id=<?php echo $m['id']; ?>" onclick="return confirm('Supprimer ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="7">Aucune matière trouvée.</td></tr>
    <?php endif; ?>
</table>
</body>
</html>