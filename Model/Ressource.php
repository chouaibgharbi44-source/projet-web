<?php
require_once __DIR__ . '/db.php';

class Ressource {
    private $db;

    public function __construct() {
        $this->db = DB::getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM ressource ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM ressource WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare('INSERT INTO ressource (titre, description, type_ressource, url, auteur, date_ajout) VALUES (?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['titre'],
            $data['description'],
            $data['type_ressource'],
            $data['url'],
            $data['auteur'],
            $data['date_ajout']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare('UPDATE ressource SET titre = ?, description = ?, type_ressource = ?, url = ?, auteur = ?, date_ajout = ? WHERE id = ?');
        return $stmt->execute([
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
}
