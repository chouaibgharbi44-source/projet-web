<!-- View/backoffice/sidebar.php -->
<div class="sidebar">
    <div class="logo">🎓 Campus Connect</div>
    <ul>
        <li><a href="index.php?area=admin" class="<?= !isset($_GET['action']) ? 'active' : '' ?>">📊 Tableau de Bord</a></li>
        <li><a href="index.php?entity=matiere&area=admin&action=list" class="<?= isset($_GET['entity']) && $_GET['entity'] === 'matiere' ? 'active' : '' ?>">📚 Matières</a></li>
        <li><a href="index.php?entity=ressource&area=admin&action=list" class="<?= isset($_GET['entity']) && $_GET['entity'] === 'ressource' ? 'active' : '' ?>">📄 Ressources</a></li>
        <li><a href="index.php?entity=ressource&area=admin&action=favoris">❤️ Favoris</a></li>
        <li><a href="index.php?entity=ressource&area=admin&action=telechargements">📥 Téléchargements</a></li>
        <li><a href="index.php?area=admin&action=logs" class="<?= isset($_GET['action']) && $_GET['action'] === 'logs' ? 'active' : '' ?>">📋 Historique</a></li>
    </ul>
</div>