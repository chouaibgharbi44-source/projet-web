<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/User.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    
    // UPDATED: Added $department and $interests parameters
    public function register($firstName, $lastName, $email, $studentId, $password, $userType, $phone = null, $year = null, $department = null, $interests = null)
    {
        global $pdo;

        try {
            
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Cet email est déjà utilisé'];
            }

            
            $stmt = $pdo->prepare("SELECT id FROM users WHERE student_id = ?");
            $stmt->execute([$studentId]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Cet ID étudiant est déjà utilisé'];
            }

            
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            
            $fullName = trim($firstName . ' ' . $lastName);

            
            // UPDATED: Added department and interests to the INSERT
            $sql = "INSERT INTO users 
                    (student_id, full_name, email, password, type, phone, year, department, interests, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute([
                $studentId,
                $fullName,
                $email,
                $hashed,
                $userType,    
                $phone,
                $year,
                $department,    // NEW
                $interests      // NEW
            ]);

            if ($success) {
                $userId = $pdo->lastInsertId();
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id']   = $userId;
                $_SESSION['user_name'] = $fullName;
                $_SESSION['user_type'] = $userType;

                return ['success' => true, 'message' => 'Compte créé avec succès !'];
            } else {
                return ['success' => false, 'message' => 'Erreur lors de l\'insertion'];
            }

        } catch (Exception $e) {
            
            return ['success' => false, 'message' => 'Erreur SQL : ' . $e->getMessage()];
        }
    }

    // Tes autres méthodes (list, add, update, delete) restent inchangées
    public function list() { /* ton code existant */ }
    public function add() { /* ton code existant */ }
    public function update() { /* ton code existant */ }
    public function delete() { /* ton code existant */ }

    public function getUserById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        global $pdo; // Ensure you have access to the PDO instance
        $stmt = $pdo->prepare("SELECT * FROM users"); // Adjust the query as needed
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all users as an associative array
    }

    public function getUsersByType($type) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_type = :type");
        $stmt->execute(['type' => $type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}