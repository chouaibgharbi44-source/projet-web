<?php
require_once __DIR__ . '/db.php';

class Ressource {
    private $db;

    public function __construct() {
        $this->db = DB::getConnection();
    }

    public function getPdo() {
        return $this->db;
    }

    public function getAll() {
        $sql = 'SELECT r.*, m.nom_matiere FROM ressource r INNER JOIN matiere m ON m.id = r.matiere_id ORDER BY r.id DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT r.*, m.nom_matiere FROM ressource r INNER JOIN matiere m ON m.id = r.matiere_id WHERE r.id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getByMatiere($matiereId) {
        $stmt = $this->db->prepare('SELECT r.*, m.nom_matiere FROM ressource r INNER JOIN matiere m ON m.id = r.matiere_id WHERE r.matiere_id = ? ORDER BY r.date_ajout DESC');
        $stmt->execute([$matiereId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare('
            INSERT INTO ressource (matiere_id, user_id, titre, description, type_ressource, url, auteur, date_ajout)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        return $stmt->execute([
            $data['matiere_id'],
            $data['user_id'], // ← doit être présent
            $data['titre'],
            $data['description'],
            $data['type_ressource'],
            $data['url'],
            $data['auteur'],
            $data['date_ajout']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare('
            UPDATE ressource 
            SET matiere_id = ?, user_id = ?, titre = ?, description = ?, type_ressource = ?, url = ?, auteur = ?, date_ajout = ? 
            WHERE id = ?
        ');
        return $stmt->execute([
            $data['matiere_id'],
            $data['user_id'] ?? 1,
            $data['titre'],
            $data['description'],
            $data['type_ressource'],
            $data['url'],
            $data['auteur'],
            $data['date_ajout'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM ressource WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function countByType() {
        $stmt = $this->db->query("
            SELECT 
                COALESCE(NULLIF(TRIM(type_ressource), ''), 'Autre') AS label,
                COUNT(*) AS total
            FROM ressource
            WHERE type_ressource IS NOT NULL
            GROUP BY label
            ORDER BY total DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopAuteurs() {
        $stmt = $this->db->query("
            SELECT 
                auteur AS label,
                COUNT(*) AS total
            FROM ressource
            WHERE auteur IS NOT NULL AND TRIM(auteur) != ''
            GROUP BY auteur
            ORDER BY total DESC
            LIMIT 5
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countByMatiere() {
        $stmt = $this->db->query("
            SELECT 
                m.id AS matiere_id,
                m.nom_matiere AS label,
                COUNT(r.id) AS total
            FROM matiere m
            INNER JOIN ressource r ON r.matiere_id = m.id
            GROUP BY m.id, m.nom_matiere
            ORDER BY total DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ========== FAVORIS ==========
    public function ajouterFavori($ressource_id, $user_id = 1) {
        $stmt = $this->db->prepare("INSERT IGNORE INTO favoris (ressource_id, user_id) VALUES (?, ?)");
        return $stmt->execute([$ressource_id, $user_id]);
    }

    public function retirerFavori($ressource_id, $user_id = 1) {
        $stmt = $this->db->prepare("DELETE FROM favoris WHERE ressource_id = ? AND user_id = ?");
        return $stmt->execute([$ressource_id, $user_id]);
    }

    public function estFavori($ressource_id, $user_id = 1) {
        $stmt = $this->db->prepare("SELECT 1 FROM favoris WHERE ressource_id = ? AND user_id = ?");
        $stmt->execute([$ressource_id, $user_id]);
        return $stmt->fetch() !== false;
    }

    public function getNbFavoris($ressource_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favoris WHERE ressource_id = ?");
        $stmt->execute([$ressource_id]);
        return (int)$stmt->fetchColumn();
    }

    public function getAllAvecFavoris() {
        $sql = "
            SELECT r.*, m.nom_matiere,
                (SELECT COUNT(*) FROM favoris f WHERE f.ressource_id = r.id) AS nb_favoris
            FROM ressource r
            INNER JOIN matiere m ON m.id = r.matiere_id
            ORDER BY r.id DESC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getFavorisParUser($user_id = 1) {
        $sql = "
            SELECT r.*, m.nom_matiere,
                (SELECT COUNT(*) FROM favoris f WHERE f.ressource_id = r.id) AS nb_favoris
            FROM ressource r
            INNER JOIN favoris f ON f.ressource_id = r.id
            INNER JOIN matiere m ON m.id = r.matiere_id
            WHERE f.user_id = ?
            ORDER BY f.created_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // ========== TÉLÉCHARGEMENTS ==========
    public function ajouterTelechargement($ressource_id, $user_id = 1) {
        $stmt = $this->db->prepare("SELECT 1 FROM telechargements WHERE ressource_id = ? AND user_id = ?");
        $stmt->execute([$ressource_id, $user_id]);
        if ($stmt->fetch()) return false;

        $stmt = $this->db->prepare("INSERT INTO telechargements (ressource_id, user_id) VALUES (?, ?)");
        return $stmt->execute([$ressource_id, $user_id]);
    }

    public function getTelechargementsParUser($user_id = 1) {
        $sql = "
            SELECT r.*, m.nom_matiere,
                (SELECT COUNT(*) FROM telechargements t2 WHERE t2.ressource_id = r.id) AS nb_telechargements
            FROM ressource r
            INNER JOIN telechargements t ON t.ressource_id = r.id
            INNER JOIN matiere m ON m.id = r.matiere_id
            WHERE t.user_id = ?
            ORDER BY t.created_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getAllTelechargements() {
        $sql = "
            SELECT t.id, t.created_at, t.user_id, r.titre, r.auteur, m.nom_matiere
            FROM telechargements t
            INNER JOIN ressource r ON r.id = t.ressource_id
            INNER JOIN matiere m ON m.id = r.matiere_id
            ORDER BY t.created_at DESC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // ✅ MÉTHODE AJOUTÉE : Partages par utilisateur
    public function getPartagesParUser($user_id = 1) {
        $sql = "
            SELECT r.*, m.nom_matiere
            FROM ressource r
            INNER JOIN matiere m ON m.id = r.matiere_id
            WHERE r.user_id = ?
            ORDER BY r.date_ajout DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
    public function getRessourcesSimilaires($ressource_id, $titre, $description, $limit = 3) {
        // Extraire des mots-clés du titre et de la description
        $mots = array_merge(
            explode(' ', strtolower($titre)),
            explode(' ', strtolower($description))
        );
        
        // Nettoyer les mots (supprimer ponctuation, mots courts)
        $mots = array_filter($mots, function($mot) {
            return strlen($mot) > 2 && !is_numeric($mot);
        });
        
        // Supprimer les doublons
        $mots = array_unique($mots);
        
        // Limiter à 5 mots-clés
        $mots = array_slice($mots, 0, 5);
        
        if (empty($mots)) {
            // Si pas de mots, retourner les dernières ressources
            $sql = "SELECT r.*, m.nom_matiere 
                    FROM ressource r 
                    INNER JOIN matiere m ON m.id = r.matiere_id 
                    WHERE r.id != ? 
                    ORDER BY r.date_ajout DESC 
                    LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ressource_id, $limit]);
            return $stmt->fetchAll();
        }

        // Construire la requête avec LIKE
        $conditions = [];
        $params = [];
        foreach ($mots as $mot) {
            $conditions[] = "(r.titre LIKE ? OR r.description LIKE ?)";
            $params[] = "%$mot%";
            $params[] = "%$mot%";
        }
        
        $sql = "SELECT r.*, m.nom_matiere 
                FROM ressource r 
                INNER JOIN matiere m ON m.id = r.matiere_id 
                WHERE r.id != ? AND (" . implode(' OR ', $conditions) . ")
                ORDER BY r.date_ajout DESC 
                LIMIT ?";
        $params = array_merge([$ressource_id], $params, [$limit]);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    // Compter les téléchargements totaux
    public function countTotalDownloads() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM telechargements");
        return $stmt->fetchColumn();
    }

    // Compter les favoris totaux
    public function countTotalFavorites() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM favoris");
        return $stmt->fetchColumn();
    }

    // Compter les téléchargements par ressource (top 5)
    // ✅ CORRIGÉ : Nom de la table = "telechargements" (pas "telechargement")
    public function getTopDownloads($limit = 5) {
        $sql = "
            SELECT r.id, r.titre, r.auteur, m.nom_matiere, COUNT(t.id) as nb_telechargements
            FROM ressource r
            INNER JOIN telechargements t ON t.ressource_id = r.id
            INNER JOIN matiere m ON m.id = r.matiere_id
            GROUP BY r.id
            ORDER BY nb_telechargements DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ CORRIGÉ : Nom de la table = "favoris" (pas "favourite")
    public function getTopFavorites($limit = 5) {
        $sql = "
            SELECT r.id, r.titre, r.auteur, m.nom_matiere, COUNT(f.id) as nb_favoris
            FROM ressource r
            INNER JOIN favoris f ON f.ressource_id = r.id
            INNER JOIN matiere m ON m.id = r.matiere_id
            GROUP BY r.id
            ORDER BY nb_favoris DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    }