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
    $userModel = new User();
    $userModel->delete($id);
}

header('Location: index.php');
exit;
?>