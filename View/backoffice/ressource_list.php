<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Backoffice - Gestion Ressources</title>
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="topbar admin">
    <h1>Backoffice - Ressources</h1>
    <div class="admin-button">
        <a href="index.php">Frontoffice</a>
        <a href="index.php?area=admin">Accueil Admin</a>
    </div>
</div>

<div class="container">
    <div class="list-card full">
        <p class="actions"><a class="btn" href="index.php?entity=ressource&action=add&area=admin">Ajouter une ressource</a></p>

        <!-- backoffice: no stats here (clean admin list) -->
        <table border="1" class="matiere-table">
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Type</th>
                <th>Auteur</th>
                <th>URL</th>
                <th>Date d'ajout</th>
                <th>Actions</th>
            </tr>
            <?php if (!empty($ressources)) : ?>
                <?php foreach ($ressources as $r) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['id']); ?></td>
                        <td><?php echo htmlspecialchars($r['titre']); ?></td>
                        <td><?php echo htmlspecialchars(substr($r['description'], 0, 50) . '...'); ?></td>
                        <td><?php echo htmlspecialchars($r['type_ressource']); ?></td>
                        <td><?php echo htmlspecialchars($r['auteur']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($r['url']); ?>" target="_blank">Lien</a></td>
                        <td><?php echo htmlspecialchars($r['date_ajout']); ?></td>
                        <td>
                            <a href="index.php?entity=ressource&action=edit&id=<?php echo $r['id']; ?>&area=admin">Modifier</a> |
                            <a href="index.php?entity=ressource&action=delete&id=<?php echo $r['id']; ?>&area=admin" onclick="return confirm('Supprimer ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">Aucune ressource trouvée.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>
<!-- Charts removed from admin view per request -->
