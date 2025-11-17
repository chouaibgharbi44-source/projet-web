<?php
// view/BackOffice/deleteUser.php
// expects ?id=NN
$id = $_GET['id'] ?? null;
if (!$id) { header('Location: userlist.php'); exit; }
?>
<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><title>Supprimer</title></head>
<body>
  <h2>Supprimer l'utilisateur #<?=htmlspecialchars($id)?></h2>
  <form action="C:\Users\death\Desktop\project\controllers\UserController.php?action=delete" method="post">
    <input type="hidden" name="id" value="<?=htmlspecialchars($id)?>">
    <p>Confirmer la suppression ?</p>
    <button type="submit">Oui, supprimer</button>
    <a href="userlist.php">Annuler</a>
  </form>
</body>
</html>
