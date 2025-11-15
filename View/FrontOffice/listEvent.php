<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/Event.php';
require_once __DIR__ . '/../../Controller/EventController.php';

$controller = new EventController();
$events = $controller->listEvents();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Événements</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: white;
            margin-bottom: 40px;
            text-align: center;
            font-size: 2.5em;
        }
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        .event-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .event-header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .event-header h2 {
            margin-bottom: 10px;
            font-size: 1.4em;
        }
        .event-meta {
            display: flex;
            gap: 15px;
            font-size: 0.9em;
            flex-wrap: wrap;
        }
        .event-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .event-body {
            padding: 20px;
            flex-grow: 1;
        }
        .description {
            color: #666;
            font-size: 0.95em;
            line-height: 1.5;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .event-details {
            display: grid;
            gap: 10px;
            font-size: 0.9em;
            color: #555;
            margin-bottom: 15px;
        }
        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .badge.upcoming {
            background-color: #c3e6cb;
            color: #155724;
        }
        .badge.past {
            background-color: #f8d7da;
            color: #721c24;
        }
        .event-footer {
            padding: 15px 20px;
            background: #f9f9f9;
            border-top: 1px solid #eee;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.9em;
        }
        .btn:hover {
            background-color: #764ba2;
        }
        .empty {
            text-align: center;
            color: white;
            padding: 60px 20px;
        }
        .empty h2 {
            margin-bottom: 20px;
            font-size: 1.8em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📅 Découvrez nos événements</h1>

        <?php if (empty($events)): ?>
            <div class="empty">
                <h2>Aucun événement disponible</h2>
                <p>Revenez bientôt pour découvrir nos prochains événements.</p>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach ($events as $event): 
                    $eventDate = strtotime($event->getDate());
                    $today = strtotime(date('Y-m-d'));
                    $status = $eventDate >= $today ? 'upcoming' : 'past';
                ?>
                    <div class="event-card">
                        <div class="event-header">
                            <h2><?php echo htmlspecialchars($event->getTitle()); ?></h2>
                            <div class="event-meta">
                                <span>📅 <?php echo htmlspecialchars($event->getDate()); ?></span>
                                <span>📍 <?php echo htmlspecialchars($event->getLocation()); ?></span>
                            </div>
                        </div>

                        <div class="event-body">
                            <p class="description"><?php echo htmlspecialchars($event->getDescription()); ?></p>
                            
                            <div class="event-details">
                                <div class="detail-item">
                                    <span class="badge <?php echo $status; ?>">
                                        <?php echo $status === 'upcoming' ? '⏳ À venir' : '✓ Passé'; ?>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <strong>Catégorie :</strong> <?php echo htmlspecialchars($event->getCategory()); ?>
                                </div>
                                <div class="detail-item">
                                    <strong>Capacité :</strong> <?php echo htmlspecialchars($event->getCapacity()); ?> personnes
                                </div>
                            </div>
                        </div>

                        <div class="event-footer">
                            <a href="viewEvent.php?id=<?php echo $event->getId(); ?>" class="btn">Voir les détails</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
