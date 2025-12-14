<?php
require_once __DIR__ . '/db.php';

class Reservation {
    private $db;

    public function __construct() {
        $this->db = DB::getConnection();
    }

    public function getAll() {
        // include event title for convenience in the admin list
        $sql = 'SELECT r.*, e.title AS event_title FROM reservations r LEFT JOIN evenements e ON r.event_id = e.id ORDER BY r.created_at DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM reservations WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare('INSERT INTO reservations (event_id, user_id, name, email, seats, status) VALUES (?, ?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['event_id'] ?? null,
            $data['user_id'] ?? null,
            $data['name'] ?? '',
            $data['email'] ?? '',
            $data['seats'] ?? 1,
            $data['status'] ?? 'pending'
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare('UPDATE reservations SET event_id = ?, user_id = ?, name = ?, email = ?, seats = ?, status = ? WHERE id = ?');
        return $stmt->execute([
            $data['event_id'] ?? null,
            $data['user_id'] ?? null,
            $data['name'] ?? '',
            $data['email'] ?? '',
            $data['seats'] ?? 1,
            $data['status'] ?? 'pending',
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM reservations WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
