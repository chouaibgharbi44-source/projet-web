<?php
/**
 * Fichier de compatibilité `config.php` (legacy)
 * Il wrappe la nouvelle classe `Database` définie dans `config/Database.php`
 * afin d'assurer la compatibilité avec les fichiers qui utilisent
 * la classe legacy `config::getConnexion()`.
 */

require_once __DIR__ . '/config/Database.php';

class config {
    /**
     * Retourne une instance PDO (compatibilité)
     * @return PDO
     */
    public static function getConnexion() {
        $db = Database::getInstance();
        return $db->getConnection();
    }
}

?>
