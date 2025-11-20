<?php
// model/User.php

class User {
    private $pdo;

    public function __construct() {
        global $pdo; 
        $this->pdo = $pdo;
    }

    /**
     * Creates a new user (for signup). 
     * Fixed to match actual database schema: type, full_name
     */
    public function create($data) {
        $sql = "INSERT INTO users (student_id, full_name, email, password, type, year, department, phone)
                VALUES (:student_id, :full_name, :email, :password, :type, :year, :department, :phone)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function delete($id) {
    $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
}

    /**
     * Retrieves a user record by email.
     */
    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(); 
    }


    public function getById($id)
{
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(); // retourne false si non trouvé
}


public function updateProfile($id, $data)
{
    // Build dynamic SET clause
    $fields = [];
    $values = [];

    $allowed = ['full_name', 'phone', 'department', 'year', 'profile_pic', 'banner'];
    foreach ($allowed as $field) {
        if (isset($data[$field])) {
            $fields[] = "$field = ?";
            $values[] = $data[$field] === '' ? null : $data[$field];
        }
    }

    if (empty($fields)) return false;

    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
    $values[] = $id;

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($values);
}

public function update($id, $data)
{
    $sql = "UPDATE users SET 
            student_id = ?, 
            full_name = ?, 
            email = ?, 
            phone = ?, 
            type = ?, 
            year = ? 
            WHERE id = ?";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        $data['student_id'],
        $data['full_name'],
        $data['email'],
        $data['phone'] ?? '',
        $data['type'],
        $data['year'] ?? null,
        $id
    ]);



    return $stmt->rowCount() > 0;
}

    public function getAll() {
    $stmt = $this->pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}}