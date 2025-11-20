document.getElementById('admin-login-form').addEventListener('submit', function(event) {
    
    const errorDisplay = document.getElementById('js-error-message');
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    
    
    errorDisplay.textContent = '';
    errorDisplay.style.display = 'none';

    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    
    if (!email) {
        event.preventDefault(); 
        errorDisplay.textContent = 'L\'adresse email est requise.';
        errorDisplay.style.display = 'block';
        document.getElementById('email').focus();
        return;
    }

    if (!emailRegex.test(email)) {
        event.preventDefault();
        errorDisplay.textContent = 'Veuillez entrer une adresse email valide.';
        errorDisplay.style.display = 'block';
        document.getElementById('email').focus();
        return;
    }

    
    if (!password) {
        event.preventDefault();
        errorDisplay.textContent = 'Le mot de passe est requis.';
        errorDisplay.style.display = 'block';
        document.getElementById('password').focus();
        return;
    }

    if (password.length < 8) {
        event.preventDefault();
        errorDisplay.textContent = 'Le mot de passe doit contenir au moins 8 caractères.';
        errorDisplay.style.display = 'block';
        document.getElementById('password').focus();
        return;
    }

    
});