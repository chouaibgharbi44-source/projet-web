<?php
// controllers/UserController.php

require_once __DIR__ . '/../model/User.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // 1. Show the list + stats (your main backoffice page)
    public function list()
    {
        $users = $this->userModel->getAll();
        $totalUsers = count($users);
        $totalStudents = count(array_filter($users, fn($u) => $u['type'] === 'student'));
        $totalTeachers = $totalUsers - $totalStudents;

        include '../view/BackOffice/userlist.php';
    }

    // 2. Add a new user
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->create($_POST);
            header('Location: /backoffice/users');
            exit;
        }
        include '../view/BackOffice/adduser.php'; // create this form
    }
    public function getDashboardData() {
    $userModel = new User();
    $users = $userModel->getAll();
    return [
        'users' => $users,
        'totalUsers' => count($users),
        'totalStudents' => count(array_filter($users, fn($u) => ($u['type'] ?? '') !== 'teacher')),
        'totalTeachers' => count($users) - count(array_filter($users, fn($u) => ($u['type'] ?? '') !== 'teacher'))
    ];
}
    // 3. Update user
    public function update()
    {
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->update($id, $_POST);
            header('Location: /backoffice/users');
            exit;
        }
        $user = $this->userModel->getById($id);
        include '../view/BackOffice/updateuser.php';
    }

    // 4. Delete user
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        $this->userModel->delete($id);
        header('Location: /backoffice/users');
        exit;
    }
}