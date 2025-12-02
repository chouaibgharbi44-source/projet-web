<?php
require_once __DIR__ . '/db.php';

class Ressource {
    private $db;

    public function __construct() {
        $this->db = DB::getConnection();
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
        $stmt = $this->db->prepare('INSERT INTO ressource (matiere_id, titre, description, type_ressource, url, auteur, date_ajout) VALUES (?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['matiere_id'],
            $data['titre'],
            $data['description'],
            $data['type_ressource'],
            $data['url'],
            $data['auteur'],
            $data['date_ajout']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare('UPDATE ressource SET matiere_id = ?, titre = ?, description = ?, type_ressource = ?, url = ?, auteur = ?, date_ajout = ? WHERE id = ?');
        return $stmt->execute([
            $data['matiere_id'],
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

    public function countByMatiere() {
        $stmt = $this->db->query('SELECT m.nom_matiere AS label, COUNT(r.id) AS total FROM matiere m LEFT JOIN ressource r ON r.matiere_id = m.id GROUP BY m.id ORDER BY total DESC');
        return $stmt->fetchAll();
    }

    public function countByType() {
        $stmt = $this->db->query('SELECT IFNULL(type_ressource, "Autre") AS label, COUNT(*) AS total FROM ressource GROUP BY type_ressource ORDER BY total DESC');
        return $stmt->fetchAll();
    }
}
