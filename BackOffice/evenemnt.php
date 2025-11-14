<?php
// Server-rendered BackOffice (admin) without JavaScript
require_once __DIR__ . '/../Controller/core_evenement.php';

$events = listEvents();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Connect - Admin - Événements</title>
    <link rel="stylesheet" href="assets/css/admin-style.css">
</head>
<body>
    <div class="container">
        <h1>Admin - Gestion des événements</h1>

        <?php if (isset($_GET['edit']) && ($editing = getEvent(intval($_GET['edit'])))): ?>
            <h2>Modifier l'événement #<?php echo intval($editing['id']); ?></h2>
            <form method="post" action="../Controller/core_evenement.php">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?php echo intval($editing['id']); ?>">
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div>
                    <label>Titre<br><input type="text" name="title" value="<?php echo htmlspecialchars($editing['title']); ?>" required></label>
                </div>
                <div>
                    <label>Description<br><textarea name="description" required><?php echo htmlspecialchars($editing['description']); ?></textarea></label>
                </div>
                <div>
                    <label>Date et heure<br><input type="datetime-local" name="date" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($editing['date']))); ?>" required></label>
                </div>
                <div>
                    <label>Lieu<br><input type="text" name="location" value="<?php echo htmlspecialchars($editing['location']); ?>" required></label>
                </div>
                <div>
                    <label>Capacité<br><input type="number" name="capacity" min="1" value="<?php echo intval($editing['capacity']); ?>"></label>
                </div>
                <div>
                    <label>Image URL<br><input type="url" name="image" value="<?php echo htmlspecialchars($editing['image']); ?>"></label>
                </div>
                <div>
                    <button type="submit" class="btn-primary">Sauvegarder</button>
                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="btn-secondary">Annuler</a>
                </div>
            </form>
        <?php else: ?>
            <h2>Créer un événement</h2>
            <form method="post" action="../Controller/core_evenement.php">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                <div>
                    <label>Titre<br><input type="text" name="title" required></label>
                </div>
                <div>
                    <label>Description<br><textarea name="description" required></textarea></label>
                </div>
                <div>
                    <label>Date et heure<br><input type="datetime-local" name="date" required></label>
                </div>
                <div>
                    <label>Lieu<br><input type="text" name="location" required></label>
                </div>
                <div>
                    <label>Capacité<br><input type="number" name="capacity" min="1"></label>
                </div>
                <div>
                    <label>Image URL<br><input type="url" name="image"></label>
                </div>
                <div>
                    <button type="submit" class="btn-primary">Créer</button>
                </div>
            </form>
        <?php endif; ?>

        <h2>Liste des événements</h2>
        <?php if (empty($events)): ?>
            <p>Aucun événement.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr><th>ID</th><th>Titre</th><th>Date</th><th>Lieu</th><th>Participants</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php foreach ($events as $e): ?>
                    <tr>
                        <td><?php echo intval($e['id']); ?></td>
                        <td><?php echo htmlspecialchars($e['title']); ?></td>
                        <td><?php echo htmlspecialchars($e['date']); ?></td>
                        <td><?php echo htmlspecialchars($e['location']); ?></td>
                        <td><?php echo intval($e['participant_count']); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?edit=' . intval($e['id'])); ?>" class="btn-secondary">Éditer</a>
                            <form method="post" action="../Controller/core_evenement.php" style="display:inline-block; margin-left:8px;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo intval($e['id']); ?>">
                                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                <button type="submit" class="btn-danger" onclick="return confirm('Supprimer ?');">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <p>
            <a href="../FrontOffice/evenemnt.php">Voir côté public</a>
            <a href="dashboard.php" style="margin-left:12px;">Ouvrir le dashboard</a>
        </p>
    </div>
</body>
</html>
