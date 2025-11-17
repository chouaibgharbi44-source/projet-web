<?php
session_start();
// Include configuration and user model for logic
require_once '../config.php';
require_once '../model/User.php';

// Initialize the User model (assuming it handles database operations)
$userModel = new User();
$error = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // 1. Retrieve the user by email
    $user = $userModel->getByEmail($email);

    // 2. Verify user existence and password
    if ($user && password_verify($password, $user['password'])) {
        
        // 3. Verify user has the 'admin' role (Crucial for admin login)
        // **NOTE**: Adjust 'admin' role check based on your actual database field/value
        if ($user['user_type'] === 'admin') {
            // Success: Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['first_name'];

            // CORRECTED REDIRECTION to the main BackOffice index.php (gestion page)
            header('Location: view/BackOffice/index.php');
            exit;
        } else {
            // User exists but is not an administrator
            $error = "Accès refusé. Vous n'êtes pas un administrateur.";
        }
    } else {
        // Invalid credentials
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In - CampusConnect</title>
    <meta name="description" content="Admin login portal for CampusConnect">
    <link rel="stylesheet" href="css/style_admin_login.css"> 
    <style>
        .error-message {
            color: #ff6b6b;
            text-align: center;
            margin: 10px 0 20px 0;
            font-weight: 600;
        }
        /* Style for JS validation errors (initially hidden) */
        #js-error-message {
            display: none; 
            color: #ff6b6b;
            text-align: center;
            margin: 10px 0 20px 0;
            font-weight: 600;
            padding: 10px;
            background: #251a1a;
            border-radius: 8px;
            border: 1px solid #ff6b6b;
        }
    </style>
</head>
<body>

   
    <div class="wrapper">

        
        <div class="left-panel">
            <div class="branding">
                <h1 class="logo-text">CampusConnect</h1>
                <p class="tagline">Admin Portal</p>
            </div>
        </div>

        
        <div class="right-panel">
            <div class="login-card">

                <div class="login-header">
                    <h2>Welcome Back, Admin</h2>
                    <p class="subtitle">Sign in to manage CampusConnect</p>
                </div>

                <?php if ($error): ?>
                    <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>

                <p id="js-error-message"></p>

                <form action="" method="POST" id="admin-login-form">

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="admin@campusconnect.edu" required autocomplete="username" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" id="remember" name="remember">
                            <span>Remember this device</span>
                        </label>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>

                    <button type="submit" class="signin-btn">Sign In</button>
                </form>

                <div class="extra-links">
                    <p>Not an admin? <a href="index.php">Return to CampusConnect</a></p>
                    <p class="security-note">
                        <strong>Protected:</strong> This portal is restricted to authorized administrators only.
                    </p>
                </div>

            </div>

           
            <footer class="panel-footer">
                <p>© 2025 CampusConnect. All rights reserved.</p>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Contact Support</a>
                </div>
            </footer>
        </div>

    </div>
    
    <script src="js/admin_login.js"></script> 

</body>
</html>