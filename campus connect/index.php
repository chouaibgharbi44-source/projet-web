<?php
session_start();
// Initialize user session if not set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 'user_' . uniqid(); // Generate unique user ID
}

require_once 'model/post.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Connect</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="main-content">
        <div class="content-section">
            <h2 class="section-title">Bienvenue sur Campus Connect</h2>
            <p>Plateforme de partage et de communication pour la communauté universitaire.</p>
        </div>

        <div class="content-section">
            <h2 class="section-title">Publications Récentes</h2>
            
            <!-- Post Form -->
            <div class="post-form">
                <form action="views/addpost.php" method="POST">
                    <textarea name="content" placeholder="Partagez quelque chose avec la communauté..." required></textarea>
                    <button type="submit" class="btn">Publier</button>
                </form>
            </div>
            
            <!-- Posts List -->
            <div id="postsContainer">
                <?php
                $posts = getPosts();
                if (empty($posts)) {
                    echo "<p>Aucune publication pour le moment.</p>";
                } else {
                    foreach ($posts as $p): 
                        // Check if current user owns this post
                        $is_owner = isset($_SESSION['user_id']) && isset($p['user_id']) && $p['user_id'] == $_SESSION['user_id'];
                ?>
                        <div class="post" id="post-<?= $p['id'] ?>">
                            <div class="post-content">
                                <p id="post-content-<?= $p['id'] ?>"><?= htmlspecialchars($p['content']); ?></p>
                            </div>
                            <div class="post-meta">
                                <span class="date">Publié le <?= $p['created_at']; ?></span>
                                <span class="likes">Likes: <?= countLikes($p['id']); ?></span>
                                <?php if (isset($p['user_id'])): ?>
                                    <span class="user-badge">👤 <?= substr($p['user_id'], 0, 8) ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="post-actions">
                                <!-- Like Button -->
                                <a href="views/like.php?post_id=<?= $p['id']; ?>" class="btn-like">
                                    👍 Like
                                </a>
                                
                                <!-- Edit Button (only for post owner) -->
                                <?php if ($is_owner): ?>
                                    <button class="btn-edit" onclick="openEditModal(<?= $p['id'] ?>)">
                                        ✏️ Modifier
                                    </button>
                                <?php endif; ?>
                                
                                <!-- Delete Button (only for post owner) -->
                                <?php if ($is_owner): ?>
                                    <button class="btn-delete" onclick="deletePost(<?= $p['id'] ?>)">
                                        🗑️ Supprimer
                                    </button>
                                <?php endif; ?>
                            </div>

                            <!-- Comments Section -->
                            <div class="comments">
                                <h4>Commentaires:</h4>
                                <?php
                                $comments = getComments($p['id']);
                                if (!empty($comments)) {
                                    foreach ($comments as $c): 
                                        // Check if current user owns this comment
                                        $comment_is_owner = isset($_SESSION['user_id']) && isset($c['user_id']) && $c['user_id'] == $_SESSION['user_id'];
                                ?>
                                        <div class="comment">
                                            <div class="comment-meta">
                                                <span class="date"><?= $c['created_at']; ?></span>
                                                <?php if (isset($c['user_id'])): ?>
                                                    <span class="user-badge">👤 <?= substr($c['user_id'], 0, 8) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <p><?= htmlspecialchars($c['content']); ?></p>
                                            
                                            <!-- Delete comment button (only for comment owner) -->
                                            <?php if ($comment_is_owner): ?>
                                                <button class="btn-delete" onclick="deleteComment(<?= $c['id'] ?>)" style="font-size: 12px; padding: 4px 8px;">
                                                    Supprimer
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach;
                                } else {
                                    echo "<p>Aucun commentaire.</p>";
                                }
                                ?>
                                
                                <!-- Add Comment Form -->
                                <form action="views/addcomment.php" method="POST" class="comment-form">
                                    <input type="hidden" name="post_id" value="<?= $p['id']; ?>">
                                    <input type="text" name="content" placeholder="Ajouter un commentaire..." required>
                                    <button type="submit">Commenter</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach;
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Modifier la publication</h3>
            <form id="editPostForm">
                <input type="hidden" id="editPostId">
                <textarea id="editPostContent" rows="4" required></textarea>
                <div style="margin-top: 15px;">
                    <button type="submit" class="btn">Enregistrer</button>
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    
    <style>
        .user-badge {
            font-size: 12px;
            color: #7f8c8d;
            background: #ecf0f1;
            padding: 2px 6px;
            border-radius: 3px;
            margin-left: 10px;
        }
    </style>
</body>
</html>