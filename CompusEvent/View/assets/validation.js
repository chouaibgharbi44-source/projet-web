/* Strict JS validation for non-HTML5 form (works with HTML4 pages)
    - Validates: title, description, date, location, capacity
   - Date is optional and not strictly enforced (confirm on unusual format)
   - Adds .is-invalid class to invalid fields and focuses the first
*/
function _markInvalid(field) {
    try { field.className = field.className + ' is-invalid'; } catch(e){}
}
function _clearInvalid(field) {
    if (!field) return;
    field.className = field.className.replace(/\bis-invalid\b/g, '').trim();
}

function validateForm(form) {
    // normalize
    var titre = (form.title && form.title.value) ? form.title.value.trim() : '';
    var description = (form.description && form.description.value) ? form.description.value.trim() : '';
    var dateAj = (form.date && form.date.value) ? form.date.value.trim() : '';
    var location = (form.location && form.location.value) ? form.location.value.trim() : '';
    var capacity = (form.capacity && form.capacity.value) ? form.capacity.value.trim() : '';

    // clear previous invalid states
    try { _clearInvalid(form.title); _clearInvalid(form.description); _clearInvalid(form.date); _clearInvalid(form.location); _clearInvalid(form.capacity); } catch(e){}

    var errors = [];

    // Rules
    // Title: 2-150 chars
    var titlePattern = /^[\p{L}\d\s\-_'.,:;()]{2,150}$/u;
    if (!titlePattern.test(titre)) {
        errors.push('Titre: 2 à 150 caractères.');
        if (form.title) _markInvalid(form.title);
    }

    // Description: 10-2000 characters (allow most chars)
    if (description.length < 10 || description.length > 4000) {
        errors.push('Description: entre 10 et 4000 caractères.');
        if (form.description) _markInvalid(form.description);
    }

    // Niveau: 1-50 chars, letters numbers and + - allowed
    // Location: 1-255
    if (location.length > 0 && location.length < 2) {
        errors.push('Lieu: si renseigné, minimum 2 caractères.');
        if (form.location) _markInvalid(form.location);
    }

    // Capacity: integer
    if (capacity.length > 0 && !/^[0-9]+$/.test(capacity)) {
        errors.push('Capacité: doit être un entier positif.');
        if (form.capacity) _markInvalid(form.capacity);
    }

    // Date: not strictly required, but if present check basic format
        // Accept human-entered date text in format YYYY-MM-DD HH:MM or YYYY-MM-DD
        var datePattern = /^\d{4}-\d{2}-\d{2}(?:[\sT]\d{2}:\d{2})?$/;
    if (dateAj.length > 0 && !datePattern.test(dateAj)) {
        // not an error, but ask user to confirm
            if (!confirm('Le format de la date semble inhabituel (attendu YYYY-MM-DD HH:MM). Continuer quand même?')) {
                if (form.date) { _markInvalid(form.date); form.date.focus(); }
            return false;
        }
    }

    if (errors.length > 0) {
        alert('Veuillez corriger les erreurs :\n\n' + errors.join('\n'));
        // focus first invalid
        try {
            if (form.querySelector('.is-invalid')) form.querySelector('.is-invalid').focus();
        } catch(e){}
        return false;
    }

    // passed
    return true;
}
