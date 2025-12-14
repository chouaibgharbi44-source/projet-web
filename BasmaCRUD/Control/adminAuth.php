<?php
// Simple admin auth helper
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isAdminLogged() {
    return !empty($_SESSION['is_admin']);
}

function requireAdmin() {
    if (!isAdminLogged()) {
        // If not logged in, redirect to login with return URL
        $returnUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'index.php?area=admin';
        header('Location: index.php?admin=login&return=' . urlencode($returnUrl));
        exit;
    }
}

function handleAdminAuthActions() {
    // Login submit
    if (isset($_GET['admin']) && $_GET['admin'] === 'login' && !empty($_POST['admin_password'])) {
        $pw = $_POST['admin_password'];
        if ($pw === 'admin123') {
            $_SESSION['is_admin'] = true;
            // redirect back
            $return = isset($_GET['return']) ? $_GET['return'] : 'index.php?area=admin';
            header('Location: ' . $return);
            exit;
        } else {
            // wrong password, display message on login page
            $_SESSION['admin_login_error'] = 'Mot de passe incorrect';
            header('Location: index.php?admin=login');
            exit;
        }
    }

    // logout
    if (isset($_GET['admin']) && $_GET['admin'] === 'logout') {
        unset($_SESSION['is_admin']);
        header('Location: index.php');
        exit;
    }
}

?>