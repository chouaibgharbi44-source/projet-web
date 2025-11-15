<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Model/Event.php';
require_once __DIR__ . '/../../Model/Validation.php';
require_once __DIR__ . '/../../Controller/EventController.php';

$controller = new EventController();
$errors = [];
$success = false;

// Traiter le formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation des données
    $validation_result = Validation::validateEvent($_POST);
    
    if ($validation_result === true) {
        // Créer un nouvel objet Event
        try {
            $event = new Event(
                $_POST['title'],
                $_POST['description'],
                $_POST['date'],
                $_POST['location'],
                $_POST['capacity'],
                $_POST['category'],
                isset($_FILES['image']) && $_FILES['image']['error'] === 0 ? $_FILES['image']['name'] : null,
                1 // created_by (utilisateur courant)
            );
            
            // Sauvegarder dans la base de données
            $result = $controller->addEvent($event);
            
            if ($result['success']) {
                $success = true;
                // Rediriger vers la liste
                header('Location: listEvent.php');
                exit;
            } else {
                $errors[] = $result['message'];
            }
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    } else {
        $errors = $validation_result;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Ajouter un événement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        input:focus,
        textarea:focus,
        textarea:focus,
        select:focus {
            border-color: #4CAF50;
            outline: none;
            background-color: #f9fff7;
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        button, a.btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        a.btn {
            background-color: #666;
            color: white;
        }
        a.btn:hover {
            background-color: #555;
        }
        .error {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border-left: 4px solid #c62828;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .error-list li {
            padding: 5px 0;
            padding-left: 20px;
            position: relative;
        }
        .error-list li:before {
            content: "✗ ";
            position: absolute;
            left: 0;
        }
        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-left: 4px solid #4CAF50;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un nouvel événement</h1>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <strong>Erreurs :</strong>
                <ul class="error-list">
                    <?php foreach ($errors as $field => $error): ?>
                        <li><?php echo is_numeric($field) ? htmlspecialchars($error) : ucfirst($field) . " : " . htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="text" id="date" name="date" value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="location">Lieu</label>
                <input type="text" id="location" name="location" value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="capacity">Capacité (nombre de participants)</label>
                <input type="text" id="capacity" name="capacity" value="<?php echo isset($_POST['capacity']) ? htmlspecialchars($_POST['capacity']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="category">Catégorie</label>
                <select id="category" name="category">
                    <option value="">-- Sélectionner une catégorie --</option>
                    <option value="Conférence" <?php echo isset($_POST['category']) && $_POST['category'] === 'Conférence' ? 'selected' : ''; ?>>Conférence</option>
                    <option value="Atelier" <?php echo isset($_POST['category']) && $_POST['category'] === 'Atelier' ? 'selected' : ''; ?>>Atelier</option>
                    <option value="Festival" <?php echo isset($_POST['category']) && $_POST['category'] === 'Festival' ? 'selected' : ''; ?>>Festival</option>
                    <option value="Réunion" <?php echo isset($_POST['category']) && $_POST['category'] === 'Réunion' ? 'selected' : ''; ?>>Réunion</option>
                    <option value="Sport" <?php echo isset($_POST['category']) && $_POST['category'] === 'Sport' ? 'selected' : ''; ?>>Sport</option>
                    <option value="Culturel" <?php echo isset($_POST['category']) && $_POST['category'] === 'Culturel' ? 'selected' : ''; ?>>Culturel</option>
                    <option value="Autre" <?php echo isset($_POST['category']) && $_POST['category'] === 'Autre' ? 'selected' : ''; ?>>Autre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Image (optionnel)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <div class="button-group">
                <button type="submit">Créer l'événement</button>
                <a href="listEvent.php" class="btn">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
