<?php
// Server-rendered FrontOffice without JavaScript
require_once __DIR__ . '/../Controller/core_evenement.php';

$events = listEvents();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Connect - Événements</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">🎓 Campus Connect</div>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1>Événements</h1>
        </div>
    </section>

    <section class="events-section">
        <div class="container">
            <a href="../BackOffice/evenemnt.php" class="btn-primary">Admin</a>
            <h2>Tous les événements</h2>
            <?php if (empty($events)): ?>
                <p>Aucun événement pour le moment.</p>
            <?php else: ?>
                <ul class="events-list">
                    <?php foreach ($events as $e): ?>
                        <li class="event-item">
                            <h3><?php echo htmlspecialchars($e['title']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($e['description'])); ?></p>
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($e['date']); ?></p>
                            <p><strong>Lieu:</strong> <?php echo htmlspecialchars($e['location']); ?></p>
                            <p><strong>Participants:</strong> <?php echo intval($e['participant_count']); ?></p>
                            <form method="post" action="../Controller/core_evenement.php">
                                <input type="hidden" name="action" value="join">
                                <input type="hidden" name="event_id" value="<?php echo intval($e['id']); ?>">
                                <input type="hidden" name="user_id" value="1">
                                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                <button type="submit" class="btn-primary">Rejoindre</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>

    <footer class="footer">
        <div class="container">&copy; Campus Connect</div>
    </footer>
</body>
</html>
