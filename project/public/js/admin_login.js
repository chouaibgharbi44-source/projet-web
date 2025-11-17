document.getElementById('admin-login-form').addEventListener('submit', function(event) {
    // 1. Get references to elements
    const errorDisplay = document.getElementById('js-error-message');
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    
    // Clear previous error message and hide it
    errorDisplay.textContent = '';
    errorDisplay.style.display = 'none';

    // Basic regex for email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // 2. Validate Email Field
    if (!email) {
        event.preventDefault(); // Stop form submission
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

    // 3. Validate Password Field (minimum 8 characters)
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

    // If validation passes, the form is submitted to the PHP script
});