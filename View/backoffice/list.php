<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Gestion Matières</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Backoffice - Matières</h1>
    <div class="admin-button"><a href="index.php">Frontoffice</a></div>
</div>

<div class="container">
    <div class="list-card full">
        <p class="actions"><a class="btn" href="index.php?action=add&area=admin">Ajouter une matière</a></p>
        <table border="1" class="matiere-table">
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
                            <a href="index.php?action=edit&id=<?php echo $m['id']; ?>&area=admin">Modifier</a> |
                            <a href="index.php?action=delete&id=<?php echo $m['id']; ?>&area=admin" onclick="return confirm('Supprimer ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Aucune matière trouvée.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>
