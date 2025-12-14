/**
 * Système de validation de formulaire avec affichage des erreurs sous les champs
 * Remplace la validation HTML5
 */

// Fonction pour créer ou récupérer l'élément d'erreur
function getErrorElement(field) {
    let errorId = field.id + '_error';
    let errorElement = document.getElementById(errorId);

    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.id = errorId;
        errorElement.className = 'error-message';
        errorElement.style.color = 'red';
        errorElement.style.fontSize = '0.9em';
        errorElement.style.marginTop = '5px';
        errorElement.style.marginBottom = '10px';

        // Insérer après le champ ou son parent label
        let parent = field.parentElement;
        if (parent.tagName === 'LABEL') {
            parent.parentElement.insertBefore(errorElement, parent.nextSibling);
        } else {
            field.parentElement.insertBefore(errorElement, field.nextSibling);
        }
    }

    return errorElement;
}

// Fonction pour afficher une erreur sous un champ
function showError(field, message) {
    if (!field) return;

    // Ajouter une classe d'erreur au champ
    field.classList.add('is-invalid');
    field.style.borderColor = 'red';

    // Afficher le message d'erreur
    let errorElement = getErrorElement(field);
    errorElement.textContent = message;
    errorElement.style.display = 'block';
}

// Fonction pour effacer l'erreur d'un champ
function clearError(field) {
    if (!field) return;

    // Retirer la classe d'erreur
    field.classList.remove('is-invalid');
    field.style.borderColor = '';

    // Cacher le message d'erreur
    let errorId = field.id + '_error';
    let errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.style.display = 'none';
    }
}

// Fonction pour effacer toutes les erreurs d'un formulaire
function clearAllErrors(form) {
    let fields = form.querySelectorAll('input, textarea, select');
    fields.forEach(function (field) {
        clearError(field);
    });
}

// Validation du formulaire Événement
function validateEventForm(form) {
    clearAllErrors(form);

    let isValid = true;

    // Récupération des champs
    let titleField = form.querySelector('[name="title"]');
    let descriptionField = form.querySelector('[name="description"]');
    let dateField = form.querySelector('[name="date"]');
    let locationField = form.querySelector('[name="location"]');
    let capacityField = form.querySelector('[name="capacity"]');

    // Validation du titre
    if (titleField) {
        let titre = titleField.value.trim();
        let titlePattern = /^[\p{L}\d\s\-_'.,:;()]{2,150}$/u;

        if (titre.length === 0) {
            showError(titleField, 'Le titre est obligatoire.');
            isValid = false;
        } else if (!titlePattern.test(titre)) {
            showError(titleField, 'Le titre doit contenir entre 2 et 150 caractères.');
            isValid = false;
        }
    }

    // Validation de la description
    if (descriptionField) {
        let description = descriptionField.value.trim();

        if (description.length === 0) {
            showError(descriptionField, 'La description est obligatoire.');
            isValid = false;
        } else if (description.length < 10) {
            showError(descriptionField, 'La description doit contenir au moins 10 caractères.');
            isValid = false;
        } else if (description.length > 4000) {
            showError(descriptionField, 'La description ne peut pas dépasser 4000 caractères.');
            isValid = false;
        }
    }

    // Validation de la date
    if (dateField) {
        let dateValue = dateField.value.trim();
        let datePattern = /^\d{4}-\d{2}-\d{2}(?:[\sT]\d{2}:\d{2})?$/;

        if (dateValue.length === 0) {
            showError(dateField, 'La date est obligatoire.');
            isValid = false;
        } else if (!datePattern.test(dateValue)) {
            showError(dateField, 'Format attendu: YYYY-MM-DD HH:MM (ex: 2025-12-15 14:00)');
            isValid = false;
        }
    }

    // Validation du lieu
    if (locationField) {
        let location = locationField.value.trim();

        if (location.length === 0) {
            showError(locationField, 'Le lieu est obligatoire.');
            isValid = false;
        } else if (location.length < 2) {
            showError(locationField, 'Le lieu doit contenir au moins 2 caractères.');
            isValid = false;
        } else if (location.length > 255) {
            showError(locationField, 'Le lieu ne peut pas dépasser 255 caractères.');
            isValid = false;
        }
    }

    // Validation de la capacité (optionnelle mais doit être un nombre si renseignée)
    if (capacityField) {
        let capacity = capacityField.value.trim();

        if (capacity.length > 0 && !/^[0-9]+$/.test(capacity)) {
            showError(capacityField, 'La capacité doit être un nombre entier positif.');
            isValid = false;
        } else if (capacity.length > 0 && parseInt(capacity) <= 0) {
            showError(capacityField, 'La capacité doit être supérieure à 0.');
            isValid = false;
        }
    }

    // Focus sur le premier champ invalide
    if (!isValid) {
        let firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.focus();
        }
    }

    return isValid;
}

// Validation du formulaire Réservation
function validateReservationForm(form) {
    clearAllErrors(form);

    let isValid = true;

    // Récupération des champs
    let eventField = form.querySelector('[name="event_id"]');
    let nameField = form.querySelector('[name="name"]');
    let emailField = form.querySelector('[name="email"]');
    let seatsField = form.querySelector('[name="seats"]');

    // Validation de l'événement
    if (eventField) {
        let eventId = eventField.value.trim();

        if (!eventId || eventId === '') {
            showError(eventField, 'Vous devez sélectionner un événement.');
            isValid = false;
        }
    }

    // Validation du nom
    if (nameField) {
        let name = nameField.value.trim();

        if (name.length === 0) {
            showError(nameField, 'Le nom est obligatoire.');
            isValid = false;
        } else if (name.length < 2) {
            showError(nameField, 'Le nom doit contenir au moins 2 caractères.');
            isValid = false;
        } else if (name.length > 255) {
            showError(nameField, 'Le nom ne peut pas dépasser 255 caractères.');
            isValid = false;
        }
    }

    // Validation de l'email (optionnel mais doit être valide si renseigné)
    if (emailField) {
        let email = emailField.value.trim();
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email.length > 0 && !emailPattern.test(email)) {
            showError(emailField, 'L\'adresse email n\'est pas valide.');
            isValid = false;
        }
    }

    // Validation du nombre de places
    if (seatsField) {
        let seats = seatsField.value.trim();

        if (seats.length === 0) {
            showError(seatsField, 'Le nombre de places est obligatoire.');
            isValid = false;
        } else if (!/^[0-9]+$/.test(seats)) {
            showError(seatsField, 'Le nombre de places doit être un nombre entier.');
            isValid = false;
        } else if (parseInt(seats) <= 0) {
            showError(seatsField, 'Le nombre de places doit être au moins 1.');
            isValid = false;
        }
    }

    // Focus sur le premier champ invalide
    if (!isValid) {
        let firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.focus();
        }
    }

    return isValid;
}

// Fonction principale de validation (détecte automatiquement le type de formulaire)
function validateForm(form) {
    // Détecter le type de formulaire
    if (form.querySelector('[name="name"]') && form.querySelector('[name="seats"]') && !form.querySelector('[name="title"]')) {
        // Formulaire de réservation
        return validateReservationForm(form);
    } else if (form.querySelector('[name="title"]') && form.querySelector('[name="description"]')) {
        // Formulaire d'événement
        return validateEventForm(form);
    }

    // Par défaut, laisser passer
    return true;
}

// Ajouter des écouteurs d'événements pour effacer les erreurs lors de la saisie
document.addEventListener('DOMContentLoaded', function () {
    let forms = document.querySelectorAll('form');

    forms.forEach(function (form) {
        let fields = form.querySelectorAll('input, textarea, select');

        fields.forEach(function (field) {
            // Générer un ID unique si le champ n'en a pas
            if (!field.id) {
                field.id = 'field_' + Math.random().toString(36).substr(2, 9);
            }

            // Effacer l'erreur lors de la saisie
            field.addEventListener('input', function () {
                clearError(field);
            });

            // Effacer l'erreur lors du changement (pour les select)
            field.addEventListener('change', function () {
                clearError(field);
            });
        });
    });
});
