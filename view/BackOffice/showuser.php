<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Afficher utilisateur - CampusConnect</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../public/css/style-gestion.css">
</head>
<body>
  <div class="app-container">
    <aside class="sidebar">
      <div class="logo">
        <img src="../public/images/logo.png" alt="Logo" class="logo-img">
        <span class="logo-text">Campus Connect Logo</span>
      </div>
      <h1 class="brand-title">CAMPUS CONNECT</h1>
      <p class="brand-subtitle">Your University United</p>
      <a href="../public/logout.php" class="logout-btn">Déconnexion</a>
    </aside>
    
    <main class="main-content">
      <header class="page-header">
        <h2 class="page-title">Profil Utilisateur</h2>
        <p class="page-subtitle">Détails complets</p>
      </header>
      
      <div id="userDetails" style="padding: 20px;">
        <p style="text-align: center; color: #666;">Chargement...</p>
      </div>
      
      <p style="padding: 20px;">
        <a href="index.php" class="search-btn" style="display: inline-block; text-decoration: none;">← Retour à la liste</a>
      </p>
    </main>
  </div>

  <script>
    (async function(){
      const params = new URLSearchParams(location.search);
      const id = params.get('id');
      
      if (!id) { 
        alert('ID utilisateur manquant'); 
        location.href = 'index.php'; 
        return; 
      }
      
      try {
        const res = await fetch(`../controllers/UserController.php?action=get&id=${id}`);
        const json = await res.json();
        
        if (!json.success) {
          throw new Error(json.message || 'Erreur lors du chargement');
        }
        
        const u = json.data;
        const html = `
          <div style="background: white; border: 1px solid #e0e0e0; padding: 24px; border-radius: 8px; max-width: 800px; margin: 0 auto;">
            ${u.banner_image ? `
              <div style="margin-bottom: 20px;">
                <img src="${escapeHtml(u.banner_image)}" alt="banner" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 6px;">
              </div>
            ` : ''}
            
            ${u.profile_image ? `
              <div style="text-align: center; margin-bottom: 20px;">
                <img src="${escapeHtml(u.profile_image)}" alt="profile" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #4CAF50;">
              </div>
            ` : ''}
            
            <h3 style="text-align: center; color: #333; margin-bottom: 24px;">
              ${escapeHtml((u.first_name||'') + ' ' + (u.last_name||''))}
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <div style="padding: 12px; background: #f9f9f9; border-radius: 6px;">
                <strong style="color: #666; font-size: 12px; text-transform: uppercase;">Student ID</strong>
                <p style="margin: 4px 0 0 0; color: #333;">${escapeHtml(u.student_id||'-')}</p>
              </div>
              
              <div style="padding: 12px; background: #f9f9f9; border-radius: 6px;">
                <strong style="color: #666; font-size: 12px; text-transform: uppercase;">Email</strong>
                <p style="margin: 4px 0 0 0; color: #333;">${escapeHtml(u.email||'-')}</p>
              </div>
              
              <div style="padding: 12px; background: #f9f9f9; border-radius: 6px;">
                <strong style="color: #666; font-size: 12px; text-transform: uppercase;">Type</strong>
                <p style="margin: 4px 0 0 0; color: #333;">${formatUserType(u.user_type)}</p>
              </div>
              
              <div style="padding: 12px; background: #f9f9f9; border-radius: 6px;">
                <strong style="color: #666; font-size: 12px; text-transform: uppercase;">Téléphone</strong>
                <p style="margin: 4px 0 0 0; color: #333;">${escapeHtml(u.phone||'-')}</p>
              </div>
              
              <div style="padding: 12px; background: #f9f9f9; border-radius: 6px;">
                <strong style="color: #666; font-size: 12px; text-transform: uppercase;">Département</strong>
                <p style="margin: 4px 0 0 0; color: #333;">${escapeHtml(u.department||'-')}</p>
              </div>
              
              <div style="padding: 12px; background: #f9f9f9; border-radius: 6px;">
                <strong style="color: #666; font-size: 12px; text-transform: uppercase;">Année</strong>
                <p style="margin: 4px 0 0 0; color: #333;">${escapeHtml(u.year||'-')}</p>
              </div>
              
              <div style="padding: 12px; background: #f9f9f9; border-radius: 6px; grid-column: 1 / -1;">
                <strong style="color: #666; font-size: 12px; text-transform: uppercase;">Intérêts</strong>
                <p style="margin: 4px 0 0 0; color: #333;">${escapeHtml(u.interests||'-')}</p>
              </div>
              
              ${u.created_at ? `
                <div style="padding: 12px; background: #f9f9f9; border-radius: 6px; grid-column: 1 / -1;">
                  <strong style="color: #666; font-size: 12px; text-transform: uppercase;">Membre depuis</strong>
                  <p style="margin: 4px 0 0 0; color: #333;">${new Date(u.created_at).toLocaleDateString('fr-FR', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                  })}</p>
                </div>
              ` : ''}
            </div>
          </div>
        `;
        
        document.getElementById('userDetails').innerHTML = html;
        
      } catch (err) {
        console.error('Erreur:', err);
        document.getElementById('userDetails').innerHTML = `
          <div style="text-align: center; padding: 40px; color: #d32f2f;">
            <p style="font-size: 18px; margin-bottom: 10px;">❌ Impossible de charger les données</p>
            <p style="color: #666;">${escapeHtml(err.message)}</p>
          </div>
        `;
      }

      function escapeHtml(s) { 
        return String(s || '').replace(/[&<>"']/g, c => ({
          '&':'&amp;',
          '<':'&lt;',
          '>':'&gt;',
          '"':'&quot;',
          "'":'&#39;'
        })[c]); 
      }
      
      function formatUserType(type) {
        switch(type) {
          case 'student': return 'Étudiant';
          case 'professor': return 'Professeur';
          case 'admin': return 'Administrateur';
          default: return 'Inconnu';
        }
      }
    })();
  </script>
</body>
</html>