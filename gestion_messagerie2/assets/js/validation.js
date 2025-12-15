// assets/js/validation.js
class InputValidator {
    static validatePost(content, min = 10, max = 5000) {
        content = content.trim();
        
        if (content.length < min) {
            return {
                valid: false,
                message: `Le contenu doit contenir au moins ${min} caractères`
            };
        }
        
        if (content.length > max) {
            return {
                valid: false,
                message: `Le contenu ne doit pas dépasser ${max} caractères`
            };
        }
        
        const maliciousPatterns = [
            /javascript:/i,
            /data:/i,
            /vbscript:/i,
            /onload=/i,
            /onerror=/i,
            /onclick=/i,
            /<script>/i,
            /<\/script>/i
        ];
        
        for (let pattern of maliciousPatterns) {
            if (pattern.test(content)) {
                return {
                    valid: false,
                    message: 'Le contenu contient du code potentiellement dangereux'
                };
            }
        }
        
        return { valid: true, message: '' };
    }
    
    static validateComment(content, min = 2, max = 1000) {
        content = content.trim();
        
        if (content.length < min) {
            return {
                valid: false,
                message: `Le commentaire doit contenir au moins ${min} caractères`
            };
        }
        
        if (content.length > max) {
            return {
                valid: false,
                message: `Le commentaire ne doit pas dépasser ${max} caractères`
            };
        }
        
        return { valid: true, message: '' };
    }
    
    static validateMessage(content, min = 1, max = 2000) {
        content = content.trim();
        
        if (content.length < min) {
            return {
                valid: false,
                message: 'Le message ne peut pas être vide'
            };
        }
        
        if (content.length > max) {
            return {
                valid: false,
                message: `Le message ne doit pas dépasser ${max} caractères`
            };
        }
        
        return { valid: true, message: '' };
    }
    
    static validateGroupMessage(content, min = 1, max = 2000) {
        return this.validateMessage(content, min, max);
    }
    
    static sanitizeHTML(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    static showError(element, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'validation-error';
        errorDiv.style.cssText = `
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            padding: 5px;
            background: #ffeaea;
            border-radius: 4px;
            border-left: 3px solid #e74c3c;
        `;
        errorDiv.textContent = message;
        
        const existingError = element.parentNode.querySelector('.validation-error');
        if (existingError) {
            existingError.remove();
        }
        
        element.parentNode.appendChild(errorDiv);
        
        element.style.borderColor = '#e74c3c';
        
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
                element.style.borderColor = '';
            }
        }, 5000);
    }
    
    static showSuccess(element, message = '') {
        element.style.borderColor = '#2ecc71';
        
        if (message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'validation-success';
            successDiv.style.cssText = `
                color: #2ecc71;
                font-size: 12px;
                margin-top: 5px;
                padding: 5px;
                background: #eaffea;
                border-radius: 4px;
                border-left: 3px solid #2ecc71;
            `;
            successDiv.textContent = message;
            
            const existingSuccess = element.parentNode.querySelector('.validation-success');
            if (existingSuccess) {
                existingSuccess.remove();
            }
            
            element.parentNode.appendChild(successDiv);
            
            setTimeout(() => {
                if (successDiv.parentNode) {
                    successDiv.remove();
                }
            }, 3000);
        }
    }
    
    static setupCharacterCounter(textarea, max) {
        const counter = document.createElement('div');
        counter.className = 'char-counter';
        counter.style.cssText = `
            font-size: 12px;
            color: #666;
            text-align: right;
            margin-top: 5px;
        `;
        
        textarea.parentNode.appendChild(counter);
        
        const updateCounter = () => {
            const length = textarea.value.length;
            counter.textContent = `${length}/${max}`;
            
            if (length > max * 0.9) {
                counter.style.color = '#f39c12';
            } else if (length > max) {
                counter.style.color = '#e74c3c';
                counter.style.fontWeight = 'bold';
            } else {
                counter.style.color = '#666';
                counter.style.fontWeight = 'normal';
            }
        };
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
        
        return counter;
    }
}

// Export pour utilisation globale
window.InputValidator = InputValidator;