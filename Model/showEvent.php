<?php
/**
 * Fichier de démonstration
 * Crée un objet Event et affiche ses informations via la méthode show()
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Event.php';
require_once __DIR__ . '/Validation.php';

// Exemple 1 : Créer un événement directement
echo "<h2>1. Création d'un événement avec le constructeur paramétré</h2>";

try {
    $event1 = new Event(
        "Conférence PHP 2024",
        "Une conférence dédiée aux meilleures pratiques en PHP et à l'architecture MVC. Découvrez comment structurer vos projets pour la maintenance et la scalabilité.",
        "2024-12-15",
        "Tunis, Esprit",
        150,
        "Conférence",
        "php-conference.jpg",
        1
    );

    echo "<h3>Affichage via var_dump():</h3>";
    var_dump($event1->toArray());

    echo "<h3>Affichage via la méthode show():</h3>";
    $event1->show();

} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";

// Exemple 2 : Créer un événement avec validation des attributs
echo "<h2>2. Création d'un événement avec validation des setters</h2>";

try {
    $event2 = new Event();
    
    // Utiliser les setters (qui valident les données)
    $event2->setTitle("Atelier Web Design");
    $event2->setDescription("Apprenez les principes du design web moderne, responsive et accessible. Travaillez avec les derniers outils et frameworks.");
    $event2->setDate("2024-12-20");
    $event2->setLocation("Sfax, Esprit");
    $event2->setCapacity(50);
    $event2->setCategory("Atelier");
    $event2->setCreatedBy(2);

    echo "<h3>Objet créé avec setters validés</h3>";
    $event2->show();

} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur de validation : " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";

// Exemple 3 : Démontrer la validation des données
echo "<h2>3. Test de validation - Données invalides</h2>";

try {
    $event3 = new Event();
    
    // Ceci va générer une erreur car le titre est trop court
    $event3->setTitle("Hi");  // Minimum 3 caractères requis
    
} catch (Exception $e) {
    echo "<p style='color: orange; background: #fff3cd; padding: 10px; border-left: 4px solid orange;'>";
    echo "<strong>✗ Erreur de validation attendue :</strong> " . htmlspecialchars($e->getMessage());
    echo "</p>";
}

echo "<hr>";

// Exemple 4 : Utiliser les getters
echo "<h2>4. Utilisation des getters</h2>";

try {
    $event1 = new Event(
        "Conférence Python",
        "Apprenez les meilleures pratiques en Python avec un focus sur les applications web et la data science.",
        "2024-12-25",
        "Monastir",
        200,
        "Conférence"
    );
    
    $event1->save(); // Sauvegarder dans la base de données
    
    echo "<h3>Accès aux propriétés via getters :</h3>";
    echo "<ul>";
    echo "<li><strong>ID :</strong> " . htmlspecialchars($event1->getId()) . "</li>";
    echo "<li><strong>Titre :</strong> " . htmlspecialchars($event1->getTitle()) . "</li>";
    echo "<li><strong>Date :</strong> " . htmlspecialchars($event1->getDate()) . "</li>";
    echo "<li><strong>Lieu :</strong> " . htmlspecialchars($event1->getLocation()) . "</li>";
    echo "<li><strong>Capacité :</strong> " . htmlspecialchars($event1->getCapacity()) . "</li>";
    echo "<li><strong>Catégorie :</strong> " . htmlspecialchars($event1->getCategory()) . "</li>";
    echo "<li><strong>Créé le :</strong> " . htmlspecialchars($event1->getCreatedAt()) . "</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Démonstration de la classe Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        h1, h2, h3 {
            color: #333;
        }
        h1 {
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        hr {
            margin: 30px 0;
            border: none;
            border-top: 2px solid #ddd;
        }
        .event-info {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            background: #f9f9f9;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-left: 4px solid #4CAF50;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>🎓 Démonstration de la classe Event (Architecture MVC)</h1>
    <p>Ce fichier démonstratif montre comment utiliser la classe Event avec :</p>
    <ul>
        <li>✓ Encapsulation (attributs privés)</li>
        <li>✓ Constructeur paramétré</li>
        <li>✓ Getters et Setters</li>
        <li>✓ Validation des données</li>
        <li>✓ Méthode show()</li>
        <li>✓ Sauvegarde en base de données</li>
    </ul>
</body>
</html>
