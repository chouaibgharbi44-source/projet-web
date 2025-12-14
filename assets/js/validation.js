document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const errors = {};

            // Valider le titre
            const title = form.querySelector('#title');
            if (!title.value.trim()) {
                errors.title = "Le titre est requis.";
                isValid = false;
            } else if (title.value.trim().length < 10) {
                errors.title = "Le titre doit faire au moins 10 caractères.";
                isValid = false;
            }

            // Valider la bonne réponse
            const correctAnswer = form.querySelector('#correct_answer');
            if (correctAnswer) {
                const val = correctAnswer.value.trim();
                if (!val){
                    errors.correct_answer = "La bonne réponse est requise.";
                    isValid = false;
                }else if (val.length < 4) {
                    errors.correct_answer = "La bonne réponse doit contenir au moins 4 caractères.";
                    isValid = false;
                } else {
                    const forbiddenChars = ['"', "'", '@', '^', '\\', '#', '~', '&'];
                    for (let char of forbiddenChars) {
                        if (val.includes(char)) {
                            errors.correct_answer = "La bonne réponse ne doit pas contenir les caractères suivants : \" ' @ ^ \\ # ~ &";
                            isValid = false;
                            break;
                        }
                    }
                }        
                
            }

            // Valider option A
            const optionA = form.querySelector('#option_a');
            if (optionA) {
                const val = optionA.value.trim();
                if (!val) {
                    errors.option_a = "L'option A est requise.";
                    isValid = false;
                } else if (val.length < 6) {
                    errors.option_a = "L'option A doit contenir au moins 6 caractères.";
                    isValid = false;
                }    
            }

            // Valider option B
            const optionB = form.querySelector('#option_b');
            if (optionB) {
                const val = optionB.value.trim();
                if (!val) {
                    errors.option_b = "L'option B est requise.";
                    isValid = false;
                } else if (val.length < 6) {
                    errors.option_b = "L'option B doit contenir au moins 6 caractères.";
                    isValid = false;
                }

            }

            // Valider la catégorie
            const category = form.querySelector('#category');
            if (!category.value) {
                errors.category = "Veuillez sélectionner une catégorie.";
                isValid = false;
            }

            // Afficher les erreurs
            if (!isValid) {
                e.preventDefault(); // Empêche l'envoi du formulaire

                // Effacer les anciennes erreurs
                const errorElements = form.querySelectorAll('.error-message');
                errorElements.forEach(el => el.remove());

                // Ajouter les nouvelles erreurs
                for (const [field, message] of Object.entries(errors)) {
                    const input = form.querySelector(`#${field}`);
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = 'red';
                    errorDiv.style.fontSize = '0.9rem';
                    errorDiv.textContent = message;
                    input.parentNode.appendChild(errorDiv);
                }
            }
        });
    });
});