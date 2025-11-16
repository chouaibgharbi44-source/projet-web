<?php
require_once __DIR__ . '/db.php';

class Matiere {
    private $db;

    public function __construct() {
        $this->db = DB::getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM matiere ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM matiere WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare('INSERT INTO matiere (nom_matiere, titre, description, date_ajout, niveau_difficulte) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['nom_matiere'],
            $data['titre'],
            $data['description'],
            $data['date_ajout'],
            $data['niveau_difficulte']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare('UPDATE matiere SET nom_matiere = ?, titre = ?, description = ?, date_ajout = ?, niveau_difficulte = ? WHERE id = ?');
        return $stmt->execute([
            $data['nom_matiere'],
            $data['titre'],
            $data['description'],
            $data['date_ajout'],
            $data['niveau_difficulte'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM matiere WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
