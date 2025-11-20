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

    
    public function register($firstName, $lastName, $email, $studentId, $password, $userType, $phone = null, $year = null)
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

            
            $sql = "INSERT INTO users 
                    (student_id, full_name, email, password, type, phone, year, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute([
                $studentId,
                $fullName,
                $email,
                $hashed,
                $userType,    
                $phone,
                $year
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
}