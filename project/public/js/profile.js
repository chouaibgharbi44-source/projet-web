// js/profile.js - Image preview for profile pic & banner
document.querySelectorAll('.file-input').forEach(input => {
    input.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => {
            const targetId = this.name === 'profile_pic' ? 'profilePic' : 'bannerImg';
            document.getElementById(targetId).src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
});

// Validation téléphone en temps réel + au submit
document.addEventListener('DOMContentLoaded', () => {
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');
    const form = document.querySelector('.info-form');

    if (!phoneInput) return;

    const phoneRegex = /^(\+33|0)[1-9](\s?\d{2}){4}$/;

    function validatePhone() {
        const value = phoneInput.value.trim();
        if (value === '') {
            phoneError.style.display = 'none';
            phoneInput.style.borderColor = '';
            return true;
        }
        if (phoneRegex.test(value)) {
            phoneError.style.display = 'none';
            phoneInput.style.borderColor = '#4ade80';
            return true;
        } else {
            phoneError.style.display = 'block';
            phoneInput.style.borderColor = '#ff6b6b';
            return false;
        }
    }

    phoneInput.addEventListener('input', validatePhone);
    phoneInput.addEventListener('blur', validatePhone);

    // Bloque l'envoi du formulaire si téléphone invalide
    form.addEventListener('submit', (e) => {
        if (!validatePhone()) {
            e.preventDefault();
            phoneInput.focus();
        }
    });
});