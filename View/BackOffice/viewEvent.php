<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/Event.php';
require_once __DIR__ . '/../../Controller/EventController.php';

// Récupérer l'ID de l'événement
if (!isset($_GET['id'])) {
    header('Location: listEvent.php');
    exit;
}

$event_id = intval($_GET['id']);
$event = Event::getById($event_id);

if (!$event) {
    header('Location: listEvent.php');
    exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo htmlspecialchars($event->getTitle()); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #666;
            text-decoration: none;
        }
        .back-link:hover {
            color: #333;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            margin-top: 0;
        }
        .event-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-block {
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
        }
        .info-block strong {
            display: block;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-block span {
            color: #333;
            font-size: 16px;
        }
        .description {
            grid-column: 1 / -1;
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid #2196F3;
            border-radius: 4px;
        }
        .description strong {
            display: block;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        a, button {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }
        a.btn-back {
            background-color: #666;
            color: white;
        }
        a.btn-back:hover {
            background-color: #555;
        }
        a.btn-edit {
            background-color: #2196F3;
            color: white;
        }
        a.btn-edit:hover {
            background-color: #0b7dda;
        }
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .status.upcoming {
            background-color: #c3e6cb;
            color: #155724;
        }
        .status.past {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="listEvent.php" class="back-link">← Retour à la liste</a>

        <?php 
            $eventDate = strtotime($event->getDate());
            $today = strtotime(date('Y-m-d'));
            $status = $eventDate >= $today ? 'upcoming' : 'past';
        ?>

        <h1><?php echo htmlspecialchars($event->getTitle()); ?></h1>
        
        <span class="status <?php echo $status; ?>">
            <?php echo $status === 'upcoming' ? '📅 À venir' : '✓ Passé'; ?>
        </span>

        <div class="event-info">
            <div class="info-block">
                <strong>Date</strong>
                <span><?php echo htmlspecialchars($event->getDate()); ?></span>
            </div>

            <div class="info-block">
                <strong>Lieu</strong>
                <span><?php echo htmlspecialchars($event->getLocation()); ?></span>
            </div>

            <div class="info-block">
                <strong>Catégorie</strong>
                <span><?php echo htmlspecialchars($event->getCategory()); ?></span>
            </div>

            <div class="info-block">
                <strong>Capacité</strong>
                <span><?php echo htmlspecialchars($event->getCapacity()); ?> personnes</span>
            </div>

            <div class="description">
                <strong>Description</strong>
                <p><?php echo nl2br(htmlspecialchars($event->getDescription())); ?></p>
            </div>
        </div>

        <div class="button-group">
            <a href="updateEvent.php?id=<?php echo $event->getId(); ?>" class="btn-edit">Éditer cet événement</a>
            <a href="listEvent.php" class="btn-back">Retour à la liste</a>
        </div>
    </div>
</body>
</html>
