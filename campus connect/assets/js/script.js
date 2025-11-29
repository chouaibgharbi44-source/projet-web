// Delete post function
function deletePost(postId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette publication?')) {
        return;
    }
    
    window.location.href = `views/deletepost.php?post_id=${postId}`;
}

// Delete comment function
function deleteComment(commentId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire?')) {
        return;
    }
    
    window.location.href = `views/deletecomment.php?comment_id=${commentId}`;
}

// Edit post functions
function openEditModal(postId) {
    // Get post content from the page
    const postContent = document.getElementById(`post-content-${postId}`).textContent;
    document.getElementById('editPostId').value = postId;
    document.getElementById('editPostContent').value = postContent;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Handle edit form submission
document.getElementById('editPostForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const postId = document.getElementById('editPostId').value;
    const content = document.getElementById('editPostContent').value;
    
    if (!content.trim()) {
        alert('Le contenu ne peut pas être vide');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Mise à jour...';
    submitBtn.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('post_id', postId);
    formData.append('content', content);
    
    // Send update request
    fetch('views/updatepost.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update post content in DOM immediately
            document.getElementById(`post-content-${postId}`).textContent = content;
            closeEditModal();
            alert('Publication modifiée avec succès!');
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la modification');
    })
    .finally(() => {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeEditModal();
    }
}

// Allow Ctrl+Enter to submit
document.getElementById('editPostContent').addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'Enter') {
        document.getElementById('editPostForm').dispatchEvent(new Event('submit'));
    }
});