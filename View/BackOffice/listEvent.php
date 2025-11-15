<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/Event.php';
require_once __DIR__ . '/../../Controller/EventController.php';

$controller = new EventController();
$events = $controller->listEvents();

// Traiter la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $result = $controller->deleteEvent($delete_id);
    
    if ($result['success']) {
        // Rediriger pour rafraîchir la page
        header('Location: listEvent.php');
        exit;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Liste des événements - BackOffice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-add {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-add:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        a.btn-edit, a.btn-view, .btn-delete {
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            border: none;
            cursor: pointer;
        }
        a.btn-edit {
            background-color: #2196F3;
            color: white;
        }
        a.btn-edit:hover {
            background-color: #0b7dda;
        }
        a.btn-view {
            background-color: #17a2b8;
            color: white;
        }
        a.btn-view:hover {
            background-color: #138496;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
        }
        .btn-delete:hover {
            background-color: #da190b;
        }
        .empty {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
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
        <h1>
            <span>Gestion des événements</span>
            <a href="addEvent.php" class="btn-add">+ Ajouter un événement</a>
        </h1>

        <?php if (empty($events)): ?>
            <div class="empty">
                <p>Aucun événement créé pour le moment.</p>
                <a href="addEvent.php" class="btn-add">Créer le premier événement</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Lieu</th>
                        <th>Catégorie</th>
                        <th>Capacité</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): 
                        $eventDate = strtotime($event->getDate());
                        $today = strtotime(date('Y-m-d'));
                        $status = $eventDate >= $today ? 'upcoming' : 'past';
                    ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($event->getTitle()); ?></strong></td>
                            <td><?php echo htmlspecialchars($event->getDate()); ?></td>
                            <td><?php echo htmlspecialchars($event->getLocation()); ?></td>
                            <td><span class="status <?php echo $status; ?>"><?php echo htmlspecialchars($event->getCategory()); ?></span></td>
                            <td><?php echo htmlspecialchars($event->getCapacity()); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($event->getCreatedAt()))); ?></td>
                            <td>
                                <div class="actions">
                                    <a href="viewEvent.php?id=<?php echo $event->getId(); ?>" class="btn-view">Voir</a>
                                    <a href="updateEvent.php?id=<?php echo $event->getId(); ?>" class="btn-edit">Éditer</a>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="delete_id" value="<?php echo $event->getId(); ?>">
                                        <button type="submit" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
