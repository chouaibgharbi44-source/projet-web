<?php
/**
 * Classe Validation pour valider tous les attributs d'un événement
 */
class Validation {
    
    /**
     * Vérifie si une chaîne est vide
     */
    public static function isEmpty($value) {
        return empty(trim($value));
    }

    /**
     * Vérifie que le titre n'est pas vide et a une longueur entre 3 et 100 caractères
     */
    public static function validateTitle($title) {
        if (self::isEmpty($title)) {
            return "Le titre est obligatoire.";
        }
        if (strlen($title) < 3) {
            return "Le titre doit contenir au moins 3 caractères.";
        }
        if (strlen($title) > 100) {
            return "Le titre ne doit pas dépasser 100 caractères.";
        }
        return true;
    }

    /**
     * Vérifie que la description n'est pas vide et a une longueur minimale
     */
    public static function validateDescription($description) {
        if (self::isEmpty($description)) {
            return "La description est obligatoire.";
        }
        if (strlen($description) < 10) {
            return "La description doit contenir au moins 10 caractères.";
        }
        if (strlen($description) > 5000) {
            return "La description ne doit pas dépasser 5000 caractères.";
        }
        return true;
    }

    /**
     * Vérifie que la date est valide et dans le futur
     */
    public static function validateDate($date) {
        // La date est optionnelle : si vide, on accepte
        if (self::isEmpty($date)) {
            return true;
        }
        // Accept multiple formats: date-only, datetime (space), html5 datetime-local (T)
        $formats = ['Y-m-d\TH:i', 'Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d'];
        $dateObj = false;
        foreach ($formats as $fmt) {
            $d = DateTime::createFromFormat($fmt, $date);
            if ($d !== false) {
                $dateObj = $d;
                break;
            }
        }

        if (!$dateObj) {
            return "Le format de la date est invalide (ex: YYYY-MM-DD ou YYYY-MM-DDTHH:MM).";
        }

        $today = new DateTime();
        if ($dateObj < $today) {
            return "La date ne peut pas être dans le passé.";
        }

        return true;
    }

    /**
     * Vérifie que le lieu n'est pas vide
     */
    public static function validateLocation($location) {
        if (self::isEmpty($location)) {
            return "Le lieu est obligatoire.";
        }
        if (strlen($location) < 3) {
            return "Le lieu doit contenir au moins 3 caractères.";
        }
        if (strlen($location) > 100) {
            return "Le lieu ne doit pas dépasser 100 caractères.";
        }
        return true;
    }

    /**
     * Vérifie que la capacité est un nombre positif
     */
    public static function validateCapacity($capacity) {
        // Capacité optionnelle : si vide, on accepte
        if (self::isEmpty($capacity)) {
            return true;
        }
        if (!is_numeric($capacity)) {
            return "La capacité doit être un nombre.";
        }
        if ($capacity < 1) {
            return "La capacité doit être supérieure à 0.";
        }
        if ($capacity > 10000) {
            return "La capacité ne doit pas dépasser 10000.";
        }
        return true;
    }

    /**
     * Vérifie que la catégorie est sélectionnée
     */
    public static function validateCategory($category) {
        $validCategories = ['Conférence', 'Atelier', 'Festival', 'Réunion', 'Sport', 'Culturel', 'Autre'];
        if (self::isEmpty($category)) {
            return "La catégorie est obligatoire.";
        }
        if (!in_array($category, $validCategories)) {
            return "La catégorie sélectionnée est invalide.";
        }
        return true;
    }

    /**
     * Valide tous les attributs d'un événement
     */
    public static function validateEvent($data) {
        $errors = [];

        if (!isset($data['title']) || ($result = self::validateTitle($data['title'])) !== true) {
            $errors['title'] = $result;
        }

        if (!isset($data['description']) || ($result = self::validateDescription($data['description'])) !== true) {
            $errors['description'] = $result;
        }

        if (!isset($data['date']) || ($result = self::validateDate($data['date'])) !== true) {
            $errors['date'] = $result;
        }

        if (!isset($data['location']) || ($result = self::validateLocation($data['location'])) !== true) {
            $errors['location'] = $result;
        }

        if (!isset($data['capacity']) || ($result = self::validateCapacity($data['capacity'])) !== true) {
            $errors['capacity'] = $result;
        }

        // Si la catégorie n'est pas fournie, on utilise 'Autre' par défaut
        $categoryValue = isset($data['category']) && !self::isEmpty($data['category']) ? $data['category'] : 'Autre';
        if (($result = self::validateCategory($categoryValue)) !== true) {
            $errors['category'] = $result;
        }

        return empty($errors) ? true : $errors;
    }
}
?>
