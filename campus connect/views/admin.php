<?php
require '../model/post.php';

// Simple protection with URL key (change 'admin123' to something secure)
if (!isset($_GET['key']) || $_GET['key'] !== 'admin123') {
    echo "<h2>Accès Refusé</h2>";
    exit();
}

// Handle delete post action
if (isset($_GET['delete_post'])) {
    $post_id = (int)$_GET['delete_post'];
    deletePost($post_id); // deletes post + its comments and likes
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

// Get all posts (including pending if you want)
$posts = getPosts(true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Campus Connect</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
    /* Modal Styles for Admin */
    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        display: none;
    }

    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        width: 50%;
        max-width: 500px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: #000;
    }

    .btn-cancel {
        background: #95a5a6;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin-left: 10px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-cancel:hover {
        background: #7f8c8d;
    }

    .edit-form {
        margin-top: 15px;
    }

    .edit-form textarea {
        width: 100%;
        height: 120px;
        padding: 12px;
        border: 1px solid #bdc3c7;
        border-radius: 4px;
        resize: vertical;
        margin-bottom: 10px;
    }
</style>
</head>
<body>
<!-- Simple Header -->
<div class="simple-header">
    <div class="header-container">
        <div class="logo-title">
            <img src="/campus connect/assets/images/logo.jpg" class="logo" alt="Logo">
            <span class="campus-title">CAMPUS CONNECT</span>
        </div>
        
        <nav class="simple-nav">
            <a href="../index.php" class="nav-item">Accueil</a>
            <a href="admin.php?key=admin123" class="nav-item active">Administration</a>
            <a href="#" class="nav-item">Ressources</a>
            <a href="#" class="nav-item">Evenements</a>
            <a href="#" class="nav-item">Messages</a>
            <a href="#" class="nav-item">Groupes</a>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="content-section">
        <h2 class="section-title">Administration - Gestion des Publications</h2>

        <?php
        if (empty($posts)) {
            echo "<p>Aucune publication trouvée.</p>";
        } else {
            foreach ($posts as $p): ?>
                <div class="post" id="post-<?= $p['id'] ?>">
                    <div class="post-content">
                        <p id="post-content-<?= $p['id'] ?>"><?= htmlspecialchars($p['content']); ?></p>
                    </div>
                    <div class="post-meta">
                        <span class="date">Publié le <?= $p['created_at']; ?></span>
                        <span class="likes">Likes: <?= countLikes($p['id']); ?></span>
                        <?php if (isset($p['user_id'])): ?>
                            <span class="user-id">User ID: <?= $p['user_id']; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="post-actions">
                        <!-- Edit Button -->
                        <button class="btn-edit" onclick="openEditModal(<?= $p['id'] ?>, '<?= htmlspecialchars($p['content'], ENT_QUOTES); ?>')">
                            ✏️ Modifier
                        </button>
                        
                        <!-- Delete Button -->
                        <a href="admin.php?key=admin123&delete_post=<?= $p['id']; ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette publication?');">
                            🗑️ Supprimer
                        </a>
                    </div>

                    <!-- Comments -->
                    <div class="comments">
                        <h4>Commentaires:</h4>
                        <?php
                        $comments = getComments($p['id']);
                        if (!empty($comments)) {
                            foreach ($comments as $c): ?>
                                <div class="comment">
                                    <div class="comment-meta">
                                        <span class="date"><?= $c['created_at']; ?></span>
                                        <?php if (isset($c['user_id'])): ?>
                                            <span class="user-id">User ID: <?= $c['user_id']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <p><?= htmlspecialchars($c['content']); ?></p>
                                    <a href="admin.php?key=admin123&delete_comment=<?= $c['id']; ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire?');">
                                        Supprimer le Commentaire 🗑️
                                    </a>
                                </div>
                            <?php endforeach;
                        } else {
                            echo "<p>Aucun commentaire.</p>";
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach;
        }
        ?>
    </div>
</div>

<!-- Edit Post Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>Modifier la publication</h3>
        <form id="editPostForm" class="edit-form" action="update_post.php" method="POST">
            <input type="hidden" id="editPostId" name="post_id">
            <input type="hidden" name="key" value="admin123">
            <textarea id="editPostContent" name="content" rows="4" required></textarea>
            <div>
                <button type="submit" class="btn">Enregistrer</button>
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
// Edit post functions
function openEditModal(postId, content) {
    document.getElementById('editPostId').value = postId;
    document.getElementById('editPostContent').value = content;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Handle edit form submission
document.getElementById('editPostForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('update_post.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update post content in DOM
            const postId = document.getElementById('editPostId').value;
            document.getElementById(`post-content-${postId}`).textContent = document.getElementById('editPostContent').value;
            closeEditModal();
            alert('Publication modifiée avec succès!');
        } else {
            alert('Erreur: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la modification');
    });
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeEditModal();
    }
}
</script>
</body>
</html>