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
     <title>Campus Connect - Détail événement</title>
     <link rel="stylesheet" href="../../FrontOffice/assets/css/style.css">
     <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9em;
        }
        .back-link:hover {
            color: white;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .meta {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            font-size: 1em;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .content {
            padding: 40px 30px;
        }
        .status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
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
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .description {
            color: #666;
            line-height: 1.6;
            font-size: 1.05em;
            white-space: pre-wrap;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .detail-card {
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }
        .detail-card strong {
            display: block;
            color: #667eea;
            font-size: 0.85em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .detail-card span {
            color: #333;
            font-size: 1.1em;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background-color: #764ba2;
        }
        .btn-secondary {
            background-color: #666;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #555;
        }
        .btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="listEvent.php" class="back-link">← Retour aux événements</a>
            <h1><?php echo htmlspecialchars($event->getTitle()); ?></h1>
            
            <div class="meta">
                <div class="meta-item">
                    📅 <?php echo htmlspecialchars($event->getDate()); ?>
                </div>
                <div class="meta-item">
                    📍 <?php echo htmlspecialchars($event->getLocation()); ?>
                </div>
                <div class="meta-item">
                    👥 <?php echo htmlspecialchars($event->getCapacity()); ?> personnes
                </div>
            </div>
        </div>

        <div class="content">
            <?php 
                $eventDate = strtotime($event->getDate());
                $today = strtotime(date('Y-m-d'));
                $status = $eventDate >= $today ? 'upcoming' : 'past';
            ?>

            <span class="status <?php echo $status; ?>">
                <?php echo $status === 'upcoming' ? '⏳ Événement à venir' : '✓ Événement passé'; ?>
            </span>

            <div class="section">
                <h2>📝 Description</h2>
                <p class="description"><?php echo nl2br(htmlspecialchars($event->getDescription())); ?></p>
            </div>

            <div class="section">
                <h2>ℹ️ Détails</h2>
                <div class="details-grid">
                    <div class="detail-card">
                        <strong>Date de l'événement</strong>
                        <span><?php echo htmlspecialchars($event->getDate()); ?></span>
                    </div>
                    <div class="detail-card">
                        <strong>Lieu</strong>
                        <span><?php echo htmlspecialchars($event->getLocation()); ?></span>
                    </div>
                    <div class="detail-card">
                        <strong>Catégorie</strong>
                        <span><?php echo htmlspecialchars($event->getCategory()); ?></span>
                    </div>
                    <div class="detail-card">
                        <strong>Capacité</strong>
                        <span><?php echo htmlspecialchars($event->getCapacity()); ?> personnes</span>
                    </div>
                    <div class="detail-card">
                        <strong>Créé le</strong>
                        <span><?php echo date('d/m/Y à H:i', strtotime($event->getCreatedAt())); ?></span>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <?php if ($status === 'upcoming'): ?>
                    <button class="btn btn-primary" onclick="alert('Fonctionnalité de réservation bientôt disponible!');">
                        🎟️ Rejoindre l'événement
                    </button>
                <?php else: ?>
                    <button class="btn btn-primary" disabled>
                        ✓ Événement terminé
                    </button>
                <?php endif; ?>
                <a href="listEvent.php" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
</body>
</html>
