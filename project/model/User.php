<?php
// model/User.php

class User {
    private $pdo;

    public function __construct() {
        // Accesses the global $pdo instance from config.php
        global $pdo; 
        $this->pdo = $pdo;
    }

    /**
     * Creates a new user (for signup). 
     * NOTE: Ensure column names like 'user_type' match your actual table schema.
     */
    public function create($data) {
        $sql = "INSERT INTO users (student_id, first_name, last_name, email, password, user_type)
                VALUES (:student_id, :first_name, :last_name, :email, :password, :user_type)";
        $stmt = $this->pdo->prepare($sql);
        
        // Ensure keys in $data match SQL parameters (e.g., changing 'user_type' to 'type' if needed)
        // We will assume the data array passed here has the key 'type'
        $stmt->execute($data);
    }

    /**
     * Retrieves a user record by email.
     * CRITICAL FIX: Explicitly fetches as an associative array (though it's the default in config.php, 
     * defining it here ensures reliability across different connection setups).
     */
    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        // The fetch() method here returns a single row.
        return $stmt->fetch(); 
    }
}