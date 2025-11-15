<?php
/**
 * Database.php - Classe PDO pour la connexion à la base de données
 * Gère la connexion centralisée et les requêtes via PDO
 */

class Database {
    private static $instance = null;
    private $conn;
    private $host = 'localhost';
    private $db_name = 'peaceconnect';
    private $user = 'Projet2A';
    private $password = '123';
    private $charset = 'utf8mb4';

    /**
     * Constructeur privé (Singleton)
     */
    private function __construct() {
        $this->connect();
    }

    /**
     * Retourne l'instance unique de la connexion
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Établit la connexion PDO
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            $this->conn = new PDO($dsn, $this->user, $this->password);
            
            // Configuration PDO
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données: ' . $e->getMessage());
        }
    }

    /**
     * Retourne la connexion PDO
     */
    public function getConnection() {
        return $this->conn;
    }

    /**
     * Prépare et exécute une requête
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die('Erreur lors de l\'exécution de la requête: ' . $e->getMessage());
        }
    }

    /**
     * Récupère une ligne
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Récupère plusieurs lignes
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->execute($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Insère un enregistrement et retourne l'ID
     */
    public function insert($sql, $params = []) {
        $this->execute($sql, $params);
        return $this->conn->lastInsertId();
    }

    /**
     * Désactiver le clonage
     */
    private function __clone() {}

    /**
     * Désactiver la sérialisation
     */
    public function __sleep() {
        return [];
    }
}

?>
