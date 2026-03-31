<?php
// model/post.php - Avec JOINTURES SQL complètes
require_once 'db.php';

function getPosts($include_pending = false) {
    global $pdo;
    if (!$pdo) {
        return [
            ['id' => 1, 'content' => 'Bienvenue sur Campus Connect! 🎓', 'created_at' => date('Y-m-d H:i:s'), 'user_id' => '1'],
            ['id' => 2, 'content' => 'Première publication de test!', 'created_at' => date('Y-m-d H:i:s'), 'user_id' => '2']
        ];
    }
    
    try {
        $sql = "SELECT * FROM posts ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// NOUVELLE FONCTION : Posts avec TOUTES les jointures
function getPostsWithFullDetails() {
    global $pdo;
    
    if (!$pdo) {
        return getPosts();
    }
    
    try {
        $sql = "SELECT 
                    p.*,
                    u.username as author_name,
                    u.email as author_email,
                    u.avatar as author_avatar,
                    u.is_online as author_online,
                    COUNT(DISTINCT c.id) as comment_count,
                    COUNT(DISTINCT l.id) as like_count,
                    GROUP_CONCAT(DISTINCT CONCAT(cu.username, ':', cu.id) SEPARATOR '|') as commenters_data,
                    MAX(c.created_at) as last_comment_date
                FROM posts p
                LEFT JOIN users u ON u.id = CAST(p.user_id AS UNSIGNED)
                LEFT JOIN comments c ON p.id = c.post_id
                LEFT JOIN users cu ON cu.id = CAST(c.user_id AS UNSIGNED)
                LEFT JOIN likes l ON p.id = l.post_id
                GROUP BY p.id
                ORDER BY p.created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $posts = $stmt->fetchAll();
        
        foreach ($posts as &$post) {
            $post['comment_count'] = (int)($post['comment_count'] ?? 0);
            $post['like_count'] = (int)($post['like_count'] ?? 0);
        }
        
        return $posts;
    } catch (Exception $e) {
        error_log("getPostsWithFullDetails error: " . $e->getMessage());
        return getPosts();
    }
}

// FONCTION EXISTANTE AVEC JOINTURES (gardée pour compatibilité)
function getPostsWithStats() {
    return getPostsWithFullDetails();
}

function getComments($post_id) {
    global $pdo;
    if (!$pdo) {
        return [
            ['id' => 1, 'post_id' => 1, 'content' => 'Super plateforme!', 'created_at' => date('Y-m-d H:i:s'), 'user_id' => '2'],
            ['id' => 2, 'post_id' => 1, 'content' => 'Content de faire partie de cette communauté!', 'created_at' => date('Y-m-d H:i:s'), 'user_id' => '3']
        ];
    }
    
    try {
        $sql = "SELECT 
                    c.*,
                    u.username,
                    u.avatar,
                    u.is_online
                FROM comments c
                LEFT JOIN users u ON u.id = CAST(c.user_id AS UNSIGNED)
                WHERE c.post_id = ? 
                ORDER BY c.created_at ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$post_id]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

function addComment($post_id, $content, $user_id = 'anonymous') {
    global $pdo;
    if (!$pdo) return true;
    
    try {
        $sql = "INSERT INTO comments (post_id, content, user_id, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$post_id, $content, $user_id]);
    } catch (Exception $e) {
        return false;
    }
}

function addPost($content, $user_id = 'anonymous') {
    global $pdo;
    if (!$pdo) return true;
    
    try {
        $sql = "INSERT INTO posts (content, user_id, created_at) VALUES (?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$content, $user_id]);
    } catch (Exception $e) {
        return false;
    }
}

function countLikes($post_id) {
    global $pdo;
    if (!$pdo) return rand(0, 5);
    
    try {
        $sql = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$post_id]);
        $result = $stmt->fetch();
        return $result['like_count'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}

function deletePost($post_id, $user_id = null) {
    global $pdo;
    
    error_log("deletePost called: post_id=$post_id, user_id=" . ($user_id ?? 'null'));
    
    if (!$pdo) return false;
    
    try {
        $check_sql = "SELECT user_id FROM posts WHERE id = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$post_id]);
        $post = $check_stmt->fetch();
        
        if (!$post) return false;
        
        if ($user_id && $post['user_id'] != $user_id) return false;
        
        $pdo->prepare("DELETE FROM comments WHERE post_id = ?")->execute([$post_id]);
        $pdo->prepare("DELETE FROM likes WHERE post_id = ?")->execute([$post_id]);
        $result = $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$post_id]);
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Delete post error: " . $e->getMessage());
        return false;
    }
}

// NOUVELLE FONCTION : Modifier un commentaire
function updateComment($comment_id, $content, $user_id = null) {
    global $pdo;
    
    error_log("updateComment called: comment_id=$comment_id");
    
    if (!$pdo) return false;
    
    try {
        if ($user_id) {
            $check_sql = "SELECT user_id FROM comments WHERE id = ?";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([$comment_id]);
            $comment = $check_stmt->fetch();
            
            if (!$comment || $comment['user_id'] != $user_id) {
                return false;
            }
        }
        
        $sql = "UPDATE comments SET content = ?, created_at = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([trim($content), $comment_id]);
        
    } catch (Exception $e) {
        error_log("Update comment error: " . $e->getMessage());
        return false;
    }
}

function deleteComment($comment_id, $user_id = null) {
    global $pdo;
    
    error_log("deleteComment called: comment_id=$comment_id, user_id=" . ($user_id ?? 'null'));
    
    if (!$pdo) return false;
    
    try {
        if ($user_id) {
            $check_sql = "SELECT user_id FROM comments WHERE id = ?";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([$comment_id]);
            $comment = $check_stmt->fetch();
            
            if (!$comment || $comment['user_id'] != $user_id) {
                return false;
            }
        }
        
        $sql = "DELETE FROM comments WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$comment_id]);
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Delete comment error: " . $e->getMessage());
        return false;
    }
}

function updatePost($post_id, $content, $user_id = null) {
    global $pdo;
    
    error_log("=== UPDATE POST START ===");
    error_log("Post ID: $post_id");
    error_log("User ID: " . ($user_id ?? 'null'));
    
    if (!$pdo) return false;
    
    try {
        if ($user_id) {
            $check_sql = "SELECT id, user_id, content FROM posts WHERE id = ?";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([$post_id]);
            $post = $check_stmt->fetch();
            
            if (!$post || $post['user_id'] != $user_id) {
                return false;
            }
        }
        
        $sql = "UPDATE posts SET content = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$content, $post_id]);
        
        error_log("Update result: " . ($result ? 'SUCCESS' : 'FAILED'));
        return $result;
        
    } catch (Exception $e) {
        error_log("Update post error: " . $e->getMessage());
        return false;
    }
}

function getPostById($post_id) {
    global $pdo;
    if (!$pdo) {
        return ['id' => $post_id, 'content' => 'Sample post content', 'created_at' => date('Y-m-d H:i:s'), 'user_id' => '1'];
    }
    
    try {
        $sql = "SELECT * FROM posts WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$post_id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

function emergencyDeletePost($post_id) {
    global $pdo;
    
    error_log("EMERGENCY delete post: $post_id");
    
    if (!$pdo) return false;
    
    try {
        $pdo->prepare("DELETE FROM comments WHERE post_id = ?")->execute([$post_id]);
        $pdo->prepare("DELETE FROM likes WHERE post_id = ?")->execute([$post_id]);
        $result = $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$post_id]);
        
        return $result;
        
    } catch (Exception $e) {
        error_log("Emergency delete error: " . $e->getMessage());
        return false;
    }
}

// NOUVELLE FONCTION : Jointure complète pour admin
function getAllPostsWithAllDetails() {
    global $pdo;
    
    if (!$pdo) {
        return getPosts();
    }
    
    try {
        $sql = "SELECT 
                    p.*,
                    u.id as author_id,
                    u.username as author_name,
                    u.email as author_email,
                    COUNT(DISTINCT c.id) as comment_count,
                    COUNT(DISTINCT l.id) as like_count,
                    GROUP_CONCAT(DISTINCT CONCAT(cu.username, ' (ID:', cu.id, ')') SEPARATOR '; ') as commenters,
                    MAX(c.created_at) as last_comment_time
                FROM posts p
                LEFT JOIN users u ON u.id = CAST(p.user_id AS UNSIGNED)
                LEFT JOIN comments c ON p.id = c.post_id
                LEFT JOIN users cu ON cu.id = CAST(c.user_id AS UNSIGNED)
                LEFT JOIN likes l ON p.id = l.post_id
                GROUP BY p.id
                ORDER BY p.created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        $posts = $stmt->fetchAll();
        
        foreach ($posts as &$post) {
            $post['comment_count'] = (int)($post['comment_count'] ?? 0);
            $post['like_count'] = (int)($post['like_count'] ?? 0);
        }
        
        return $posts;
    } catch (Exception $e) {
        error_log("getAllPostsWithAllDetails error: " . $e->getMessage());
        return getPosts();
    }
}
?>