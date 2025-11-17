// ============================================
// FILE: public/js/signup.js
// ============================================
// Show/hide year field based on user type
document.getElementById('userType').addEventListener('change', function() {
    const yearGroup = document.getElementById('yearGroup');
    if (this.value === 'student') {
        yearGroup.style.display = 'block';
    } else {
        yearGroup.style.display = 'none';
    }
});

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
    } else {
        field.type = 'password';
    }
}

// Form validation
document.getElementById('signupForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const errorMessage = document.getElementById('errorMessage');
    
    if (password !== confirmPassword) {
        e.preventDefault();
        errorMessage.textContent = 'Les mots de passe ne correspondent pas';
        errorMessage.classList.add('show');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        errorMessage.textContent = 'Le mot de passe doit contenir au moins 8 caractères';
        errorMessage.classList.add('show');
        return false;
    }
});


// ============================================
// FILE: controllers/UserController.php (ADD THIS METHOD)
// ============================================
public function register($firstName, $lastName, $email, $studentId, $password, $userType, $phone = null, $year = null) {
    try {
        // Check if email already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Cet email est déjà utilisé'];
        }
        
        // Check if student ID already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE student_id = ?");
        $stmt->execute([$studentId]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Cet ID étudiant est déjà utilisé'];
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $stmt = $this->db->prepare("
            INSERT INTO users (first_name, last_name, email, student_id, password, user_type, phone, year, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $result = $stmt->execute([
            $firstName,
            $lastName,
            $email,
            $studentId,
            $hashedPassword,
            $userType,
            $phone,
            $year
        ]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Compte créé avec succès'];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de la création du compte'];
        }
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
    }
}