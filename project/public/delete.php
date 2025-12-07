<?php
session_start();
require_once '../config.php';
require_once '../model/User.php';

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        $userModel = new User();
        $result = $userModel->delete($id);
        
        if ($result) {
            $_SESSION['success_message'] = 'Utilisateur supprimé avec succès';
        } else {
            $_SESSION['error_message'] = 'Erreur lors de la suppression';
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Erreur: ' . $e->getMessage();
        error_log('Delete error: ' . $e->getMessage());
    }
}

header('Location: index.php');
exit;
?>