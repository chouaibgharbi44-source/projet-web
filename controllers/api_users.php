<?php
// controllers/api/users.php - NEW FILE (don't replace anything)
session_start();
header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit;
}

// Fix the path - try both options
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
} else if (file_exists(__DIR__ . '/../../config.php')) {
    require_once __DIR__ . '/../../config.php';
} else {
    die(json_encode(['success' => false, 'message' => 'config.php not found. Current dir: ' . __DIR__]));
}

require_once __DIR__ . '../UserController.php';

// Initialize your existing controller
$controller = new UserController();

// Get action from request
$action = $_GET['action'] ?? '';

try {
    global $pdo;

    switch ($action) {
        
        case 'getAll':
            // Use your existing method
            $users = $controller->getAllUsers();
            echo json_encode([
                'success' => true,
                'data' => $users
            ]);
            break;

        case 'get':
            // Get single user
            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID manquant']);
                exit;
            }
            
            $user = $controller->getUserById($id);
            
            if ($user) {
                echo json_encode([
                    'success' => true,
                    'data' => $user
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Utilisateur non trouvé'
                ]);
            }
            break;

        case 'create':
            // Create new user
            $studentId = $_POST['student_id'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $userType = $_POST['user_type'] ?? 'student';
            $interests = $_POST['interests'] ?? '';
            $department = $_POST['department'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $year = $_POST['year'] ?? '';

            // Validate
            if (empty($email) || empty($password)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Email et mot de passe requis'
                ]);
                exit;
            }

            // Use your existing register method
            $result = $controller->register(
                $firstName, 
                $lastName, 
                $email, 
                $studentId, 
                $password, 
                $userType, 
                $phone, 
                $year, 
                $department, 
                $interests
            );

            echo json_encode($result);
            break;

        case 'update':
            // Update user
            $id = $_POST['id'] ?? null;
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID manquant']);
                exit;
            }

            $studentId = $_POST['student_id'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $userType = $_POST['user_type'] ?? 'student';
            $interests = $_POST['interests'] ?? '';
            $department = $_POST['department'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $year = $_POST['year'] ?? '';
            $fullName = trim($firstName . ' ' . $lastName);

            // Direct SQL update
            if (!empty($_POST['password'])) {
                $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql = "UPDATE users SET 
                        student_id=?, full_name=?, email=?, password=?, 
                        type=?, interests=?, department=?, phone=?, year=? 
                        WHERE id=?";
                $params = [$studentId, $fullName, $email, $hashedPassword, 
                          $userType, $interests, $department, $phone, $year, $id];
            } else {
                $sql = "UPDATE users SET 
                        student_id=?, full_name=?, email=?, 
                        type=?, interests=?, department=?, phone=?, year=? 
                        WHERE id=?";
                $params = [$studentId, $fullName, $email, 
                          $userType, $interests, $department, $phone, $year, $id];
            }

            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute($params);
            
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Utilisateur mis à jour' : 'Erreur mise à jour'
            ]);
            break;

        case 'delete':
            // Delete user
            $id = $_POST['id'] ?? null;
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID manquant']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $success = $stmt->execute([$id]);
            
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Utilisateur supprimé' : 'Erreur suppression'
            ]);
            break;

        default:
            echo json_encode([
                'success' => false,
                'message' => 'Action non reconnue: ' . $action
            ]);
            break;
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur DB: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
?>