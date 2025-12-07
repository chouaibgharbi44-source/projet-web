<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once '../config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire - CampusConnect</title>
    <link rel="stylesheet" href="css/style_signup.css">
</head>
<body>
    <div class="signup-wrapper">
        
        <div class="signup-brand">
            <div class="brand-logo">
                <img src="images/logo.png" alt="Logo" onerror="this.style.display='none'; this.parentElement.innerHTML='Graduation Cap'">
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
                        <input type="text" id="firstName" name="firstName" placeholder="Jean">
                    </div>

                    <div class="input-group">
                        <label for="lastName">Nom <span class="required">*</span></label>
                        <input type="text" id="lastName" name="lastName" placeholder="Dupont">
                    </div>
                </div>

                <div class="input-group">
                    <label for="email">Email universitaire <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="email@university.edu">
                </div>

                <div class="input-group">
                    <label for="studentId">ID Étudiant <span class="required">*</span></label>
                    <input type="text" id="studentId" name="studentId" placeholder="STU2024001">
                </div>

                <div class="input-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" placeholder="+216 XX XXX XXX">
                </div>

                <div class="input-group">
                    <label for="password">Mot de passe <span class="required">*</span></label>
                    <div class="password-group">
                        <input type="password" id="password" name="password" placeholder="Minimum 8 caractères">
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">Eye</button>
                    </div>
                    <div id="password-strength" class="password-strength"></div>
                </div>

                <div class="input-group">
                    <label for="confirmPassword">Confirmer le mot de passe <span class="required">*</span></label>
                    <div class="password-group">
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Répétez le mot de passe">
                        <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">Eye</button>
                    </div>
                </div>

                <div class="input-group">
                    <label for="userType">Type d'utilisateur <span class="required">*</span></label>
                    <select id="userType" name="userType">
                        <option value="">Sélectionnez...</option>
                        <option value="student">Étudiant</option>
                        <option value="professor">Professeur</option>
                    </select>
                </div>

                <!-- NEW: Department Field -->
                <div class="input-group">
                    <label for="department">Département</label>
                    <select id="department" name="department">
                        <option value="">Sélectionnez...</option>
                        <option value="Informatique">Informatique</option>
                        <option value="Mathématiques">Mathématiques</option>
                        <option value="Physique">Physique</option>
                        <option value="Chimie">Chimie</option>
                        <option value="Biologie">Biologie</option>
                        <option value="Économie">Économie</option>
                        <option value="Gestion">Gestion</option>
                        <option value="Lettres">Lettres</option>
                        <option value="Sciences Humaines">Sciences Humaines</option>
                        <option value="Ingénierie">Ingénierie</option>
                        <option value="Médecine">Médecine</option>
                        <option value="Droit">Droit</option>
                        <option value="Architecture">Architecture</option>
                        <option value="Arts">Arts</option>
                    </select>
                </div>

                <div class="input-group" id="yearGroup" style="display: none;">
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

                <!-- NEW: Interests Field -->
                <div class="input-group">
                    <label>Centres d'intérêt</label>
                    <p style="font-size: 0.85rem; color: #999; margin: 4px 0 8px 0;">Sélectionnez vos domaines d'intérêt (optionnel)</p>
                    <div class="interests-container">
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Programmation">
                            <span>Programmation</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Intelligence Artificielle">
                            <span>Intelligence Artificielle</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Cybersécurité">
                            <span>Cybersécurité</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Data Science">
                            <span>Data Science</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Développement Web">
                            <span>Développement Web</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Mobile">
                            <span>Mobile</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Design">
                            <span>Design</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Robotique">
                            <span>Robotique</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Réseaux">
                            <span>Réseaux</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Cloud Computing">
                            <span>Cloud Computing</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="Blockchain">
                            <span>Blockchain</span>
                        </label>
                        <label class="interest-tag">
                            <input type="checkbox" name="interests[]" value="IoT">
                            <span>IoT</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-primary" name="signup">Créer mon compte</button>
            </form>

            <div class="signup-footer">
                <p>Vous avez déjà un compte ? <a href="login.php">Se connecter</a></p>
            </div>
        </div>
    </div>

    <script src="js/signup.js"></script>
    <script>
        // Toggle interest tag selection
        document.querySelectorAll('.interest-tag').forEach(tag => {
            tag.addEventListener('click', function() {
                this.classList.toggle('selected');
            });
        });
    </script>
</body>
</html>

<?php
if (isset($_POST['signup'])) {
    require_once '../controllers/UserController.php';
    
    $userController = new UserController();
    
    $firstName  = trim($_POST['firstName'] ?? '');
    $lastName   = trim($_POST['lastName'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $studentId  = trim($_POST['studentId'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $userType   = $_POST['userType'] ?? '';
    $phone      = trim($_POST['phone'] ?? '');
    $year       = ($userType === 'student') ? ($_POST['year'] ?? null) : null;
    
    // NEW: Get department and interests
    $department = trim($_POST['department'] ?? '');
    $interests  = isset($_POST['interests']) ? $_POST['interests'] : [];
    $interestsString = !empty($interests) ? implode(',', $interests) : '';

    $errorMsg = '';
    if (empty($firstName) || empty($lastName) || empty($email) || empty($studentId) || empty($password) || empty($userType)) {
        $errorMsg = "Tous les champs obligatoires doivent être remplis.";
    } elseif ($password !== $confirmPassword) {
        $errorMsg = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 8) {
        $errorMsg = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    if ($errorMsg) {
        echo "<script>
            document.getElementById('errorMessage').textContent = " . json_encode($errorMsg) . ";
            document.getElementById('errorMessage').classList.add('show');
        </script>";
    } else {
        // Pass department and interests to the register method
        $result = $userController->register($firstName, $lastName, $email, $studentId, $password, $userType, $phone, $year, $department, $interestsString);

        if ($result['success']) {
            echo "<script>
                document.getElementById('successMessage').textContent = 'Compte créé avec succès ! Redirection...';
                document.getElementById('successMessage').classList.add('show');
                setTimeout(() => window.location.href = 'profile.php', 1800);
            </script>";
        } else {
            echo "<script>
                document.getElementById('errorMessage').textContent = " . json_encode($result['message']) . ";
                document.getElementById('errorMessage').classList.add('show');
            </script>";
        }
    }
}
?>