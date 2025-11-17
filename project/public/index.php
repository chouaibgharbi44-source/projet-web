<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}
require_once '../config.php';
require_once '../model/User.php';

$userModel = new User();
$users = $userModel->getAll();
$totalUsers = count($users);
$totalStudents = count(array_filter($users, fn($u) => ($u['user_type'] ?? $u['type'] ?? '') !== 'teacher'));
$totalTeachers = $totalUsers - $totalStudents;

include '../view/BackOffice/userlist.php';