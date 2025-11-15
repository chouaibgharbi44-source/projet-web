<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Validation.php';

/**
 * Classe Event - Représente un événement
 * Encapsulation : attributs privés avec getters/setters
 */
class Event {
    
    private $id;
    private $title;
    private $description;
    private $date;
    private $location;
    private $capacity;
    private $category;
    private $image;
    private $created_by;
    private $created_at;

    /**
     * Constructeur paramétré
     */
    public function __construct($title = null, $description = null, $date = null, $location = null, $capacity = null, $category = null, $image = null, $created_by = null) {
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->location = $location;
        $this->capacity = $capacity;
        $this->category = $category;
        $this->image = $image;
        $this->created_by = $created_by;
        $this->created_at = date('Y-m-d H:i:s');
    }

    // ========== GETTERS ==========

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDate() {
        return $this->date;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getCapacity() {
        return $this->capacity;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getImage() {
        return $this->image;
    }

    public function getCreatedBy() {
        return $this->created_by;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    // ========== SETTERS ==========

    public function setTitle($title) {
        $validation = Validation::validateTitle($title);
        if ($validation !== true) {
            throw new Exception($validation);
        }
        $this->title = $title;
    }

    public function setDescription($description) {
        $validation = Validation::validateDescription($description);
        if ($validation !== true) {
            throw new Exception($validation);
        }
        $this->description = $description;
    }

    public function setDate($date) {
        $validation = Validation::validateDate($date);
        if ($validation !== true) {
            throw new Exception($validation);
        }

        // Normaliser le format pour MySQL DATETIME : 'Y-m-d H:i:s'
        // Accepte les formats HTML5 'Y-m-dTH:i', 'Y-m-d H:i' ou 'Y-m-d H:i:s' et 'Y-m-d'
        $normalized = str_replace('T', ' ', $date);
        // Si on a seulement 'Y-m-d', ajouter minuit
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $normalized)) {
            $normalized .= ' 00:00:00';
        }
        // Si on a 'Y-m-d H:i' (pas de secondes), ajouter ':00'
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $normalized)) {
            $normalized .= ':00';
        }

        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $normalized);
        if ($dt === false) {
            // Fallback: essayer créer via DateTime générique
            try {
                $dt = new DateTime($date);
            } catch (Exception $e) {
                throw new Exception('Impossible de parser la date fournie.');
            }
        }

        $this->date = $dt->format('Y-m-d H:i:s');
    }

    public function setLocation($location) {
        $validation = Validation::validateLocation($location);
        if ($validation !== true) {
            throw new Exception($validation);
        }
        $this->location = $location;
    }

    public function setCapacity($capacity) {
        $validation = Validation::validateCapacity($capacity);
        if ($validation !== true) {
            throw new Exception($validation);
        }
        $this->capacity = (int)$capacity;
    }

    public function setCategory($category) {
        $validation = Validation::validateCategory($category);
        if ($validation !== true) {
            throw new Exception($validation);
        }
        $this->category = $category;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setCreatedBy($created_by) {
        $this->created_by = $created_by;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }

    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Méthode show() - Affiche les informations de l'événement
     */
    public function show() {
        echo "<div class='event-info' style='border: 1px solid #ddd; padding: 15px; margin: 10px 0; background: #f9f9f9;'>";
        echo "<h3>" . htmlspecialchars($this->title) . "</h3>";
        echo "<p><strong>Description :</strong> " . htmlspecialchars($this->description) . "</p>";
        echo "<p><strong>Date :</strong> " . htmlspecialchars($this->date) . "</p>";
        echo "<p><strong>Lieu :</strong> " . htmlspecialchars($this->location) . "</p>";
        echo "<p><strong>Capacité :</strong> " . htmlspecialchars($this->capacity) . " personnes</p>";
        echo "<p><strong>Catégorie :</strong> " . htmlspecialchars($this->category) . "</p>";
        echo "<p><strong>Créé le :</strong> " . htmlspecialchars($this->created_at) . "</p>";
        echo "</div>";
    }

    /**
     * Convertit l'objet en tableau associatif
     */
    public function toArray() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'location' => $this->location,
            'capacity' => $this->capacity,
            'category' => $this->category,
            'image' => $this->image,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at
        ];
    }

    /**
     * CRUD : Ajouter un événement à la base de données
     */
    public function save() {
        try {
            $db = config::getConnexion();
            
            $sql = "INSERT INTO evenements (title, description, date, location, capacity, category, image, created_by, created_at) 
                    VALUES (:title, :description, :date, :location, :capacity, :category, :image, :created_by, :created_at)";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':title' => $this->title,
                ':description' => $this->description,
                ':date' => $this->date,
                ':location' => $this->location,
                ':capacity' => $this->capacity,
                ':category' => $this->category,
                ':image' => $this->image,
                ':created_by' => $this->created_by,
                ':created_at' => $this->created_at
            ]);

            $this->id = $db->lastInsertId();
            return true;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'ajout : " . $e->getMessage());
        }
    }

    /**
     * CRUD : Récupérer un événement par ID
     */
    public static function getById($id) {
        try {
            $db = config::getConnexion();
            $sql = "SELECT * FROM evenements WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();

            if ($row) {
                $event = new Event();
                $event->setId($row['id']);
                $event->setTitle($row['title']);
                $event->setDescription($row['description']);
                $event->setDate($row['date']);
                $event->setLocation($row['location']);
                $event->setCapacity($row['capacity']);
                $event->setCategory(isset($row['category']) ? $row['category'] : null);
                $event->setImage($row['image']);
                $event->setCreatedBy($row['created_by']);
                $event->setCreatedAt($row['created_at']);
                return $event;
            }
            return null;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération : " . $e->getMessage());
        }
    }

    /**
     * CRUD : Récupérer tous les événements
     */
    public static function getAll($filter = 'all', $search = '') {
        try {
            $db = config::getConnexion();
            $sql = "SELECT * FROM evenements ORDER BY date DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();

            $events = [];
            foreach ($rows as $row) {
                $event = new Event();
                $event->setId($row['id']);
                $event->setTitle($row['title']);
                $event->setDescription($row['description']);
                $event->setDate($row['date']);
                $event->setLocation($row['location']);
                $event->setCapacity($row['capacity']);
                $event->setCategory(isset($row['category']) ? $row['category'] : null);
                $event->setImage($row['image']);
                $event->setCreatedBy($row['created_by']);
                $event->setCreatedAt($row['created_at']);
                $events[] = $event;
            }
            return $events;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération : " . $e->getMessage());
        }
    }

    /**
     * Compatibilité : créer un événement depuis un tableau de données
     * Utilisé par le contrôleur legacy qui appelle $eventModel->create(
     */
    public function create($data) {
        // Utiliser les setters pour valider et normaliser
        if (isset($data['title'])) $this->setTitle($data['title']);
        if (isset($data['description'])) $this->setDescription($data['description']);
        // Supporte date + time séparés depuis les formulaires
        if (isset($data['date'])) {
            $dateVal = $data['date'];
            if (isset($data['time']) && !empty(trim($data['time']))) {
                // Normaliser time HH:MM en HH:MM
                $timeVal = trim($data['time']);
                $dateVal = $dateVal . 'T' . $timeVal; // sera transformé par setDate
            }
            $this->setDate($dateVal);
        }
        if (isset($data['location'])) $this->setLocation($data['location']);
    if (!empty($data['capacity'])) $this->setCapacity($data['capacity']);
    $category = isset($data['category']) ? $data['category'] : null;
    if (!empty($category)) {
            $this->setCategory($category);
        } else {
            // Valeur par défaut si non fournie
            $this->setCategory('Autre');
        }
        if (!empty($data['image'])) $this->setImage($data['image']);

        // created_by may come from session / form
        if (!empty($data['created_by'])) {
            $this->setCreatedBy($data['created_by']);
        } else {
            // Par défaut en local, attribuer l'ID 1 (administrateur) pour éviter les erreurs NOT NULL
            $this->setCreatedBy(1);
        }

        return $this->save();
    }

    /**
     * CRUD : Modifier un événement
     */
    public function update() {
        try {
            $db = config::getConnexion();
            $sql = "UPDATE evenements 
                    SET title = :title, description = :description, date = :date, 
                        location = :location, capacity = :capacity, category = :category, image = :image
                    WHERE id = :id";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':id' => $this->id,
                ':title' => $this->title,
                ':description' => $this->description,
                ':date' => $this->date,
                ':location' => $this->location,
                ':capacity' => $this->capacity,
                ':category' => $this->category,
                ':image' => $this->image
            ]);

            return true;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la modification : " . $e->getMessage());
        }
    }

    /**
     * CRUD : Supprimer un événement
     */
    public static function delete($id) {
        try {
            $db = config::getConnexion();
            
            // Supprimer d'abord les participants
            $sql = "DELETE FROM participants WHERE event_id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);

            // Puis supprimer l'événement
            $sql = "DELETE FROM evenements WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);

            return true;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression : " . $e->getMessage());
        }
    }
}
?>
