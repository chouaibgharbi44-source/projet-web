<?php
// BackOffice Dashboard: full table view with edit/delete actions
require_once __DIR__ . '/../Controller/core_evenement.php';

$events = listEvents();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard - Gestion des événements</title>
    <link rel="stylesheet" href="assets/css/admin-style.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard - Liste complète des événements</h1>
        <p>
            <a href="evenemnt.php" class="btn-primary">Créer / Éditer</a>
            <a href="../FrontOffice/evenemnt.php" class="btn-secondary">Voir public</a>
        </p>

        <?php if (empty($events)): ?>
            <p>Aucun événement.</p>
        <?php else: ?>
            <table class="admin-table" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Capacité</th>
                        <th>Participants</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $e): ?>
                        <tr>
                            <td><?php echo intval($e['id']); ?></td>
                            <td><?php echo htmlspecialchars($e['title']); ?></td>
                            <td style="max-width:300px; white-space:pre-wrap"><?php echo htmlspecialchars($e['description']); ?></td>
                            <td><?php echo htmlspecialchars($e['date']); ?></td>
                            <td><?php echo htmlspecialchars($e['location']); ?></td>
                            <td><?php echo ($e['capacity'] === null ? '—' : intval($e['capacity'])); ?></td>
                            <td><?php echo intval($e['participant_count']); ?></td>
                            <td><?php echo htmlspecialchars($e['created_at'] ?? ''); ?></td>
                            <td>
                                <a class="btn-secondary" href="evenemnt.php?edit=<?php echo intval($e['id']); ?>">Éditer</a>
                                <form method="post" action="../Controller/core_evenement.php" style="display:inline-block; margin-left:6px;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo intval($e['id']); ?>">
                                    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                    <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cet événement ?');">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
