<?php
require_once __DIR__ . '/db.php';

class Evenement {
    private $db;

    public function __construct() {
        $this->db = DB::getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM evenements ORDER BY date DESC');
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM evenements WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare('INSERT INTO evenements (title, description, date, location, capacity, category, created_by, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['date'],
            $data['location'],
            $data['capacity'],
            $data['category'],
            $data['created_by'],
            $data['image'],
            $data['status']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare('UPDATE evenements SET title = ?, description = ?, date = ?, location = ?, capacity = ?, category = ?, image = ?, status = ? WHERE id = ?');
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['date'],
            $data['location'],
            $data['capacity'],
            $data['category'],
            $data['image'],
            $data['status'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM evenements WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
