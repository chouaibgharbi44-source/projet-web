<?php
// model/post.php - With improved delete functionality
require_once 'db.php';

function getPosts($include_pending = false) {
    global $pdo;
    if (!$pdo) {
        // Fallback to temporary data
        return [
            ['id' => 1, 'content' => 'Bienvenue sur Campus Connect! 🎓', 'created_at' => date('Y-m-d H:i:s'), 'user_id' => 'user_1'],
            ['id' => 2, 'content' => 'Première publication de test!', 'created_at' => date('Y-m-d H:i:s'), 'user_id' => 'user_2']
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

function getComments($post_id) {
    global $pdo;
    if (!$pdo) {
        return [
            ['id' => 1, 'post_id' => 1, 'content' => 'Super plateforme!', 'created_at' => date('Y-m-d H:i:s'), 'user_id' => 'user_2'],
            ['id' => 2, 'post_id' => 1, 'content' => 'Content de faire partie de cette communauté!', 'created_at' => date('Y-m-d H:i:s'), 'user_id' => 'user_3']
        ];
    }
    
    try {
        $sql = "SELECT * FROM comments WHERE post_id = ? ORDER BY created_at ASC";
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
    
    if (!$pdo) {
        error_log("No database connection - delete failed");
        return false;
    }
    
    try {
        // First, check if post exists
        $check_sql = "SELECT user_id FROM posts WHERE id = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$post_id]);
        $post = $check_stmt->fetch();
        
        error_log("Post check result: " . print_r($post, true));
        
        if (!$post) {
            error_log("Post $post_id not found");
            return false;
        }
        
        // Check ownership if user_id provided
        if ($user_id && $post['user_id'] != $user_id) {
            error_log("User $user_id is not owner of post $post_id (owner is: " . $post['user_id'] . ")");
            return false;
        }
        
        // Delete comments
        $comments_result = $pdo->prepare("DELETE FROM comments WHERE post_id = ?")->execute([$post_id]);
        error_log("Deleted comments for post $post_id - result: " . ($comments_result ? 'success' : 'failed'));
        
        // Delete likes
        $likes_result = $pdo->prepare("DELETE FROM likes WHERE post_id = ?")->execute([$post_id]);
        error_log("Deleted likes for post $post_id - result: " . ($likes_result ? 'success' : 'failed'));
        
        // Delete post
        $post_result = $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$post_id]);
        error_log("Deleted post $post_id - result: " . ($post_result ? 'success' : 'failed'));
        
        return $post_result;
        
    } catch (Exception $e) {
        error_log("Delete post error: " . $e->getMessage());
        return false;
    }
}

function deleteComment($comment_id, $user_id = null) {
    global $pdo;
    
    error_log("deleteComment called: comment_id=$comment_id, user_id=" . ($user_id ?? 'null'));
    
    if (!$pdo) {
        return false;
    }
    
    try {
        // Check if comment exists
        $check_sql = "SELECT user_id FROM comments WHERE id = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$comment_id]);
        $comment = $check_stmt->fetch();
        
        error_log("Comment check result: " . print_r($comment, true));
        
        if (!$comment) {
            error_log("Comment $comment_id not found");
            return false;
        }
        
        // Check ownership if user_id provided
        if ($user_id && $comment['user_id'] != $user_id) {
            error_log("User $user_id is not owner of comment $comment_id (owner is: " . $comment['user_id'] . ")");
            return false;
        }
        
        $sql = "DELETE FROM comments WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$comment_id]);
        
        error_log("Deleted comment $comment_id - result: " . ($result ? 'success' : 'failed'));
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
    error_log("Content: " . substr($content, 0, 50) . "...");
    
    if (!$pdo) {
        error_log("No database connection");
        error_log("=== UPDATE POST END (NO DB) ===");
        return false;
    }
    
    try {
        // Always check ownership when user_id is provided
        if ($user_id) {
            $check_sql = "SELECT id, user_id, content FROM posts WHERE id = ?";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([$post_id]);
            $post = $check_stmt->fetch();
            
            error_log("Database check result: " . print_r($post, true));
            
            if (!$post) {
                error_log("Post not found in database");
                error_log("=== UPDATE POST END (NOT FOUND) ===");
                return false;
            }
            
            error_log("Post owner: " . $post['user_id']);
            error_log("Current user: $user_id");
            error_log("Ownership match: " . ($post['user_id'] == $user_id ? 'YES' : 'NO'));
            
            if ($post['user_id'] != $user_id) {
                error_log("User is not the owner of this post");
                error_log("=== UPDATE POST END (NOT OWNER) ===");
                return false;
            }
        } else {
            error_log("No user_id provided - skipping ownership check");
        }
        
        // Perform the update
        $sql = "UPDATE posts SET content = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$content, $post_id]);
        
        error_log("Update executed - Rows affected: " . $stmt->rowCount());
        error_log("Update result: " . ($result ? 'SUCCESS' : 'FAILED'));
        
        // Verify the update
        if ($result) {
            $verify_sql = "SELECT content FROM posts WHERE id = ?";
            $verify_stmt = $pdo->prepare($verify_sql);
            $verify_stmt->execute([$post_id]);
            $updated_post = $verify_stmt->fetch();
            error_log("Verified content: " . ($updated_post['content'] ?? 'NOT FOUND'));
        }
        
        error_log("=== UPDATE POST END ===");
        return $result;
        
    } catch (Exception $e) {
        error_log("Update post error: " . $e->getMessage());
        error_log("=== UPDATE POST END (ERROR) ===");
        return false;
    }
}

function getPostById($post_id) {
    global $pdo;
    if (!$pdo) {
        return ['id' => $post_id, 'content' => 'Sample post content', 'created_at' => date('Y-m-d H:i:s'), 'user_id' => 'user_1'];
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

// Emergency delete function - no ownership check
function emergencyDeletePost($post_id) {
    global $pdo;
    
    error_log("EMERGENCY delete post: $post_id");
    
    if (!$pdo) return false;
    
    try {
        // Delete everything without checking ownership
        $pdo->prepare("DELETE FROM comments WHERE post_id = ?")->execute([$post_id]);
        $pdo->prepare("DELETE FROM likes WHERE post_id = ?")->execute([$post_id]);
        $result = $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$post_id]);
        
        error_log("Emergency delete result: " . ($result ? 'success' : 'failed'));
        return $result;
        
    } catch (Exception $e) {
        error_log("Emergency delete error: " . $e->getMessage());
        return false;
    }
}
?>