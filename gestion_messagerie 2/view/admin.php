<?php
require '../model/post.php';

// Simple protection with URL key
if (!isset($_GET['key']) || $_GET['key'] !== 'admin123') {
    echo "<h2 style='text-align:center; margin-top:100px; color:#e74c3c;'>Accès Refusé</h2>";
    exit();
}

// Handle delete post action
if (isset($_GET['delete_post'])) {
    $post_id = (int)$_GET['delete_post'];
    deletePost($post_id);
    header("Location: admin.php?key=admin123");
    exit();
}

// Handle delete comment action
if (isset($_GET['delete_comment'])) {
    $comment_id = (int)$_GET['delete_comment'];
    deleteComment($comment_id);
    header("Location: admin.php?key=admin123");
    exit();
}

// Get all posts WITH JOINS
$posts = getPostsWithStats();
$total_posts = count($posts);
$total_comments = 0;
$total_likes = 0;

// Calculer les totaux depuis les JOINS
foreach ($posts as $post) {
    $total_comments += ($post['comment_count'] ?? 0);
    $total_likes += ($post['like_count'] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - Campus Connect</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Modern Admin Styles */
    :root {
        --admin-primary: #2c3e50;
        --admin-secondary: #34495e;
        --admin-accent: #3498db;
        --admin-danger: #e74c3c;
        --admin-success: #27ae60;
        --admin-warning: #f39c12;
        --admin-light: #ecf0f1;
        --admin-dark: #2c3e50;
        --admin-gray: #7f8c8d;
        --admin-border-radius: 10px;
        --admin-shadow: 0 4px 6px rgba(0,0,0,0.1);
        --admin-shadow-hover: 0 8px 15px rgba(0,0,0,0.15);
        --admin-transition: all 0.3s ease;
    }

    /* Admin Header */
    .admin-header {
        background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
        color: white;
        padding: 25px 0;
        margin-bottom: 30px;
        border-radius: 0 0 var(--admin-border-radius) var(--admin-border-radius);
        box-shadow: var(--admin-shadow);
    }

    .admin-header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .admin-title {
        font-size: 1.8rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .admin-title i {
        font-size: 2rem;
        color: var(--admin-accent);
    }

    .admin-breadcrumb {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .admin-breadcrumb a {
        color: white;
        text-decoration: none;
        opacity: 0.8;
        transition: var(--admin-transition);
    }

    .admin-breadcrumb a:hover {
        opacity: 1;
        text-decoration: underline;
    }

    /* Admin Dashboard */
    .admin-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Stats Cards */
    .admin-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: var(--admin-border-radius);
        padding: 25px;
        box-shadow: var(--admin-shadow);
        border-left: 5px solid var(--admin-accent);
        transition: var(--admin-transition);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--admin-shadow-hover);
    }

    .stat-card.danger {
        border-left-color: var(--admin-danger);
    }

    .stat-card.warning {
        border-left-color: var(--admin-warning);
    }

    .stat-card.success {
        border-left-color: var(--admin-success);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .stat-title {
        font-size: 0.95rem;
        color: var(--admin-gray);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .stat-icon {
        font-size: 1.5rem;
        color: var(--admin-accent);
    }

    .stat-card.danger .stat-icon {
        color: var(--admin-danger);
    }

    .stat-card.warning .stat-icon {
        color: var(--admin-warning);
    }

    .stat-card.success .stat-icon {
        color: var(--admin-success);
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--admin-dark);
        margin-bottom: 10px;
    }

    .stat-change {
        font-size: 0.9rem;
        color: var(--admin-success);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .stat-change.negative {
        color: var(--admin-danger);
    }

    /* Admin Content */
    .admin-content {
        background: white;
        border-radius: var(--admin-border-radius);
        box-shadow: var(--admin-shadow);
        overflow: hidden;
        margin-bottom: 40px;
    }

    .content-header {
        background: var(--admin-light);
        padding: 20px 25px;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .content-title {
        font-size: 1.3rem;
        color: var(--admin-dark);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .content-actions {
        display: flex;
        gap: 10px;
    }

    .btn-admin {
        padding: 8px 20px;
        border-radius: 6px;
        border: none;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--admin-transition);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-refresh {
        background: var(--admin-light);
        color: var(--admin-dark);
        border: 1px solid #ddd;
    }

    .btn-refresh:hover {
        background: #e0e0e0;
    }

    .btn-export {
        background: var(--admin-success);
        color: white;
    }

    .btn-export:hover {
        background: #219653;
    }

    /* Posts Table */
    .admin-table-container {
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .admin-table thead {
        background: var(--admin-light);
    }

    .admin-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--admin-dark);
        border-bottom: 2px solid #ddd;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-table tbody tr {
        border-bottom: 1px solid #eee;
        transition: var(--admin-transition);
    }

    .admin-table tbody tr:hover {
        background: #f9f9f9;
    }

    .admin-table td {
        padding: 15px;
        color: #333;
        vertical-align: top;
    }

    .post-content-cell {
        max-width: 400px;
        word-wrap: break-word;
    }

    .post-content-preview {
        margin: 0;
        line-height: 1.5;
        max-height: 60px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .post-meta {
        display: flex;
        flex-direction: column;
        gap: 5px;
        font-size: 0.85rem;
        color: var(--admin-gray);
    }

    .post-date {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .post-user {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 4px;
        border: none;
        font-size: 0.85rem;
        cursor: pointer;
        transition: var(--admin-transition);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-view {
        background: rgba(52, 152, 219, 0.1);
        color: var(--admin-accent);
        border: 1px solid rgba(52, 152, 219, 0.2);
    }

    .btn-view:hover {
        background: rgba(52, 152, 219, 0.2);
    }

    .btn-edit {
        background: rgba(243, 156, 18, 0.1);
        color: var(--admin-warning);
        border: 1px solid rgba(243, 156, 18, 0.2);
    }

    .btn-edit:hover {
        background: rgba(243, 156, 18, 0.2);
    }

    .btn-delete {
        background: rgba(231, 76, 60, 0.1);
        color: var(--admin-danger);
        border: 1px solid rgba(231, 76, 60, 0.2);
    }

    .btn-delete:hover {
        background: rgba(231, 76, 60, 0.2);
    }

    /* Comments Section */
    .comments-section {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #eee;
    }

    .comment-item {
        padding: 10px;
        background: #f9f9f9;
        border-radius: 6px;
        margin-bottom: 8px;
        border-left: 3px solid var(--admin-accent);
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .comment-user {
        font-size: 0.85rem;
        color: var(--admin-dark);
        font-weight: 500;
    }

    .comment-date {
        font-size: 0.8rem;
        color: var(--admin-gray);
    }

    .comment-content {
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 5px;
    }

    .comment-actions {
        text-align: right;
    }

    /* Modal Styles */
    .admin-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        backdrop-filter: blur(5px);
    }

    .admin-modal-content {
        background: white;
        margin: 10% auto;
        padding: 30px;
        border-radius: var(--admin-border-radius);
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        animation: modalSlideIn 0.3s ease;
    }

    .admin-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .admin-modal-title {
        font-size: 1.5rem;
        color: var(--admin-dark);
        font-weight: 600;
    }

    .admin-modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--admin-gray);
        cursor: pointer;
        transition: var(--admin-transition);
    }

    .admin-modal-close:hover {
        color: var(--admin-danger);
    }

    .admin-modal-body textarea {
        width: 100%;
        min-height: 150px;
        padding: 15px;
        border: 2px solid #eee;
        border-radius: var(--admin-border-radius);
        font-size: 1rem;
        resize: vertical;
        transition: var(--admin-transition);
        margin-bottom: 20px;
        font-family: inherit;
    }

    .admin-modal-body textarea:focus {
        outline: none;
        border-color: var(--admin-accent);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .admin-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .btn-modal-save {
        background: var(--admin-accent);
        color: white;
        padding: 10px 25px;
        border-radius: 6px;
        border: none;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--admin-transition);
    }

    .btn-modal-save:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .btn-modal-cancel {
        background: #eee;
        color: #666;
        padding: 10px 25px;
        border-radius: 6px;
        border: none;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--admin-transition);
    }

    .btn-modal-cancel:hover {
        background: #ddd;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--admin-gray);
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        color: #eee;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .admin-header-content {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .admin-stats {
            grid-template-columns: 1fr;
        }

        .content-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .content-actions {
            flex-wrap: wrap;
            justify-content: center;
        }

        .admin-modal-content {
            padding: 20px;
            margin: 20% auto;
        }
    }
</style>
</head>
<body>
<!-- Admin Header -->
<div class="admin-header">
    <div class="admin-header-content">
        <div>
            <h1 class="admin-title">
                <i class="fas fa-shield-alt"></i>
                Administration Campus Connect
            </h1>
            <div class="admin-breadcrumb">
                <a href="../index.php">Accueil</a> / 
                <span>Tableau de bord Administrateur</span>
            </div>
        </div>
        <div style="color: rgba(255,255,255,0.8); font-size: 0.9rem;">
            <i class="fas fa-user-shield" style="margin-right: 8px;"></i>
            Session Admin • <?= date('d/m/Y H:i') ?>
        </div>
    </div>
</div>

<!-- Admin Dashboard -->
<div class="admin-dashboard">
    <!-- Stats Overview -->
    <div class="admin-stats">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Publications</div>
                <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
            </div>
            <div class="stat-value"><?= $total_posts ?></div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> <?= $total_posts > 0 ? round($total_posts * 0.12) : 0 ?> cette semaine
            </div>
        </div>

        <div class="stat-card warning">
            <div class="stat-header">
                <div class="stat-title">Commentaires</div>
                <div class="stat-icon"><i class="fas fa-comments"></i></div>
            </div>
            <div class="stat-value"><?= $total_comments ?></div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> <?= $total_comments > 0 ? round($total_comments * 0.08) : 0 ?> cette semaine
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-title">Utilisateurs Actifs</div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-value"><?= count($posts) > 0 ? count(array_unique(array_column($posts, 'user_id'))) : 0 ?></div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> <?= count($posts) > 0 ? round(count(array_unique(array_column($posts, 'user_id'))) * 0.15) : 0 ?> cette semaine
            </div>
        </div>

        <div class="stat-card danger">
            <div class="stat-header">
                <div class="stat-title">Taux d'Engagement</div>
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            </div>
            <div class="stat-value">
                <?php 
                $engagement = ($total_posts > 0) ? round(($total_comments + $total_likes) / $total_posts, 1) : 0;
                echo $engagement;
                ?>
            </div>
            <div class="stat-change <?= $engagement > 2 ? 'positive' : 'negative' ?>">
                <i class="fas fa-<?= $engagement > 2 ? 'arrow-up' : 'arrow-down' ?>"></i> 
                <?= $engagement > 2 ? 'Excellent' : 'À améliorer' ?>
            </div>
        </div>
    </div>

    <!-- Posts Management -->
    <div class="admin-content">
        <div class="content-header">
            <h2 class="content-title">
                <i class="fas fa-edit"></i>
                Gestion des Publications
                <span style="font-size: 0.9rem; color: var(--admin-gray); margin-left: 10px;">
                    (<?= $total_posts ?> publications • <?= $total_comments ?> commentaires • <?= $total_likes ?> likes)
                </span>
            </h2>
            <div class="content-actions">
                <button class="btn-admin btn-refresh" onclick="window.location.reload()">
                    <i class="fas fa-sync-alt"></i> Actualiser
                </button>
                <button class="btn-admin btn-export" onclick="exportData()">
                    <i class="fas fa-download"></i> Exporter CSV
                </button>
            </div>
        </div>

        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="far fa-newspaper"></i>
                </div>
                <h3>Aucune publication à gérer</h3>
                <p>Il n'y a actuellement aucune publication sur la plateforme.</p>
            </div>
        <?php else: ?>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Contenu</th>
                            <th>Auteur</th>
                            <th>Date</th>
                            <th>Statistiques</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $p): 
                            // DÉJÀ DANS $p GRÂCE AUX JOINS :
                            $comment_count = $p['comment_count'] ?? 0;
                            $like_count = $p['like_count'] ?? 0;
                            $post_comments = getComments($p['id']); // Pour les détails seulement
                        ?>
                            <tr id="post-row-<?= $p['id'] ?>">
                                <td><strong>#<?= $p['id'] ?></strong></td>
                                <td class="post-content-cell">
                                    <p class="post-content-preview"><?= htmlspecialchars($p['content']) ?></p>
                                </td>
                                <td>
                                    <div class="post-meta">
                                        <div class="post-user">
                                            <i class="fas fa-user"></i>
                                            ID: <?= $p['user_id'] ?? 'Anonyme' ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="post-meta">
                                        <div class="post-date">
                                            <i class="far fa-calendar"></i>
                                            <?= date('d/m/Y H:i', strtotime($p['created_at'])) ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="post-meta">
                                        <div><i class="fas fa-heart" style="color: #e74c3c;"></i> <?= $like_count ?> likes</div>
                                        <div><i class="fas fa-comment" style="color: #3498db;"></i> <?= $comment_count ?> commentaires</div>
                                        <div style="font-size: 0.8rem; color: #7f8c8d;">
                                            Engagement: <?= $comment_count + $like_count ?> interactions
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn-view" onclick="viewPostDetails(<?= $p['id'] ?>)">
                                            <i class="fas fa-eye"></i> Détails
                                        </button>
                                        <button class="btn-action btn-edit" onclick="openEditModal(<?= $p['id'] ?>, '<?= htmlspecialchars($p['content'], ENT_QUOTES); ?>')">
                                            <i class="fas fa-edit"></i> Éditer
                                        </button>
                                        <a href="admin.php?key=admin123&delete_post=<?= $p['id']; ?>" 
                                           class="btn-action btn-delete" 
                                           onclick="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cette publication?\n\nCette action supprimera également tous les commentaires et likes associés.\n\nPublication #<?= $p['id'] ?>')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Comments for this post (hidden by default) -->
                            <tr id="comments-row-<?= $p['id'] ?>" style="display: none;">
                                <td colspan="6">
                                    <div class="comments-section">
                                        <h4 style="margin-bottom: 15px; color: var(--admin-dark); font-size: 1rem;">
                                            <i class="fas fa-comments"></i> Commentaires (<?= $comment_count ?>)
                                        </h4>
                                        <?php if ($comment_count > 0): ?>
                                            <?php foreach ($post_comments as $c): ?>
                                                <div class="comment-item">
                                                    <div class="comment-header">
                                                        <div class="comment-user">
                                                            <i class="fas fa-user-circle"></i>
                                                            Utilisateur: <?= $c['user_id'] ?? 'Anonyme' ?>
                                                        </div>
                                                        <div class="comment-date">
                                                            <?= date('d/m/Y H:i', strtotime($c['created_at'])) ?>
                                                        </div>
                                                    </div>
                                                    <div class="comment-content">
                                                        <?= htmlspecialchars($c['content']) ?>
                                                    </div>
                                                    <div class="comment-actions">
                                                        <button class="btn-action btn-edit" 
                                                                style="padding: 4px 8px; font-size: 0.8rem; margin-right: 5px;"
                                                                onclick="editCommentInAdmin(<?= $c['id'] ?>, '<?= htmlspecialchars($c['content'], ENT_QUOTES); ?>')">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <a href="admin.php?key=admin123&delete_comment=<?= $c['id']; ?>" 
                                                           class="btn-action btn-delete" 
                                                           style="padding: 4px 8px; font-size: 0.8rem;"
                                                           onclick="return confirm('Supprimer ce commentaire ?')">
                                                            <i class="fas fa-times"></i> Supprimer
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p style="color: var(--admin-gray); font-style: italic; text-align: center; padding: 20px;">
                                                Aucun commentaire pour cette publication.
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Post Modal -->
<div id="editModal" class="admin-modal">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title">Modifier la publication</h3>
            <button class="admin-modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <div class="admin-modal-body">
            <form id="editPostForm">
                <input type="hidden" id="editPostId" name="post_id">
                <input type="hidden" name="key" value="admin123">
                <textarea id="editPostContent" name="content" rows="4" required placeholder="Modifiez le contenu de la publication..."></textarea>
                <div class="admin-modal-actions">
                    <button type="submit" class="btn-modal-save">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Comment Modal -->
<div id="editCommentModal" class="admin-modal" style="display: none;">
    <div class="admin-modal-content">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title">Modifier le commentaire</h3>
            <button class="admin-modal-close" onclick="closeCommentEditModal()">&times;</button>
        </div>
        <div class="admin-modal-body">
            <form id="editCommentForm">
                <input type="hidden" id="editCommentId" name="comment_id">
                <input type="hidden" name="key" value="admin123">
                <textarea id="editCommentContent" name="content" rows="4" required placeholder="Modifiez le commentaire..."></textarea>
                <div class="admin-modal-actions">
                    <button type="submit" class="btn-modal-save">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <button type="button" class="btn-modal-cancel" onclick="closeCommentEditModal()">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Toggle comments view
function viewPostDetails(postId) {
    const commentsRow = document.getElementById('comments-row-' + postId);
    if (commentsRow.style.display === 'none') {
        commentsRow.style.display = 'table-row';
    } else {
        commentsRow.style.display = 'none';
    }
}

// Edit post functions
function openEditModal(postId, content) {
    document.getElementById('editPostId').value = postId;
    document.getElementById('editPostContent').value = content;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Edit comment functions
function editCommentInAdmin(commentId, content) {
    document.getElementById('editCommentId').value = commentId;
    document.getElementById('editCommentContent').value = content;
    document.getElementById('editCommentModal').style.display = 'block';
}

function closeCommentEditModal() {
    document.getElementById('editCommentModal').style.display = 'none';
}

// Handle edit form submission FOR POSTS
document.getElementById('editPostForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const postId = document.getElementById('editPostId').value;
    const content = document.getElementById('editPostContent').value;
    const saveBtn = this.querySelector('.btn-modal-save');
    const originalText = saveBtn.innerHTML;
    
    if (!content.trim()) {
        showNotification('Le contenu ne peut pas être vide', 'error');
        return;
    }
    
    // Show loading state
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
    saveBtn.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('post_id', postId);
    formData.append('content', content);
    formData.append('key', 'admin123');
    
    // Send update request to admin endpoint
    fetch('update_post_admin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update post content in the table
            const postRow = document.getElementById('post-row-' + postId);
            const contentCell = postRow.querySelector('.post-content-preview');
            if (contentCell) {
                contentCell.textContent = content;
            }
            
            closeEditModal();
            showNotification('Publication modifiée avec succès!', 'success');
        } else {
            showNotification('Erreur: ' + (data.error || data.message), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur lors de la modification', 'error');
    })
    .finally(() => {
        // Reset button state
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
});

// Handle edit form submission FOR COMMENTS
document.getElementById('editCommentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const commentId = document.getElementById('editCommentId').value;
    const content = document.getElementById('editCommentContent').value;
    const saveBtn = this.querySelector('.btn-modal-save');
    const originalText = saveBtn.innerHTML;
    
    if (!content.trim()) {
        showNotification('Le contenu ne peut pas être vide', 'error');
        return;
    }
    
    // Show loading state
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
    saveBtn.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('comment_id', commentId);
    formData.append('content', content);
    formData.append('key', 'admin123');
    
    // Send update request to admin endpoint for comments
    fetch('updatecomment_admin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update comment content in the table
            const commentElement = document.querySelector(`.comment-item .comment-content`);
            if (commentElement) {
                commentElement.textContent = content;
            }
            
            closeCommentEditModal();
            showNotification('Commentaire modifié avec succès!', 'success');
        } else {
            showNotification('Erreur: ' + (data.error || data.message), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erreur lors de la modification', 'error');
    })
    .finally(() => {
        // Reset button state
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
});

// Export data function
function exportData() {
    const table = document.querySelector('.admin-table');
    let csv = [];
    
    // Headers
    const headers = [];
    table.querySelectorAll('thead th').forEach(th => {
        headers.push(th.textContent.trim());
    });
    csv.push(headers.join(','));
    
    // Data
    table.querySelectorAll('tbody tr:not([id^="comments-row-"])').forEach(row => {
        const rowData = [];
        row.querySelectorAll('td').forEach((cell, index) => {
            // Skip actions column for export
            if (index !== 5) { // actions column is 5th (0-indexed)
                let text = cell.textContent.trim();
                // Clean up text for CSV
                text = text.replace(/,/g, ';').replace(/\n/g, ' ');
                rowData.push(`"${text}"`);
            }
        });
        csv.push(rowData.join(','));
    });
    
    // Download CSV
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', `campus_connect_export_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showNotification('Export CSV généré avec succès!', 'success');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    const commentModal = document.getElementById('editCommentModal');
    
    if (event.target === modal) {
        closeEditModal();
    }
    if (event.target === commentModal) {
        closeCommentEditModal();
    }
}

// Notification function
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `admin-notification admin-notification-${type}`;
    notification.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: var(--admin-border-radius);
            color: white;
            font-weight: 500;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            ${type === 'success' ? 'background: linear-gradient(135deg, #27ae60, #219653);' : ''}
            ${type === 'error' ? 'background: linear-gradient(135deg, #e74c3c, #c0392b);' : ''}
            box-shadow: var(--admin-shadow-hover);
        ">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" style="margin-right: 10px;"></i>
            ${message}
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add CSS for notification animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
</body>
</html>