<?php
require '../../model/post.php';

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Poppins Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
    /* Modern pink theme using Poppins and 3-color gradient */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root {
        --purple-1: #7b2da8;    /* deep purple */
        --rose-1: #ff6fb1;      /* vivid rose */
        --dark-blue: #0b2545;   /* deep navy/blue */
        --muted: #3a2a3a;
        --surface: rgba(255, 255, 255, 0.9);
        --glass: rgba(255, 255, 255, 0.6);
        --admin-border-radius: 14px;
        --admin-shadow: 0 10px 30px rgba(15, 10, 15, 0.04);
        --admin-shadow-hover: 0 20px 50px rgba(123, 45, 168, 0.12);
        --admin-transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    * {
        box-sizing: border-box;
        font-family: 'Poppins', Arial, sans-serif;
    }

    body {
        margin: 0;
        padding: 0;
        color: var(--muted);
        background: radial-gradient(1200px 600px at 10% 10%, rgba(123, 45, 168, 0.08), transparent 12%),
                    radial-gradient(1000px 500px at 90% 90%, rgba(255, 111, 177, 0.06), transparent 12%),
                    linear-gradient(135deg, #fffafc 0%, #fff 100%);
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
    }

    /* Admin Header */
    .admin-header {
        background: linear-gradient(90deg, var(--purple-1), var(--rose-1), var(--dark-blue));
        color: white;
        padding: 25px 0;
        margin-bottom: 30px;
        border-radius: 0 0 var(--admin-border-radius) var(--admin-border-radius);
        box-shadow: 0 8px 30px rgba(255, 77, 140, 0.12);
        position: relative;
        overflow: hidden;
    }

    .admin-header::after {
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

    .admin-header-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .admin-title {
        font-size: 1.8rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 15px;
        letter-spacing: 0.2px;
    }

    .admin-title i {
        font-size: 2rem;
        color: white;
        opacity: 0.9;
    }

    .admin-breadcrumb {
        font-size: 0.95rem;
        opacity: 0.9;
        margin-top: 8px;
    }

    .admin-breadcrumb a {
        color: white;
        text-decoration: none;
        opacity: 0.8;
        transition: var(--admin-transition);
        font-weight: 500;
    }

    .admin-breadcrumb a:hover {
        opacity: 1;
        text-decoration: underline;
    }

    /* Admin Dashboard */
    .admin-dashboard {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 28px;
    }

    /* Stats Cards */
    .admin-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: var(--surface);
        border-radius: var(--admin-border-radius);
        padding: 25px;
        box-shadow: var(--admin-shadow);
        border: 1px solid rgba(255, 100, 150, 0.06);
        transition: var(--admin-transition);
        position: relative;
        overflow: hidden;
        animation: float 8s ease-in-out infinite;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--purple-1), var(--rose-1));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--admin-shadow-hover);
        border-color: rgba(255, 111, 177, 0.2);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
        100% { transform: translateY(0px); }
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .stat-title {
        font-size: 0.95rem;
        color: var(--muted);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .stat-icon {
        font-size: 1.8rem;
        background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--dark-blue);
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .stat-change {
        font-size: 0.9rem;
        color: #27ae60;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }

    .stat-change.negative {
        color: #e74c3c;
    }

    /* Admin Content */
    .admin-content {
        background: var(--surface);
        border-radius: var(--admin-border-radius);
        box-shadow: var(--admin-shadow);
        overflow: hidden;
        margin-bottom: 50px;
        border: 1px solid rgba(255, 100, 150, 0.06);
    }

    .admin-content:hover {
        border-color: rgba(255, 111, 177, 0.2);
    }

    .content-header {
        background: linear-gradient(90deg, rgba(123, 45, 168, 0.06), rgba(255, 111, 177, 0.04));
        padding: 24px 30px;
        border-bottom: 1px solid rgba(255, 100, 150, 0.04);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .content-title {
        font-size: 1.4rem;
        color: var(--dark-blue);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .content-title i {
        background: linear-gradient(135deg, var(--purple-1), var(--rose-1));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .content-actions {
        display: flex;
        gap: 12px;
    }

    .btn-admin {
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--admin-transition);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.2px;
        position: relative;
        overflow: hidden;
    }

    .btn-admin:after {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.14);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 420ms ease, height 420ms ease, opacity 420ms ease;
        opacity: 0;
    }

    .btn-admin:active:after {
        width: 260px;
        height: 260px;
        opacity: 1;
        transition: 0s;
    }

    .btn-refresh {
        background: transparent;
        color: var(--muted);
        border: 1px solid rgba(80, 40, 60, 0.06);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    .btn-refresh:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .btn-export {
        background: linear-gradient(90deg, var(--rose-1), var(--purple-1));
        color: white;
        box-shadow: 0 12px 28px rgba(255, 61, 158, 0.14);
    }

    .btn-export:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 40px rgba(255, 61, 158, 0.18);
    }

    /* Posts Table */
    .admin-table-container {
        overflow-x: auto;
        padding: 20px;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
        border-radius: 10px;
        overflow: hidden;
    }

    .admin-table thead {
        background: linear-gradient(90deg, rgba(123, 45, 168, 0.06), rgba(255, 111, 177, 0.04));
    }

    .admin-table th {
        padding: 18px 20px;
        text-align: left;
        font-weight: 600;
        color: var(--dark-blue);
        border-bottom: 1px solid rgba(255, 100, 150, 0.04);
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-table tbody tr {
        border-bottom: 1px solid rgba(255, 100, 150, 0.04);
        transition: var(--admin-transition);
        opacity: 0;
        transform: translateY(8px);
        animation: rowIn 420ms ease forwards;
    }

    .admin-table tbody tr:nth-child(1) { animation-delay: 0.06s; }
    .admin-table tbody tr:nth-child(2) { animation-delay: 0.12s; }
    .admin-table tbody tr:nth-child(3) { animation-delay: 0.18s; }
    .admin-table tbody tr:nth-child(4) { animation-delay: 0.24s; }
    .admin-table tbody tr:nth-child(5) { animation-delay: 0.30s; }
    .admin-table tbody tr:nth-child(6) { animation-delay: 0.36s; }
    .admin-table tbody tr:nth-child(7) { animation-delay: 0.42s; }
    .admin-table tbody tr:nth-child(8) { animation-delay: 0.48s; }
    .admin-table tbody tr:nth-child(9) { animation-delay: 0.54s; }
    .admin-table tbody tr:nth-child(10) { animation-delay: 0.60s; }

    @keyframes rowIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .admin-table tbody tr:hover {
        transform: translateX(6px);
        background: linear-gradient(90deg, rgba(255, 250, 253, 0.8), rgba(255, 240, 250, 0.8));
        transition: background 220ms ease, transform 220ms ease;
    }

    .admin-table tr:nth-child(even) td {
        background: rgba(255, 240, 250, 0.6);
    }

    .admin-table td {
        padding: 20px;
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
        font-size: 14px;
    }

    .post-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
        font-size: 0.85rem;
        color: var(--muted);
    }

    .post-date, .post-user {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        padding: 10px 16px;
        border-radius: 10px;
        border: none;
        font-size: 0.85rem;
        cursor: pointer;
        transition: var(--admin-transition);
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        letter-spacing: 0.2px;
        position: relative;
        overflow: hidden;
    }

    .btn-action:after {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.14);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 420ms ease, height 420ms ease, opacity 420ms ease;
        opacity: 0;
    }

    .btn-action:active:after {
        width: 260px;
        height: 260px;
        opacity: 1;
        transition: 0s;
    }

    .btn-view {
        background: rgba(52, 152, 219, 0.1);
        color: var(--dark-blue);
        border: 1px solid rgba(52, 152, 219, 0.2);
    }

    .btn-view:hover {
        background: rgba(52, 152, 219, 0.2);
        transform: translateY(-2px);
    }

    .btn-edit {
        background: rgba(255, 111, 177, 0.1);
        color: var(--rose-1);
        border: 1px solid rgba(255, 111, 177, 0.2);
    }

    .btn-edit:hover {
        background: rgba(255, 111, 177, 0.2);
        transform: translateY(-2px);
    }

    .btn-delete {
        background: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
        border: 1px solid rgba(231, 76, 60, 0.2);
    }

    .btn-delete:hover {
        background: rgba(231, 76, 60, 0.2);
        transform: translateY(-2px);
    }

    /* Comments Section */
    .comments-section {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 100, 150, 0.04);
    }

    .comment-item {
        padding: 16px;
        background: rgba(255, 240, 250, 0.6);
        border-radius: 12px;
        margin-bottom: 12px;
        border-left: 3px solid var(--rose-1);
        transition: var(--admin-transition);
    }

    .comment-item:hover {
        background: rgba(255, 240, 250, 0.8);
        transform: translateX(4px);
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .comment-user {
        font-size: 0.85rem;
        color: var(--dark-blue);
        font-weight: 600;
    }

    .comment-date {
        font-size: 0.8rem;
        color: var(--muted);
    }

    .comment-content {
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 10px;
        color: #333;
    }

    .comment-actions {
        text-align: right;
    }

    /* Modal Styles */
    .admin-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        animation: fadeIn 200ms ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .admin-modal-content {
        background: linear-gradient(145deg, #ffffff, #fdfaff);
        margin: 60px auto;
        padding: 40px;
        border-radius: 24px;
        width: 90%;
        max-width: 550px;
        box-shadow: 0 25px 80px rgba(123, 45, 168, 0.25);
        animation: slideUp 300ms cubic-bezier(0.16, 1, 0.3, 1);
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    @keyframes slideUp {
        from {
            transform: translateY(40px) scale(0.95);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    .admin-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 100, 150, 0.04);
    }

    .admin-modal-title {
        font-size: 1.5rem;
        background: linear-gradient(90deg, var(--purple-1), var(--rose-1));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .admin-modal-close {
        background: none;
        border: none;
        font-size: 1.8rem;
        color: var(--muted);
        cursor: pointer;
        transition: var(--admin-transition);
        padding: 5px;
    }

    .admin-modal-close:hover {
        color: var(--rose-1);
        transform: rotate(90deg);
    }

    .admin-modal-body textarea {
        width: 100%;
        min-height: 150px;
        padding: 20px;
        border: 2px solid rgba(123, 45, 168, 0.15);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.95);
        font-size: 1rem;
        resize: vertical;
        transition: var(--admin-transition);
        margin-bottom: 20px;
        font-family: 'Poppins', sans-serif;
        color: var(--muted);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
    }

    .admin-modal-body textarea:focus {
        outline: none;
        border-color: var(--rose-1);
        background: white;
        box-shadow: 0 0 0 4px rgba(255, 111, 177, 0.15);
        transform: translateY(-2px);
    }

    .admin-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 10px;
    }

    .btn-modal-save {
        background: linear-gradient(90deg, var(--rose-1), var(--purple-1));
        color: white;
        padding: 14px 30px;
        border-radius: 12px;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--admin-transition);
        box-shadow: 0 12px 28px rgba(255, 61, 158, 0.14);
        letter-spacing: 0.2px;
        position: relative;
        overflow: hidden;
    }

    .btn-modal-save:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 40px rgba(255, 61, 158, 0.18);
    }

    .btn-modal-cancel {
        background: transparent;
        color: var(--muted);
        padding: 14px 30px;
        border-radius: 12px;
        border: 1px solid rgba(80, 40, 60, 0.06);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--admin-transition);
        letter-spacing: 0.2px;
    }

    .btn-modal-cancel:hover {
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--muted);
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        color: rgba(123, 45, 168, 0.1);
    }

    /* Responsive Design */
    @media screen and (max-width: 900px) {
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
            padding: 30px;
            margin: 20% auto;
            width: 95%;
        }

        .action-buttons {
            flex-wrap: wrap;
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
                <a href="../../index.php">Accueil</a> / 
                <span>Tableau de bord Administrateur</span>
            </div>
        </div>
        <div style="color: rgba(255,255,255,0.9); font-size: 0.9rem; font-weight: 500;">
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

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Commentaires</div>
                <div class="stat-icon"><i class="fas fa-comments"></i></div>
            </div>
            <div class="stat-value"><?= $total_comments ?></div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> <?= $total_comments > 0 ? round($total_comments * 0.08) : 0 ?> cette semaine
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Utilisateurs Actifs</div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-value"><?= count($posts) > 0 ? count(array_unique(array_column($posts, 'user_id'))) : 0 ?></div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> <?= count($posts) > 0 ? round(count(array_unique(array_column($posts, 'user_id'))) * 0.15) : 0 ?> cette semaine
            </div>
        </div>

        <div class="stat-card">
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
                <span style="font-size: 0.9rem; color: var(--muted); margin-left: 10px; font-weight: 500;">
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
                            $comment_count = $p['comment_count'] ?? 0;
                            $like_count = $p['like_count'] ?? 0;
                            $post_comments = getComments($p['id']);
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
                                        <div><i class="fas fa-heart" style="color: var(--rose-1);"></i> <?= $like_count ?> likes</div>
                                        <div><i class="fas fa-comment" style="color: var(--purple-1);"></i> <?= $comment_count ?> commentaires</div>
                                        <div style="font-size: 0.8rem; color: var(--muted); font-weight: 500;">
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
                                        <h4 style="margin-bottom: 15px; color: var(--dark-blue); font-size: 1rem; font-weight: 700;">
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
                                                                style="padding: 6px 12px; font-size: 0.8rem; margin-right: 5px;"
                                                                onclick="editCommentInAdmin(<?= $c['id'] ?>, '<?= htmlspecialchars($c['content'], ENT_QUOTES); ?>')">
                                                            <i class="fas fa-edit"></i> Modifier
                                                        </button>
                                                        <a href="admin.php?key=admin123&delete_comment=<?= $c['id']; ?>" 
                                                           class="btn-action btn-delete" 
                                                           style="padding: 6px 12px; font-size: 0.8rem;"
                                                           onclick="return confirm('Supprimer ce commentaire ?')">
                                                            <i class="fas fa-times"></i> Supprimer
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p style="color: var(--muted); font-style: italic; text-align: center; padding: 20px; font-weight: 500;">
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
                <textarea id="editPostContent" name="content" rows="4" required placeholder="Modifiez le contenu de la publication..." class="form-control"></textarea>
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
                <textarea id="editCommentContent" name="content" rows="4" required placeholder="Modifiez le commentaire..." class="form-control"></textarea>
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
            padding: 18px 28px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            z-index: 9999;
            animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            ${type === 'success' ? 'background: linear-gradient(90deg, #27ae60, #219653);' : ''}
            ${type === 'error' ? 'background: linear-gradient(90deg, #e74c3c, #c0392b);' : ''}
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            backdrop-filter: blur(10px);
        ">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" style="font-size: 1.2rem;"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards';
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