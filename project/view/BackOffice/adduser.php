<?php
require_once '../../config.php';
require_once '../../model/User.php';

if ($_POST) {
    $user = new User();
    $data = [
        'student_id' => $_POST['student_id'],
        'first_name' => $_POST['first_name'],
        'last_name'  => $_POST['last_name'],
        'email'      => $_POST['email'],
        'phone'      => $_POST['phone'],
        'password'   => password_hash($_POST['password'], PASSWORD_DEFAULT),
        'user_type'  => $_POST['user_type'] ?? 'student'
    ];
    $user->create($data);            
    header("Location: userlist.php");
    exit;
}
?>
<!DOCTYPE html>
<html><head><title>Ajouter</title></head><body>
<h2>Ajouter un utilisateur</h2>
<form method="post">
    <input name="student_id" placeholder="Student ID" required><br><br>
    <input name="first_name" placeholder="Prénom" required><br><br>
    <input name="last_name" placeholder="Nom" required><br><br>
    <input name="email" type="email" placeholder="Email" required><br><br>
    <input name="password" type="password" placeholder="Mot de passe" required minlength="8"><br><br>
    <select name="user_type">
        <option value="student">Étudiant</option>
        <option value="teacher">Professeur</option>
        <option value="admin">admin</option>
    </select><br><br>
    <button type="submit">Créer</button>
</form>
</body></html>