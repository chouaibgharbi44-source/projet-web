<?php
session_start();
require_once '../config.php';

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire - CampusConnect</title>
    <style>
        /* =============================================
           CAMPUS CONNECT - DARK SIGNUP FORM
           ============================================= */

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
        }

        body {
            background: #121212;
            color: #e0e0e0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .signup-wrapper {
            display: flex;
            max-width: 1200px;
            width: 100%;
            background: #1a1a1a;
            border-radius: 24px;
            border: 1px solid #2d2d2d;
            overflow: hidden;
            min-height: 600px;
        }

        /* LEFT SIDE - BRANDING */
        .signup-brand {
            flex: 1;
            background: linear-gradient(135deg, #6a1b9a 0%, #8e24aa 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }

        .brand-logo img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .brand-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .brand-features {
            list-style: none;
            text-align: left;
            margin-top: 2rem;
        }

        .brand-features li {
            padding: 0.75rem 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            opacity: 0.95;
        }

        .brand-features li::before {
            content: "✓";
            background: rgba(255, 255, 255, 0.3);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* RIGHT SIDE - FORM */
        .signup-container {
            flex: 1;
            padding: 3rem 2.5rem;
            overflow-y: auto;
            max-height: 90vh;
        }

        .signup-header {
            margin-bottom: 2rem;
        }

        .signup-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .signup-subtitle {
            font-size: 0.95rem;
            color: #aaaaaa;
        }

        .signup-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #e0e0e0;
            font-size: 0.9rem;
        }

        .input-group label .required {
            color: #bb86fc;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 0.9rem 1rem;
            border: 1px solid #333;
            border-radius: 12px;
            background: #1e1e1e;
            color: #e0e0e0;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: #bb86fc;
            box-shadow: 0 0 0 3px rgba(187, 134, 252, 0.2);
        }

        .input-group input::placeholder {
            color: #666666;
        }

        .password-group {
            position: relative;
        }

        .password-group input {
            padding-right: 3rem;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #888888;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0.25rem;
        }

        .toggle-password:hover {
            color: #bb86fc;
        }

        .btn-primary {
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #bb86fc;
            color: #121212;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            background: #9f57eb;
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .signup-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #2d2d2d;
        }

        .signup-footer p {
            color: #aaaaaa;
            font-size: 0.9rem;
        }

        .signup-footer a {
            color: #bb86fc;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .signup-footer a:hover {
            color: #9f57eb;
            text-decoration: underline;
        }

        .error-message,
        .success-message {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            display: none;
            margin-bottom: 1rem;
        }

        .error-message {
            background: rgba(244, 67, 54, 0.1);
            border: 1px solid #f44336;
            color: #ff6b6b;
        }

        .success-message {
            background: rgba(76, 175, 80, 0.1);
            border: 1px solid #4caf50;
            color: #81c784;
        }

        .error-message.show,
        .success-message.show {
            display: block;
        }

        @media (max-width: 992px) {
            .signup-wrapper {
                flex-direction: column;
            }
            
            .signup-brand {
                padding: 2rem;
                min-height: 300px;
            }
            
            .brand-features {
                display: none;
            }
        }

        @media (max-width: 600px) {
            .signup-container {
                padding: 2rem 1.5rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .signup-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="signup-wrapper">
        <!-- LEFT SIDE: BRANDING -->
        <div class="signup-brand">
            <div class="brand-logo">
                <img src="images/logo.png" alt="Logo" onerror="this.style.display='none'; this.parentElement.innerHTML='🎓'">
            </div>
            <h1 class="brand-title">CAMPUS CONNECT</h1>
            <p class="brand-subtitle">Rejoignez la communauté universitaire</p>
            
            <ul class="brand-features">
                <li>Connectez-vous avec vos camarades</li>
                <li>Partagez vos ressources académiques</li>
                <li>Participez aux discussions de cours</li>
                <li>Restez informé des événements campus</li>
            </ul>
        </div>

        <!-- RIGHT SIDE: FORM -->
        <div class="signup-container">
            <header class="signup-header">
                <h2 class="signup-title">Créer un compte</h2>
                <p class="signup-subtitle">Commencez votre aventure universitaire</p>
            </header>

            <div class="error-message" id="errorMessage"></div>
            <div class="success-message" id="successMessage"></div>

            <form class="signup-form" id="signupForm" method="POST" action="">
                <div class="form-row">
                    <div class="input-group">
                        <label for="firstName">Prénom <span class="required">*</span></label>
                        <input type="text" id="firstName" name="firstName" placeholder="Jean" required>
                    </div>

                    <div class="input-group">
                        <label for="lastName">Nom <span class="required">*</span></label>
                        <input type="text" id="lastName" name="lastName" placeholder="Dupont" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Email universitaire <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="email@university.edu" required>
                </div>

                <div class="input-group">
                    <label for="studentId">ID Étudiant <span class="required">*</span></label>
                    <input type="text" id="studentId" name="studentId" placeholder="STU2024001" required>
                </div>

                <div class="input-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" placeholder="+216 XX XXX XXX">
                </div>

                <div class="input-group">
                    <label for="password">Mot de passe <span class="required">*</span></label>
                    <div class="password-group">
                        <input type="password" id="password" name="password" placeholder="Minimum 8 caractères" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">👁️</button>
                    </div>
                </div>

                <div class="input-group">
                    <label for="confirmPassword">Confirmer le mot de passe <span class="required">*</span></label>
                    <div class="password-group">
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Répétez le mot de passe" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">👁️</button>
                    </div>
                </div>

                <div class="input-group">
                    <label for="userType">Type d'utilisateur <span class="required">*</span></label>
                    <select id="userType" name="userType" required>
                        <option value="">Sélectionnez...</option>
                        <option value="student">Étudiant</option>
                        <option value="professor">Professeur</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>

                <div class="input-group" id="yearGroup" style="display:none;">
                    <label for="year">Année d'études</label>
                    <select id="year" name="year">
                        <option value="">Sélectionnez...</option>
                        <option value="1">1ère année</option>
                        <option value="2">2ème année</option>
                        <option value="3">3ème année</option>
                        <option value="4">4ème année</option>
                        <option value="5">5ème année</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary" name="signup">Créer mon compte</button>
            </form>

            <div class="signup-footer">
                <p>Vous avez déjà un compte ? <a href="login.php">Se connecter</a></p>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>

<?php
// Handle form submission
if (isset($_POST['signup'])) {
    require_once '../controllers/UserController.php';
    
    $userController = new UserController();
    
    // Sanitize inputs
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $studentId = trim($_POST['studentId']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $userType = $_POST['userType'];
    $year = isset($_POST['year']) ? $_POST['year'] : null;
    
    // Validation
    $errors = [];
    
    if (empty($firstName) || empty($lastName)) {
        $errors[] = "Le prénom et le nom sont obligatoires";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide";
    }
    
    if (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }
    
    if (empty($errors)) {
        $result = $userController->register($firstName, $lastName, $email, $studentId, $password, $userType, $phone, $year);
        
        if ($result['success']) {
            echo "<script>
                document.getElementById('successMessage').textContent = 'Compte créé avec succès! Redirection...';
                document.getElementById('successMessage').classList.add('show');
                setTimeout(() => { window.location.href = 'login.php'; }, 2000);
            </script>";
        } else {
            echo "<script>
                document.getElementById('errorMessage').textContent = '" . addslashes($result['message']) . "';
                document.getElementById('errorMessage').classList.add('show');
            </script>";
        }
    } else {
        $errorMsg = implode(', ', $errors);
        echo "<script>
            document.getElementById('errorMessage').textContent = '" . addslashes($errorMsg) . "';
            document.getElementById('errorMessage').classList.add('show');
        </script>";
    }
}
?>