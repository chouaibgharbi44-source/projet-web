<?php
 
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Afficher utilisateur</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="C:\Users\death\Desktop\project\public\css\style-gestion.css">
</head>
<body>
  <div class="app-container">
    <aside class="sidebar"><div class="logo">Campus Connect</div></aside>
    <main class="main-content">
      <h2>Profil utilisateur</h2>
      <div id="userDetails">Chargement...</div>
      <p><a href="userlist.php">Retour</a></p>
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
        const html = `
          <div style="border:1px solid #eee;padding:12px;border-radius:8px">
            <p><strong>Student ID:</strong> ${escapeHtml(u.student_id||'')}</p>
            <p><strong>Nom:</strong> ${escapeHtml((u.first_name||'') + ' ' + (u.last_name||''))}</p>
            <p><strong>Email:</strong> ${escapeHtml(u.email||'')}</p>
            <p><strong>Type:</strong> ${escapeHtml(u.user_type||'')}</p>
            <p><strong>Intérêts:</strong> ${escapeHtml(u.interests||'')}</p>
            <p><strong>Département:</strong> ${escapeHtml(u.department||'')}</p>
            <p><strong>Téléphone:</strong> ${escapeHtml(u.phone||'')}</p>
            ${u.profile_image ? `<p><img src="../../${u.profile_image}" alt="profile" style="max-width:120px;border-radius:6px"></p>` : ''}
            ${u.banner_image ? `<p><img src="../../${u.banner_image}" alt="banner" style="max-width:360px"></p>` : ''}
          </div>
        `;
        document.getElementById('userDetails').innerHTML = html;
      } catch (err) {
        alert('Impossible de charger');
        location.href = 'userlist.php';
      }

      function escapeHtml(s){ return String(s || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c]); }
    })();
  </script>
</body>
</html>
