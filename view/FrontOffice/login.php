<?php
session_start();
require_once '../../config.php';
require_once '../../model/User.php';
require_once '../../auth_config.php';

$userModel = new User();
$error = '';

// Check if user is locked out
$lockoutFile = '../../temp/lockouts.json';
@mkdir('../../temp', 0755, true);

$lockouts = [];
if (file_exists($lockoutFile)) {
    $lockouts = json_decode(file_get_contents($lockoutFile), true) ?? [];
}

// Clean expired lockouts
$lockouts = array_filter($lockouts, fn($time) => time() < $time);
file_put_contents($lockoutFile, json_encode($lockouts));

// Handle Google Sign-In
if (isset($_POST['google_token'])) {
    $token = $_POST['google_token'];
    
    // Verify Google token
    $ch = curl_init('https://oauth2.googleapis.com/tokeninfo?id_token=' . $token);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $userData = json_decode($response, true);
    
    if (isset($userData['email'])) {
        $email = $userData['email'];
        $user = $userModel->getByEmail($email);
        
        if ($user) {
            // User exists, log them in
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            echo json_encode(['success' => true, 'redirect' => 'homepage.php']);
        } else {
            // Create new user from Google data
            $fullName = $userData['name'] ?? ($userData['given_name'] . ' ' . $userData['family_name']);
            $data = [
                'student_id' => 'STU' . time(),
                'full_name'  => $fullName,
                'email'      => $email,
                'password'   => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT), // Random password
                'type'       => 'student',
                'year'       => null,
                'department' => null,
                'phone'      => null
            ];
            $userId = $userModel->create($data);
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $fullName;
            echo json_encode(['success' => true, 'redirect' => 'homepage.php']);
        }
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid Google token']);
        exit;
    }
}

if ($_POST['action'] ?? '' === 'login') {
    $email = $_POST['email'] ?? '';
    
    // Verify reCAPTCHA
    if (isset($_POST['g-recaptcha-response'])) {
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptchaData = [
            'secret' => RECAPTCHA_SECRET_KEY,
            'response' => $recaptchaResponse,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($recaptchaData)
            ]
        ];
        
        $context  = stream_context_create($options);
        $result = file_get_contents($recaptchaUrl, false, $context);
        $resultJson = json_decode($result);
        
        if (!$resultJson->success) {
            $error = "Veuillez compléter le captcha.";
        }
    } else {
        $error = "Veuillez compléter le captcha.";
    }
    
    if (!$error) {
        // Check if email is locked out
        if (isset($lockouts[$email]) && time() < $lockouts[$email]) {
            $error = "Compte temporairement verrouillé. Réessayez plus tard.";
        } else {
            $user = $userModel->getByEmail($email);
            
            if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
                // Clear failed attempts
                unset($lockouts[$email]);
                file_put_contents($lockoutFile, json_encode($lockouts));
                
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                header('Location: homepage.php');
                exit;
            } else {
                // Increment failed attempts
                $attemptsFile = '../../temp/attempts.json';
                $attempts = [];
                if (file_exists($attemptsFile)) {
                    $attempts = json_decode(file_get_contents($attemptsFile), true) ?? [];
                }
                
                $attempts[$email] = ($attempts[$email] ?? 0) + 1;
                
                if ($attempts[$email] >= 3) {
                    // Lock account for 15 minutes
                    $lockouts[$email] = time() + (15 * 60);
                    file_put_contents($lockoutFile, json_encode($lockouts));
                    unset($attempts[$email]);
                    $error = "Trop de tentatives échouées. Compte verrouillé pendant 15 minutes.";
                } else {
                    file_put_contents($attemptsFile, json_encode($attempts));
                    $remaining = 3 - $attempts[$email];
                    $error = "Email ou mot de passe incorrect. Tentatives restantes : " . $remaining;
                }
            }
        }
    }
} elseif ($_POST['action'] ?? '' === 'signup') {
    $email = $_POST['email'] ?? '';
    $existingUser = $userModel->getByEmail($email);
    
    // Check if email already exists
    if ($existingUser) {
        $error = "Email déjà utilisé.";
    } else {
        $fullName = trim(($_POST['first_name'] ?? '') . ' ' . ($_POST['last_name'] ?? ''));
        $data = [
            'student_id' => 'STU' . time(),
            'full_name'  => $fullName,
            'email'      => $email,
            'password'   => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'type'       => 'student',
            'year'       => null,
            'department' => null,
            'phone'      => null
        ];
        $userId = $userModel->create($data);
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $fullName;
        header('Location: homepage.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect - Connexion</title>
    <link rel="stylesheet" href="css/style_login.css">
    
    <!-- Google Sign-In -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    
    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <style>
        html, body {margin:0;padding:0;height:auto;min-height:100vh;overflow-y:auto}
        .form-content.hidden {display:none !important}
        
        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 12px;
            margin: 15px 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .google-btn:hover {
            background: #f8f9fa;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .google-btn img {
            width: 20px;
            height: 20px;
        }
        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #999;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ddd;
        }
        
        .divider span {
            padding: 0 15px;
            font-size: 14px;
        }
        
        .recaptcha-container {
            display: flex;
            justify-content: center;
            margin: 15px 0;
        }
    </style>
</head>
<body onload="window.scrollTo(0,0)">

<div class="auth-container">
    <div class="auth-left">
        <div class="branding">
            <h1>CampusConnect</h1>
            <p class="tagline">Plateforme Sociale Universitaire</p>
            <p class="subtitle">Connectez-vous avec vos camarades, partagez vos projets, organisez des sessions d'étude</p>
        </div>
        <div class="features">
            <div class="feature-item"><div><h3>Communiquez</h3><p>Échangez avec étudiants et professeurs</p></div></div>
            <div class="feature-item"><div><h3>Collaborez</h3><p>Formez des groupes d'étude</p></div></div>
            <div class="feature-item"><div><h3>Partagez</h3><p>Publiez vos projets</p></div></div>
        </div>
    </div>

    <div class="auth-right">
        <div class="form-container">
            <?php if($error) echo "<p style='color:#ff6b6b;text-align:center;margin:10px 0'>$error</p>"; ?>

            <div class="form-wrapper active" id="formWrapper">
                <!-- LOGIN FORM -->
                <div id="loginForm" class="form-content">
                    <h2>Bon retour !</h2>
                    
                    <!-- Google Sign-In Button -->
                    <div id="g_id_onload"
                         data-client_id="<?= GOOGLE_CLIENT_ID ?>"
                         data-callback="handleGoogleSignIn"
                         data-auto_prompt="false">
                    </div>
                    
                    <button class="google-btn" onclick="googleSignIn()">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
                        Continuer avec Google
                    </button>
                    
                    <div class="divider"><span>OU</span></div>
                    
                    <form method="post">
                        <input type="hidden" name="action" value="login">
                        <div class="form-group"><input type="email" name="email" placeholder="Email" required></div>
                        <div class="form-group"><input type="password" name="password" placeholder="Mot de passe" required></div>
                        
                        <!-- reCAPTCHA -->
                        <div class="recaptcha-container">
                            <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_SITE_KEY ?>"></div>
                        </div>
                        
                        <button type="submit" class="btn-submit">Se connecter</button>
                        <p class="signup-link">Pas de compte ? <a href="signup.php">Créer un compte</a></p>
                        <p class="signup-link"><a href="forgot-password.php">Mot de passe oublié ?</a></p>
                    </form>
                </div>

                <!-- SIGNUP FORM -->
                <div id="signupForm" class="form-content hidden">
                    <h2>Créer un compte</h2>
                    
                    <!-- Google Sign-Up -->
                    <button class="google-btn" onclick="googleSignIn()">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
                        S'inscrire avec Google
                    </button>
                    
                    <div class="divider"><span>OU</span></div>
                    
                    <form method="post">
                        <input type="hidden" name="action" value="signup">
                        <div class="form-group"><input type="text" name="first_name" placeholder="Prénom" required></div>
                        <div class="form-group"><input type="text" name="last_name" placeholder="Nom" required></div>
                        <div class="form-group"><input type="email" name="email" placeholder="Email" required></div>
                        <div class="form-group"><input type="password" name="password" placeholder="Mot de passe (8+)" minlength="8" required></div>
                        <button type="submit" class="btn-submit">S'inscrire</button>
                        <p class="signup-link">Déjà un compte ? <a href="#" onclick="switchToLogin(); return false;">Se connecter</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function switchToSignup() {
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('signupForm').classList.remove('hidden');
}

function switchToLogin() {
    document.getElementById('signupForm').classList.add('hidden');
    document.getElementById('loginForm').classList.remove('hidden');
}

// Google Sign-In Handler
function handleGoogleSignIn(response) {
    const token = response.credential;
    
    // Send token to server
    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'google_token=' + encodeURIComponent(token)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert(data.message || 'Erreur de connexion Google');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Erreur de connexion');
    });
}

function googleSignIn() {
    google.accounts.id.initialize({
        client_id: '<?= GOOGLE_CLIENT_ID ?>',
        callback: handleGoogleSignIn
    });
    
    google.accounts.id.prompt();
}
</script>
</body>
</html>