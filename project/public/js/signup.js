// public/js/signup.js

document.getElementById('userType').addEventListener('change', function() {
    const yearGroup = document.getElementById('yearGroup');
    if (this.value === 'student') {
        yearGroup.style.display = 'block';
    } else {
        yearGroup.style.display = 'none';
    }
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
    } else {
        field.type = 'password';
    }
}

// FIXED: Password strength checker
document.getElementById('password').addEventListener('input', function () {
    const password = this.value;
    const strengthElem = document.getElementById('password-strength');

    // Remove all strength classes
    strengthElem.classList.remove('weak', 'medium', 'strong');
    strengthElem.textContent = '';

    if (!password) return;

    let message = '';
    let level = 'weak';

    // Check password strength
    const hasLower = /[a-z]/.test(password);
    const hasUpper = /[A-Z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    
    if (password.length < 8) {
        message = 'Trop court (min 8 caractères)';
        level = 'weak';
    } else if (password.length >= 8 && password.length < 12) {
        if (hasLower && hasUpper && hasNumber) {
            message = 'Moyen';
            level = 'medium';
        } else {
            message = 'Faible (ajoutez majuscule, minuscule et chiffre)';
            level = 'weak';
        }
    } else if (password.length >= 12) {
        if (hasLower && hasUpper && hasNumber) {
            if (hasSpecial) {
                message = 'Très fort';
                level = 'strong';
            } else {
                message = 'Fort';
                level = 'strong';
            }
        } else if (hasLower && hasUpper) {
            message = 'Moyen (ajoutez un chiffre)';
            level = 'medium';
        } else {
            message = 'Faible';
            level = 'weak';
        }
    }

    strengthElem.textContent = `Force du mot de passe : ${message}`;
    strengthElem.classList.add(level);
});

// Toggle interest tag selection
document.querySelectorAll('.interest-tag').forEach(tag => {
    tag.addEventListener('click', function() {
        this.classList.toggle('selected');
    });
});

// Form validation
document.getElementById('signupForm').addEventListener('submit', function(e) {
    const firstName = document.getElementById('firstName').value.trim();
    const lastName = document.getElementById('lastName').value.trim();
    const email = document.getElementById('email').value.trim();
    const studentId = document.getElementById('studentId').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const userType = document.getElementById('userType').value;
    const errorMessage = document.getElementById('errorMessage');

    // Clear previous error
    errorMessage.style.display = "none";
    errorMessage.textContent = "";

    // Check required fields
    if (!firstName || !lastName || !email || !studentId || !password || !confirmPassword || !userType) {
        e.preventDefault();
        errorMessage.textContent = "Veuillez remplir tous les champs obligatoires.";
        errorMessage.style.display = "block";
        return false;
    }

    // Name validation (letters only, accents allowed)
    const nameRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ](?:[A-Za-zÀ-ÖØ-öø-ÿ\s'-]*[A-Za-zÀ-ÖØ-öø-ÿ])?$/;
    if (!nameRegex.test(firstName) || !nameRegex.test(lastName)) {
        e.preventDefault();
        errorMessage.textContent = "Les noms ne doivent contenir que des lettres (pas de chiffres).";
        errorMessage.style.display = "block";
        return false;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        errorMessage.textContent = "Adresse email invalide.";
        errorMessage.style.display = "block";
        return false;
    }

    // Password length
    if (password.length < 8) {
        e.preventDefault();
        errorMessage.textContent = "Le mot de passe doit contenir au moins 8 caractères.";
        errorMessage.style.display = "block";
        return false;
    }

    // Strong password check
    const strongPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!strongPass.test(password)) {
        e.preventDefault();
        errorMessage.textContent = "Le mot de passe doit contenir une majuscule, une minuscule et un chiffre.";
        errorMessage.style.display = "block";
        return false;
    }

    // Password match
    if (password !== confirmPassword) {
        e.preventDefault();
        errorMessage.textContent = "Les mots de passe ne correspondent pas.";
        errorMessage.style.display = "block";
        return false;
    }
});