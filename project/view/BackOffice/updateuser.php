<?php
 
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Modifier utilisateur - BackOffice</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../../public/css/style-gestion.css">
</head>
<body>
  <div class="app-container">
    <aside class="sidebar"><div class="logo">Campus Connect</div></aside>
    <main class="main-content">
      <h2>Modifier l'utilisateur</h2>

      <form id="updateForm" enctype="multipart/form-data" action="../../controllers/UserController.php?action=update" method="post">
        <input type="hidden" name="id" id="uid">
        <div class="form-row">
          <label>Bannière de Profil</label>
          <input type="file" name="banner_image" accept="image/*">
        </div>
        <div class="form-row">
          <label>Photo de Profil</label>
          <input type="file" name="profile_image" accept="image/*">
        </div>

        <fieldset>
          <label>Student ID *<input name="student_id" id="student_id" required></label>
          <label>Prénom *<input name="first_name" id="first_name" required></label>
          <label>Nom *<input name="last_name" id="last_name" required></label>
          <label>Email *<input name="email" id="email" type="email" required></label>
          <label>Mot de passe (laisser vide pour ne pas changer) <input name="password" type="password" minlength="8"></label>
          <label>Type d'utilisateur *<select name="user_type" id="user_type" required><option value="">Sélectionner...</option><option>Étudiant</option><option>Professeur</option><option>Personnel</option></select></label>
          <label>Centres d'intérêt <input name="interests" id="interests"></label>
          <label>Département <input name="department" id="department"></label>
          <label>Téléphone <input name="phone" id="phone"></label>
        </fieldset>

        <div style="margin-top:12px">
          <button type="submit" class="primary">Mettre à jour</button>
          <a href="userlist.php"><button type="button">Retour</button></a>
        </div>
      </form>
    </main>
  </div>

  <script>
     
    (async function(){
      const params = new URLSearchParams(location.search);
      const id = params.get('id');
      if (!id) { alert('ID manquant'); location.href = 'userlist.php'; return; }
      try {
        const res = await fetch(`C:\Users\death\Desktop\project\controllers\UserController.php?action=get&id=${id}`);
        const json = await res.json();
        if (!json.success) throw new Error(json.message || 'Erreur');
        const u = json.data;
        document.getElementById('uid').value = u.id;
        document.getElementById('student_id').value = u.student_id || '';
        document.getElementById('first_name').value = u.first_name || '';
        document.getElementById('last_name').value = u.last_name || '';
        document.getElementById('email').value = u.email || '';
        document.getElementById('user_type').value = u.user_type || '';
        document.getElementById('interests').value = u.interests || '';
        document.getElementById('department').value = u.department || '';
        document.getElementById('phone').value = u.phone || '';
      } catch (err) {
        alert('Impossible de récupérer l\\'utilisateur');
        location.href = 'userlist.php';
      }
    })();
  </script>
</body>
</html>
