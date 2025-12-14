<?php
session_start();
// Initialize user session if not set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Use a simple numeric ID for demo
    $_SESSION['username'] = 'Étudiant';
}

// The model is in the model/ directory relative to the root
require_once 'model/post.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Connect - Accueil</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/edit-modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Modern pink theme using Poppins */
        :root {
            --primary: #7b2da8; /* purple */
            --secondary: #ff6fb1; /* rose */
            --dark: #0b2545; /* navy blue */
            --light: #f8f9fa;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --success: #4cc9f0;
            --warning: #f8961e;
            --danger: #e63946;
            --border-radius: 12px;
            --shadow: 0 8px 30px rgba(0,0,0,0.08);
            --shadow-hover: 0 12px 40px rgba(0,0,0,0.12);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: radial-gradient(1200px 600px at 10% 10%, rgba(123, 45, 168, 0.08), transparent 12%),
                        radial-gradient(1000px 500px at 90% 90%, rgba(255, 111, 177, 0.06), transparent 12%),
                        linear-gradient(135deg, #fffafc 0%, #fff 100%);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* Header & Navigation */
        .header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding: 16px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .header-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 22px;
            background: linear-gradient(135deg, #7b2da8, #ff6fb1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
            text-shadow: 0 10px 20px rgba(123, 45, 168, 0.15);
        }

        .simple-nav {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-item {
            text-decoration: none;
            color: #5b3b4a;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            position: relative;
            padding: 8px 0;
        }

        .nav-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #7b2da8, #ff6fb1);
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .nav-item:hover {
            color: #7b2da8;
        }

        .nav-item:hover::after,
        .nav-item.active::after {
            width: 100%;
        }

        .nav-item.active {
            color: #7b2da8;
        }

        .nav-item.admin-link {
            background: linear-gradient(135deg, rgba(123, 45, 168, 0.1), rgba(255, 111, 177, 0.1));
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid rgba(123, 45, 168, 0.2);
            color: #7b2da8;
            font-weight: 700;
        }

        .nav-item.admin-link:hover {
            background: linear-gradient(135deg, rgba(123, 45, 168, 0.2), rgba(255, 111, 177, 0.2));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(123, 45, 168, 0.15);
        }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Welcome Hero Section - Updated colors */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 60px 0;
            border-radius: var(--border-radius);
            margin-bottom: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,202.7C672,203,768,181,864,181.3C960,181,1056,203,1152,202.7C1248,203,1344,181,1392,170.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center no-repeat;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero-title {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(to right, #ffffff, #f8f9fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
            min-width: 120px;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Post Form Modern Design - Updated with your button style */
        .modern-post-form {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: var(--shadow);
            border: 1px solid var(--light-gray);
        }

        .form-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .form-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        .form-title {
            font-size: 1.3rem;
            color: var(--dark);
            font-weight: 600;
        }

        .modern-post-form textarea {
            width: 100%;
            min-height: 120px;
            padding: 20px;
            border: 2px solid var(--light-gray);
            border-radius: var(--border-radius);
            font-size: 1rem;
            resize: vertical;
            transition: var(--transition);
            font-family: inherit;
        }

        .modern-post-form textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 45, 168, 0.1);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-publish {
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 12px 28px rgba(255, 61, 158, 0.14);
        }

        .btn-publish:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 40px rgba(255, 61, 158, 0.18);
        }

        /* Modern Posts Design */
        .modern-post {
            background: white;
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
            border: 1px solid var(--light-gray);
            transition: var(--transition);
        }

        .modern-post:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .post-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1rem;
        }

        .user-info h4 {
            margin: 0 0 5px 0;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .user-info .post-time {
            color: var(--gray);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .post-content {
            font-size: 1.1rem;
            line-height: 1.7;
            color: var(--dark);
            margin-bottom: 25px;
            white-space: pre-wrap;
        }

        .post-stats {
            display: flex;
            gap: 25px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--light-gray);
        }

        .stat {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray);
            font-size: 0.95rem;
        }

        .stat i {
            font-size: 1.1rem;
        }

        .post-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 8px 20px;
            border-radius: 50px;
            border: none;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-like {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success);
            border: 1px solid rgba(76, 201, 240, 0.2);
        }

        .btn-like:hover {
            background: rgba(76, 201, 240, 0.2);
        }

        .btn-edit {
            background: rgba(123, 45, 168, 0.1);
            color: var(--primary);
            border: 1px solid rgba(123, 45, 168, 0.2);
        }

        .btn-edit:hover {
            background: rgba(123, 45, 168, 0.2);
        }

        .btn-delete {
            background: rgba(230, 57, 70, 0.1);
            color: var(--danger);
            border: 1px solid rgba(230, 57, 70, 0.2);
        }

        .btn-delete:hover {
            background: rgba(230, 57, 70, 0.2);
        }

        /* Comments Section */
        .comments-section {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--light-gray);
        }

        .comments-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modern-comment {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary);
            position: relative;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .comment-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .comment-avatar {
            width: 35px;
            height: 35px;
            background: var(--light-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            font-weight: bold;
            font-size: 0.9rem;
        }

        .comment-info h5 {
            margin: 0;
            font-size: 0.95rem;
            color: var(--dark);
        }

        .comment-time {
            font-size: 0.85rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .comment-content {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--dark);
            margin-bottom: 10px;
            word-wrap: break-word;
        }

        .comment-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-comment-action {
            background: none;
            border: 1px solid var(--light-gray);
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-comment-edit {
            color: var(--primary);
            border-color: rgba(123, 45, 168, 0.2);
            background: rgba(123, 45, 168, 0.1);
        }

        .btn-comment-edit:hover {
            background: rgba(123, 45, 168, 0.2);
        }

        .btn-comment-delete {
            color: var(--danger);
            border-color: rgba(230, 57, 70, 0.2);
            background: rgba(230, 57, 70, 0.1);
        }

        .btn-comment-delete:hover {
            background: rgba(230, 57, 70, 0.2);
        }

        .add-comment-form {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .add-comment-form input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid var(--light-gray);
            border-radius: 50px;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .add-comment-form input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 45, 168, 0.1);
        }

        .btn-comment {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-comment:hover {
            background: var(--secondary);
        }

        /* No Posts Message */
        .no-posts {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
        }

        .no-posts-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--light-gray);
        }

        /* Modal Styles */
        .modal {
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

        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 40px;
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-hover);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-title {
            font-size: 1.5rem;
            color: var(--dark);
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--danger);
        }

        .modal-body textarea {
            width: 100%;
            min-height: 150px;
            padding: 20px;
            border: 2px solid var(--light-gray);
            border-radius: var(--border-radius);
            font-size: 1rem;
            resize: vertical;
            transition: var(--transition);
            font-family: inherit;
            margin-bottom: 25px;
        }

        .modal-body textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(123, 45, 168, 0.1);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .btn-save, .btn-cancel {
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }

        .btn-save {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .btn-cancel {
            background: var(--light-gray);
            color: var(--gray);
        }

        .btn-cancel:hover {
            background: #dee2e6;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-inner {
                flex-direction: column;
                gap: 16px;
            }

            .simple-nav {
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px;
            }

            .nav-item {
                font-size: 12px;
                padding: 6px 0;
            }

            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-stats {
                gap: 15px;
            }
            
            .stat-item {
                min-width: 100px;
                padding: 15px;
            }
            
            .modern-post {
                padding: 20px;
            }
            
            .post-header {
                flex-direction: column;
                gap: 15px;
            }
            
            .modal-content {
                padding: 25px;
                margin: 20% auto;
            }
            
            .add-comment-form {
                flex-direction: column;
            }
            
            .btn-action {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
            
            .comment-actions {
                flex-wrap: wrap;
            }
        }

        /* Feed Title */
        h2[style*="margin-bottom"] {
            margin-bottom: 25px !important;
            color: var(--dark) !important;
            font-size: 1.5rem !important;
            font-weight: 600 !important;
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }
    </style>
</head>
<body>
    <!-- Header with Navigation -->
    <div class="header">
        <div class="header-inner">
            <div class="logo">Campus Connect</div>
            
            <nav class="simple-nav">
                <a href="index.php" class="nav-item active">Accueil</a>
                <a href="#" class="nav-item">Ressources</a>
                <a href="#" class="nav-item">Événements</a>
                <a href="view/Front-office/messages.php" class="nav-item">Messages</a>
                <a href="view/Front-office/group_messages.php" class="nav-item">Groupes</a>
                <a href="#" class="nav-item">Profil</a>
                
                <!-- Admin Panel Link -->
                <a href="view/Back-office/admin.php?key=admin123" class="nav-item admin-link">
                    <i class="fas fa-cog"></i> Admin Panel
                </a>
            </nav>
        </div>
    </div>

    <div class="main-content">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Bienvenue sur Campus Connect</h1>
                <p class="hero-subtitle">
                    La plateforme de partage et de communication dédiée à la communauté universitaire. 
                    Échangez, collaborez et restez connecté avec vos pairs.
                </p>
                
                <div class="hero-stats">
                    <?php
                    // Utilise la fonction avec jointures pour les stats
                    $posts = getPostsWithFullDetails();
                    $total_posts = count($posts);
                    $total_comments = 0;
                    $total_likes = 0;
                    
                    foreach ($posts as $post) {
                        $total_comments += ($post['comment_count'] ?? 0);
                        $total_likes += ($post['like_count'] ?? 0);
                    }
                    ?>
                    <div class="stat-item">
                        <div class="stat-number"><?= $total_posts ?></div>
                        <div class="stat-label">Publications</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $total_comments ?></div>
                        <div class="stat-label">Commentaires</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $total_likes ?></div>
                        <div class="stat-label">Réactions</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24h</div>
                        <div class="stat-label">En ligne</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Post Form -->
        <div class="modern-post-form">
            <div class="form-header">
                <div class="form-avatar">
                    <?= substr($_SESSION['username'], 0, 1) ?>
                </div>
                <div class="form-title">
                    Créer une nouvelle publication
                </div>
            </div>
            <!-- Since index.php is in root, the action needs to point to view/Front-office/ -->
            <form action="view/Front-office/addpost.php" method="POST" id="postForm">
                <textarea name="content" id="postContent" placeholder="De quoi souhaitez-vous parler aujourd'hui ? Partagez vos idées, questions ou découvertes avec la communauté..." required></textarea>
                <div class="form-actions">
                    <button type="submit" class="btn-publish">
                        <i class="fas fa-paper-plane"></i> Publier
                    </button>
                </div>
            </form>
        </div>

        <!-- Posts Feed -->
        <h2 class="section-title">
            <i class="fas fa-stream"></i> Fil d'actualité
        </h2>

        <div id="postsContainer">
            <?php
            // Utilise la fonction avec JOINTURES SQL
            $posts = getPostsWithFullDetails();
            if (empty($posts)): ?>
                <div class="no-posts">
                    <div class="no-posts-icon">
                        <i class="far fa-comment-dots"></i>
                    </div>
                    <h3>Aucune publication pour le moment</h3>
                    <p>Soyez le premier à partager quelque chose avec la communauté !</p>
                </div>
            <?php else:
                foreach ($posts as $p): 
                    $is_owner = isset($_SESSION['user_id']) && isset($p['user_id']) && 
                               (strval($p['user_id']) === strval($_SESSION['user_id']));
                    
                    $post_comments = getComments($p['id']);
                    $comment_count = $p['comment_count'] ?? 0;
                    $post_likes = $p['like_count'] ?? 0;
            ?>
                    <div class="modern-post" id="post-<?= $p['id'] ?>">
                        <div class="post-header">
                            <div class="post-user">
                                <div class="user-avatar">
                                    <?= isset($p['author_name']) ? substr($p['author_name'], 0, 1) : '?' ?>
                                </div>
                                <div class="user-info">
                                    <h4><?= isset($p['author_name']) ? htmlspecialchars($p['author_name']) : 'Anonyme' ?></h4>
                                    <div class="post-time">
                                        <i class="far fa-clock"></i>
                                        <?= date('d/m/Y à H:i', strtotime($p['created_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="post-content" id="post-content-<?= $p['id'] ?>">
                            <?= nl2br(htmlspecialchars($p['content'])) ?>
                        </div>

                        <div class="post-stats">
                            <div class="stat">
                                <i class="fas fa-heart" style="color: var(--danger);"></i>
                                <span><?= $post_likes ?> j'aime</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-comment" style="color: var(--primary);"></i>
                                <span><?= $comment_count ?> commentaire<?= $comment_count > 1 ? 's' : '' ?></span>
                            </div>
                        </div>

                        <div class="post-actions">
                            <!-- Since index.php is in root, the href needs to point to view/Front-office/ -->
                            <a href="view/Front-office/like.php?post_id=<?= $p['id']; ?>" class="btn-action btn-like">
                                <i class="fas fa-heart"></i> J'aime
                            </a>
                            
                            <?php if ($is_owner): ?>
                                <button class="btn-action btn-edit" onclick="openEditModal(<?= $p['id'] ?>)">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                                <!-- Since index.php is in root, the onclick function needs the correct path -->
                                <button class="btn-action btn-delete" onclick="deletePost(<?= $p['id'] ?>)">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Comments Section -->
                        <?php if ($comment_count > 0): ?>
                            <div class="comments-section">
                                <h4 class="comments-title">
                                    <i class="fas fa-comments"></i> Commentaires (<?= $comment_count ?>)
                                </h4>
                                
                                <?php foreach ($post_comments as $c): 
                                    $comment_is_owner = isset($_SESSION['user_id']) && isset($c['user_id']) && 
                                                       (strval($c['user_id']) === strval($_SESSION['user_id']));
                                ?>
                                    <div class="modern-comment" data-id="<?= $c['id'] ?>">
                                        <div class="comment-header">
                                            <div class="comment-user">
                                                <div class="comment-avatar">
                                                    <?= isset($c['username']) ? substr($c['username'], 0, 1) : '?' ?>
                                                </div>
                                                <div class="comment-info">
                                                    <h5><?= isset($c['username']) ? htmlspecialchars($c['username']) : 'Anonyme' ?></h5>
                                                    <div class="comment-time">
                                                        <i class="far fa-clock"></i>
                                                        <?= date('H:i', strtotime($c['created_at'])) ?>
                                                        <?php if ($comment_is_owner): ?>
                                                            <span class="edited">(vous)</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment-content">
                                            <?= htmlspecialchars($c['content']) ?>
                                        </div>
                                        
                                        <?php if ($comment_is_owner): ?>
                                            <div class="comment-actions">
                                                <button class="btn-comment-action btn-comment-edit" onclick="editComment(<?= $c['id'] ?>)">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </button>
                                                <!-- Since index.php is in root, the onclick function needs the correct path -->
                                                <button class="btn-comment-action btn-comment-delete" onclick="deleteComment(<?= $c['id'] ?>)">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Add Comment Form -->
                        <!-- Since index.php is in root, the action needs to point to view/Front-office/ -->
                        <form action="view/Front-office/addcomment.php" method="POST" class="add-comment-form" data-post-id="<?= $p['id'] ?>">
                            <input type="hidden" name="post_id" value="<?= $p['id']; ?>">
                            <input type="text" name="content" class="comment-input" placeholder="Ajouter un commentaire..." required>
                            <button type="submit" class="btn-comment">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Modifier la publication</h3>
                <button class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editPostForm">
                    <input type="hidden" id="editPostId">
                    <textarea id="editPostContent" rows="4" required placeholder="Modifiez votre publication ici..."></textarea>
                    <div class="modal-actions">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <button type="button" class="btn-cancel" onclick="closeEditModal()">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Since index.php is in root, the src needs to point to assets/ -->
    <script src="assets/js/validation.js"></script>
    <script src="assets/js/edit-functions.js"></script>
    <script>
    // Validation pour le formulaire de publication
    document.addEventListener('DOMContentLoaded', function() {
        const postForm = document.getElementById('postForm');
        const postContent = document.getElementById('postContent');
        
        if (postForm && postContent) {
            InputValidator.setupCharacterCounter(postContent, 5000);
            
            postContent.addEventListener('input', function() {
                const validation = InputValidator.validatePost(this.value);
                if (!validation.valid) {
                    InputValidator.showError(this, validation.message);
                } else {
                    InputValidator.showSuccess(this);
                }
            });
            
            postForm.addEventListener('submit', function(e) {
                const content = postContent.value.trim();
                const validation = InputValidator.validatePost(content);
                
                if (!validation.valid) {
                    e.preventDefault();
                    InputValidator.showError(postContent, validation.message);
                    postContent.focus();
                }
            });
        }
        
        // Validation pour les formulaires de commentaires
        const commentForms = document.querySelectorAll('.add-comment-form');
        commentForms.forEach(form => {
            const input = form.querySelector('.comment-input');
            if (input) {
                InputValidator.setupCharacterCounter(input, 1000);
                
                input.addEventListener('input', function() {
                    const validation = InputValidator.validateComment(this.value);
                    if (!validation.valid) {
                        InputValidator.showError(this, validation.message);
                    } else {
                        InputValidator.showSuccess(this);
                    }
                });
                
                form.addEventListener('submit', function(e) {
                    const content = input.value.trim();
                    const validation = InputValidator.validateComment(content);
                    
                    if (!validation.valid) {
                        e.preventDefault();
                        InputValidator.showError(input, validation.message);
                        input.focus();
                    }
                });
            }
        });
    });

    // Edit post functions
    function openEditModal(postId) {
        const currentContent = document.getElementById('post-content-' + postId).textContent;
        document.getElementById('editPostId').value = postId;
        document.getElementById('editPostContent').value = currentContent;
        document.getElementById('editModal').style.display = 'block';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Handle edit form submission
    // Since index.php is in root, the fetch needs to point to view/Front-office/
    document.getElementById('editPostForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const postId = document.getElementById('editPostId').value;
        const content = document.getElementById('editPostContent').value;
        
        fetch('view/Front-office/update_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'post_id=' + encodeURIComponent(postId) + '&content=' + encodeURIComponent(content)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('post-content-' + postId).textContent = content;
                closeEditModal();
                showNotification('Publication modifiée avec succès!', 'success');
            } else {
                showNotification('Erreur: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Erreur lors de la modification', 'error');
        });
    });

    // Delete post function - UPDATED PATH
    function deletePost(postId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette publication ? Cette action est irréversible.')) {
            // Since index.php is in root, the href needs to point to view/Front-office/
            window.location.href = 'view/Front-office/deletepost.php?post_id=' + postId;
        }
    }

    // Delete comment function - UPDATED PATH
    function deleteComment(commentId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
            // Since index.php is in root, the href needs to point to view/Front-office/
            window.location.href = 'view/Front-office/deletecomment.php?comment_id=' + commentId;
        }
    }

    // Edit comment function
    function editComment(commentId) {
        if (window.editManager) {
            window.editManager.openCommentEditModal(commentId);
        }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeEditModal();
        }
    }

    // Notification function
    function showNotification(message, type) {
        if (window.editManager && window.editManager.showNotification) {
            window.editManager.showNotification(message, type);
            return;
        }
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 25px;
                border-radius: var(--border-radius);
                color: white;
                font-weight: 500;
                z-index: 9999;
                animation: slideIn 0.3s ease;
                ${type === 'success' ? 'background: linear-gradient(135deg, #2ecc71, #27ae60);' : ''}
                ${type === 'error' ? 'background: linear-gradient(135deg, #e74c3c, #c0392b);' : ''}
                box-shadow: var(--shadow);
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
        
        .validation-error {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            padding: 5px;
            background: #ffeaea;
            border-radius: 4px;
            border-left: 3px solid #e74c3c;
        }
        
        .validation-success {
            color: #2ecc71;
            font-size: 12px;
            margin-top: 5px;
            padding: 5px;
            background: #eaffea;
            border-radius: 4px;
            border-left: 3px solid #2ecc71;
        }
        
        .char-counter {
            font-size: 12px;
            color: #666;
            text-align: right;
            margin-top: 5px;
        }
        
        .char-counter .error {
            color: #e74c3c;
            font-weight: bold;
        }
        
        /* Header animations */
        .header::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 6px;
            background: linear-gradient(90deg, rgba(123, 45, 168, 0.6), rgba(255, 111, 177, 0.5), rgba(11, 37, 69, 0.4));
            opacity: 0.8;
            transform-origin: left center;
            animation: slideGradient 6s linear infinite;
        }
        
        @keyframes slideGradient {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(0%); }
            100% { transform: translateX(100%); }
        }
    `;
    document.head.appendChild(style);
    </script>
</body>
</html>