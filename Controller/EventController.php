<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Model/Event.php';
require_once __DIR__ . '/../Model/Validation.php';

/**
 * Classe EventController
 * Gère la logique métier et la communication entre le modèle et la vue
 */
class EventController {

    /**
     * Affiche les informations d'un événement (par ID)
     */
    public function showEvent($id) {
        try {
            $event = Event::getById($id);
            if ($event) {
                echo "<h2>Détails de l'événement</h2>";
                $event->show();
                var_dump($event->toArray());
            } else {
                echo "<p>Événement non trouvé.</p>";
            }
        } catch (Exception $e) {
            echo "Erreur : " . htmlspecialchars($e->getMessage());
        }
    }

    /**
     * Affiche la liste de tous les événements
     */
    public function listEvents() {
        try {
            $events = Event::getAll();
            return $events;
        } catch (Exception $e) {
            echo "Erreur : " . htmlspecialchars($e->getMessage());
            return [];
        }
    }

    /**
     * Ajoute un nouvel événement (à partir d'un objet Event)
     */
    public function addEvent(Event $event) {
        try {
            $event->save();
            return [
                'success' => true,
                'message' => 'Événement créé avec succès (ID: ' . $event->getId() . ')',
                'event_id' => $event->getId()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la création : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Modifie un événement existant
     */
    public function updateEvent(Event $event) {
        try {
            $event->update();
            return [
                'success' => true,
                'message' => 'Événement modifié avec succès'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la modification : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Supprime un événement par ID
     */
    public function deleteEvent($id) {
        try {
            Event::delete($id);
            return [
                'success' => true,
                'message' => 'Événement supprimé avec succès'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
            ];
        }
    }
}
?>
