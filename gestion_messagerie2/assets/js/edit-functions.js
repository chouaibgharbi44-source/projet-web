// assets/js/edit-functions.js
class EditManager {
    constructor() {
        this.setupEventListeners();
        this.setupValidation();
    }
    
    setupEventListeners() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.edit-comment-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.edit-comment-btn');
                const commentId = btn.dataset.commentId;
                this.openCommentEditModal(commentId);
            }
            
            if (e.target.closest('.edit-message-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.edit-message-btn');
                const messageId = btn.dataset.messageId;
                this.openMessageEditModal(messageId);
            }
            
            if (e.target.closest('.edit-group-message-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.edit-group-message-btn');
                const messageId = btn.dataset.messageId;
                this.openGroupMessageEditModal(messageId);
            }
        });
    }
    
    setupValidation() {
        const postForm = document.querySelector('form[action*="addpost"]');
        if (postForm) {
            const textarea = postForm.querySelector('textarea[name="content"]');
            if (textarea) {
                InputValidator.setupCharacterCounter(textarea, 5000);
                
                textarea.addEventListener('input', () => {
                    const validation = InputValidator.validatePost(textarea.value);
                    if (!validation.valid) {
                        InputValidator.showError(textarea, validation.message);
                    } else {
                        InputValidator.showSuccess(textarea);
                    }
                });
            }
        }
        
        const commentForms = document.querySelectorAll('form[action*="addcomment"]');
        commentForms.forEach(form => {
            const input = form.querySelector('input[name="content"]');
            if (input) {
                InputValidator.setupCharacterCounter(input, 1000);
                
                input.addEventListener('input', () => {
                    const validation = InputValidator.validateComment(input.value);
                    if (!validation.valid) {
                        InputValidator.showError(input, validation.message);
                    } else {
                        InputValidator.showSuccess(input);
                    }
                });
            }
        });
        
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            InputValidator.setupCharacterCounter(messageInput, 2000);
            
            messageInput.addEventListener('input', () => {
                const validation = InputValidator.validateMessage(messageInput.value);
                if (!validation.valid) {
                    InputValidator.showError(messageInput, validation.message);
                } else {
                    InputValidator.showSuccess(messageInput);
                }
            });
        }
    }
    
    openCommentEditModal(commentId) {
        const commentElement = document.querySelector(`.modern-comment[data-id="${commentId}"]`) || 
                              document.querySelector(`.comment-item[data-id="${commentId}"]`);
        
        if (!commentElement) {
            this.showNotification('Commentaire non trouvé', 'error');
            return;
        }
        
        const currentContent = commentElement.querySelector('.comment-content')?.textContent || 
                             commentElement.querySelector('.comment-text')?.textContent || 
                             '';
        
        const modal = this.createModal(
            'Modifier le commentaire',
            currentContent,
            (newContent) => this.updateComment(commentId, newContent, commentElement),
            'comment'
        );
        
        document.body.appendChild(modal);
    }
    
    openMessageEditModal(messageId) {
        const messageElement = document.querySelector(`.message[data-id="${messageId}"]`);
        
        if (!messageElement) {
            this.showNotification('Message non trouvé', 'error');
            return;
        }
        
        const currentContent = messageElement.querySelector('.message-content')?.textContent || '';
        
        const modal = this.createModal(
            'Modifier le message',
            currentContent,
            (newContent) => this.updateMessage(messageId, newContent, messageElement),
            'message'
        );
        
        document.body.appendChild(modal);
    }
    
    openGroupMessageEditModal(messageId) {
        const messageElement = document.querySelector(`.group-message[data-id="${messageId}"]`);
        
        if (!messageElement) {
            this.showNotification('Message non trouvé', 'error');
            return;
        }
        
        const currentContent = messageElement.querySelector('.message-text')?.textContent || '';
        
        const modal = this.createModal(
            'Modifier le message de groupe',
            currentContent,
            (newContent) => this.updateGroupMessage(messageId, newContent, messageElement),
            'group-message'
        );
        
        document.body.appendChild(modal);
    }
    
    createModal(title, currentContent, onSaveCallback, type = 'comment') {
        const modal = document.createElement('div');
        modal.className = 'edit-modal';
        modal.innerHTML = `
            <div class="modal-overlay"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h3>${title}</h3>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <textarea class="edit-textarea" rows="5" placeholder="Modifiez votre contenu ici...">${currentContent}</textarea>
                    <div class="char-counter">
                        <span class="current">${currentContent.length}</span> / 
                        <span class="max">${type === 'comment' ? '1000' : '2000'}</span> caractères
                    </div>
                    <div class="validation-message"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn-cancel">Annuler</button>
                    <button class="btn-save">Enregistrer</button>
                </div>
            </div>
        `;
        
        const textarea = modal.querySelector('.edit-textarea');
        const charCounter = modal.querySelector('.char-counter .current');
        const maxSpan = modal.querySelector('.char-counter .max');
        const validationMsg = modal.querySelector('.validation-message');
        const saveBtn = modal.querySelector('.btn-save');
        const cancelBtn = modal.querySelector('.btn-cancel');
        const closeBtn = modal.querySelector('.close-modal');
        const overlay = modal.querySelector('.modal-overlay');
        
        const maxLength = type === 'comment' ? 1000 : 2000;
        maxSpan.textContent = maxLength;
        
        textarea.addEventListener('input', () => {
            const length = textarea.value.length;
            charCounter.textContent = length;
            charCounter.className = length > maxLength ? 'current error' : 'current';
            
            let validation;
            if (type === 'comment') {
                validation = InputValidator.validateComment(textarea.value);
            } else if (type === 'message') {
                validation = InputValidator.validateMessage(textarea.value);
            } else {
                validation = InputValidator.validateGroupMessage(textarea.value);
            }
            
            if (!validation.valid) {
                validationMsg.textContent = validation.message;
                validationMsg.className = 'validation-message error';
                saveBtn.disabled = true;
            } else {
                validationMsg.textContent = '';
                validationMsg.className = 'validation-message';
                saveBtn.disabled = false;
            }
        });
        
        saveBtn.addEventListener('click', async () => {
            const newContent = textarea.value.trim();
            
            let validation;
            if (type === 'comment') {
                validation = InputValidator.validateComment(newContent);
            } else if (type === 'message') {
                validation = InputValidator.validateMessage(newContent);
            } else {
                validation = InputValidator.validateGroupMessage(newContent);
            }
            
            if (!validation.valid) {
                validationMsg.textContent = validation.message;
                validationMsg.className = 'validation-message error';
                return;
            }
            
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
            saveBtn.disabled = true;
            
            try {
                await onSaveCallback(newContent);
                modal.remove();
            } catch (error) {
                saveBtn.innerHTML = 'Enregistrer';
                saveBtn.disabled = false;
                validationMsg.textContent = 'Erreur: ' + error.message;
                validationMsg.className = 'validation-message error';
            }
        });
        
        const closeModal = () => modal.remove();
        cancelBtn.addEventListener('click', closeModal);
        closeBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);
        
        setTimeout(() => {
            textarea.focus();
            textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        }, 100);
        
        return modal;
    }
    
    async updateComment(commentId, newContent, commentElement) {
        try {
            const formData = new FormData();
            formData.append('comment_id', commentId);
            formData.append('content', newContent);
            
            const response = await fetch('views/updatecomment.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                const contentElement = commentElement.querySelector('.comment-content') || 
                                     commentElement.querySelector('.comment-text');
                if (contentElement) {
                    contentElement.textContent = newContent;
                }
                
                const timeElement = commentElement.querySelector('.comment-time');
                if (timeElement) {
                    const now = new Date();
                    timeElement.innerHTML = `${now.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})} <span class="edited">(modifié)</span>`;
                }
                
                this.showNotification('Commentaire modifié avec succès', 'success');
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            this.showNotification(error.message || 'Erreur lors de la modification', 'error');
            throw error;
        }
    }
    
    async updateMessage(messageId, newContent, messageElement) {
        try {
            const formData = new FormData();
            formData.append('message_id', messageId);
            formData.append('content', newContent);
            
            const response = await fetch('views/updatemessage.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                const contentElement = messageElement.querySelector('.message-content');
                if (contentElement) {
                    contentElement.textContent = newContent;
                }
                
                const timeElement = messageElement.querySelector('.message-time');
                if (timeElement) {
                    const now = new Date();
                    timeElement.innerHTML = `${now.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})} <span class="edited">(modifié)</span>`;
                }
                
                this.showNotification('Message modifié avec succès', 'success');
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            this.showNotification(error.message || 'Erreur lors de la modification', 'error');
            throw error;
        }
    }
    
    async updateGroupMessage(messageId, newContent, messageElement) {
        try {
            const formData = new FormData();
            formData.append('message_id', messageId);
            formData.append('content', newContent);
            
            const response = await fetch('views/updategroupmessage.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                const contentElement = messageElement.querySelector('.message-text');
                if (contentElement) {
                    contentElement.textContent = newContent;
                }
                
                const timeElement = messageElement.querySelector('.message-time');
                if (timeElement) {
                    const now = new Date();
                    timeElement.innerHTML = `${now.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})} <span class="edited">(modifié)</span>`;
                }
                
                this.showNotification('Message de groupe modifié avec succès', 'success');
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            this.showNotification(error.message || 'Erreur lors de la modification', 'error');
            throw error;
        }
    }
    
    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 3000);
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    window.editManager = new EditManager();
});