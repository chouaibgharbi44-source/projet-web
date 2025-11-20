 
document.getElementById('userType').addEventListener('change', function() {
    const yearGroup = document.getElementById('yearGroup');
    if (this.value === 'student') {
        yearGroup.style.display = 'block';
    } else {
        yearGroup.style.display = 'none';
    }
});

 
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
    } else {
        field.type = 'password';
    }
}

document.getElementById('signupForm').addEventListener('submit', function(e) {

    const fullName = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const errorMessage = document.getElementById('errorMessage');

    
    errorMessage.style.display = "none";
    errorMessage.textContent = "";

    
    if (!fullName || !email || !password || !confirmPassword) {
        e.preventDefault();
        errorMessage.textContent = "Veuillez remplir tous les champs.";
        errorMessage.style.display = "block";
        return false;
    }

    
    const nameRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ](?:[A-Za-zÀ-ÖØ-öø-ÿ\s'-]*[A-Za-zÀ-ÖØ-öø-ÿ])?$/;

    if (!nameRegex.test(fullName)) {
        e.preventDefault();
        errorMessage.textContent = "Le nom ne doit contenir que des lettres (pas de chiffres).";
        errorMessage.style.display = "block";
    return false;
    }   

    
    if (fullName.replace(/\s/g, '').length === 0) {
        e.preventDefault();
        errorMessage.textContent = "Le nom est invalide.";
        errorMessage.style.display = "block";
        return false;
    }

    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        errorMessage.textContent = "Adresse email invalide.";
        errorMessage.style.display = "block";
        return false;
    }

    
    if (password.length < 8) {
        e.preventDefault();
        errorMessage.textContent = "Le mot de passe doit contenir au moins 8 caractères.";
        errorMessage.style.display = "block";
        return false;
    }

    
    const strongPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!strongPass.test(password)) {
        e.preventDefault();
        errorMessage.textContent = "Le mot de passe doit contenir une majuscule, une minuscule et un chiffre.";
        errorMessage.style.display = "block";
        return false;
    }

    
    if (password !== confirmPassword) {
        e.preventDefault();
        errorMessage.textContent = "Les mots de passe ne correspondent pas.";
        errorMessage.style.display = "block";
        return false;
    }

});


 
public function register($firstName, $lastName, $email, $studentId, $password, $userType, $phone = null, $year = null) {
    try {
         
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Cet email est déjà utilisé'];
        }
        
         
        $stmt = $this->db->prepare("SELECT id FROM users WHERE student_id = ?");
        $stmt->execute([$studentId]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Cet ID étudiant est déjà utilisé'];
        }
        
         
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
         
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