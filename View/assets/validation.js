/* Strict JS validation for non-HTML5 form (works with HTML4 pages)
   - Validates: nom_matiere, titre, description, niveau_difficulte
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

// Validator specific for matiere forms
function validateMatiereForm(form) {
    // keep original matiere validation rules
    var nom = (form.nom_matiere && form.nom_matiere.value) ? form.nom_matiere.value.trim() : '';
    var titre = (form.titre && form.titre.value) ? form.titre.value.trim() : '';
    var description = (form.description && form.description.value) ? form.description.value.trim() : '';
    var dateAj = (form.date_ajout && form.date_ajout.value) ? form.date_ajout.value.trim() : '';
    var niveau = (form.niveau_difficulte && form.niveau_difficulte.value) ? form.niveau_difficulte.value.trim() : '';

    try { _clearInvalid(form.nom_matiere); _clearInvalid(form.titre); _clearInvalid(form.description); _clearInvalid(form.niveau_difficulte); } catch(e){}

    var errors = [];

    var namePattern = /^[\p{L}\d\s\-_'.,()]{2,100}$/u;
    if (!namePattern.test(nom)) {
        errors.push('Nom matière: 2 à 100 caractères, lettres, chiffres et - _ \' . , ( ) autorisés.');
        if (form.nom_matiere) _markInvalid(form.nom_matiere);
    }

    var titlePattern = /^[\p{L}\d\s\-_'.,:;()]{2,150}$/u;
    if (!titlePattern.test(titre)) {
        errors.push('Titre: 2 à 150 caractères, caractères alphanumériques et ponctuation simple autorisés.');
        if (form.titre) _markInvalid(form.titre);
    }

    if (description.length < 10 || description.length > 2000) {
        errors.push('Description: entre 10 et 2000 caractères.');
        if (form.description) _markInvalid(form.description);
    }

    var niveauPattern = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s\-+]{1,50}$/;
    if (!niveauPattern.test(niveau)) {
        errors.push('Niveau difficulté: 1 à 50 caractères, lettres, chiffres, espaces, + et - autorisés.');
        if (form.niveau_difficulte) _markInvalid(form.niveau_difficulte);
    }

    var datePattern = /^\d{4}-\d{2}-\d{2}(?:\s+\d{2}:\d{2}:\d{2})?$/;
    if (dateAj.length > 0 && !datePattern.test(dateAj)) {
        if (!confirm('Le format de la date semble inhabituel (attendu YYYY-MM-DD ou YYYY-MM-DD HH:MM:SS). Continuer quand même?')) {
            if (form.date_ajout) { _markInvalid(form.date_ajout); form.date_ajout.focus(); }
            return false;
        }
    }

    if (errors.length > 0) {
        alert('Veuillez corriger les erreurs :\n\n' + errors.join('\n'));
        try { if (form.querySelector('.is-invalid')) form.querySelector('.is-invalid').focus(); } catch(e){}
        return false;
    }
    return true;
}

// Validator specific for ressource forms
function validateRessourceForm(form) {
    var titre = (form.titre && form.titre.value) ? form.titre.value.trim() : '';
    var description = (form.description && form.description.value) ? form.description.value.trim() : '';
    var typeR = (form.type_ressource && form.type_ressource.value) ? form.type_ressource.value.trim() : '';
    var url = (form.url && form.url.value) ? form.url.value.trim() : '';
    var auteur = (form.auteur && form.auteur.value) ? form.auteur.value.trim() : '';
    var dateAj = (form.date_ajout && form.date_ajout.value) ? form.date_ajout.value.trim() : '';

    // clear previous invalid states
    try { _clearInvalid(form.titre); _clearInvalid(form.description); _clearInvalid(form.type_ressource); _clearInvalid(form.url); _clearInvalid(form.auteur); } catch(e){}

    var errors = [];

    var textPattern = /^[\p{L}\d\s\-_'.,:;()]{2,150}$/u;
    if (!textPattern.test(titre)) {
        errors.push('Titre: 2 à 150 caractères.');
        if (form.titre) _markInvalid(form.titre);
    }

    if (description.length < 10 || description.length > 2000) {
        errors.push('Description: entre 10 et 2000 caractères.');
        if (form.description) _markInvalid(form.description);
    }

    var typePattern = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s\-]{2,50}$/;
    if (!typePattern.test(typeR)) {
        errors.push('Type de ressource: 2 à 50 caractères (PDF, Document, Tutoriel, ...).');
        if (form.type_ressource) _markInvalid(form.type_ressource);
    }

    // URL basic check
    var urlPattern = /^(https?:\/\/)?([\w\-]+\.)+[\w\-]+(\/[^\s]*)?$/i;
    if (!urlPattern.test(url)) {
        errors.push('URL: format invalide (commencez par http:// ou https:// recommandé).');
        if (form.url) _markInvalid(form.url);
    }

    var authorPattern = /^[\p{L}\d\s\-_'.,]{2,100}$/u;
    if (!authorPattern.test(auteur)) {
        errors.push('Auteur: 2 à 100 caractères.');
        if (form.auteur) _markInvalid(form.auteur);
    }

    var datePattern = /^\d{4}-\d{2}-\d{2}(?:\s+\d{2}:\d{2}:\d{2})?$/;
    if (dateAj.length > 0 && !datePattern.test(dateAj)) {
        if (!confirm('Le format de la date semble inhabituel (attendu YYYY-MM-DD ou YYYY-MM-DD HH:MM:SS). Continuer quand même?')) {
            if (form.date_ajout) { _markInvalid(form.date_ajout); form.date_ajout.focus(); }
            return false;
        }
    }

    if (errors.length > 0) {
        alert('Veuillez corriger les erreurs :\n\n' + errors.join('\n'));
        try { if (form.querySelector('.is-invalid')) form.querySelector('.is-invalid').focus(); } catch(e){}
        return false;
    }
    return true;
}

// Backwards-compatible dispatch: keep validateForm API but auto-detect which specific validator to call
function validateForm(form) {
    // If nom_matiere exists, assume matiere form
    if (form.nom_matiere) return validateMatiereForm(form);
    // If resource-specific fields exist, use ressource validator
    if (form.type_ressource || form.url || form.auteur) return validateRessourceForm(form);
    // fallback: perform a light check on title/description
    var title = (form.titre && form.titre.value) ? form.titre.value.trim() : '';
    var desc = (form.description && form.description.value) ? form.description.value.trim() : '';
    if (title.length < 2) { alert('Titre trop court'); if (form.titre) _markInvalid(form.titre); return false; }
    if (desc.length < 3) { alert('Description trop courte'); if (form.description) _markInvalid(form.description); return false; }
    return true;
}
    // normalize
    var nom = (form.nom_matiere && form.nom_matiere.value) ? form.nom_matiere.value.trim() : '';
    var titre = (form.titre && form.titre.value) ? form.titre.value.trim() : '';
    var description = (form.description && form.description.value) ? form.description.value.trim() : '';
    var dateAj = (form.date_ajout && form.date_ajout.value) ? form.date_ajout.value.trim() : '';
    var niveau = (form.niveau_difficulte && form.niveau_difficulte.value) ? form.niveau_difficulte.value.trim() : '';

    // clear previous invalid states
    try { _clearInvalid(form.nom_matiere); _clearInvalid(form.titre); _clearInvalid(form.description); _clearInvalid(form.niveau_difficulte); } catch(e){}

    var errors = [];

    // Rules
    // Name: 2-100 chars, letters/numbers and some punctuation allowed
    var namePattern = /^[\p{L}\d\s\-_'.,()]{2,100}$/u;
    if (!namePattern.test(nom)) {
        errors.push('Nom matière: 2 à 100 caractères, lettres, chiffres et - _ \' . , ( ) autorisés.');
        if (form.nom_matiere) _markInvalid(form.nom_matiere);
    }

    // Title: 2-150 chars, similar to name
    var titlePattern = /^[\p{L}\d\s\-_'.,:;()]{2,150}$/u;
    if (!titlePattern.test(titre)) {
        errors.push('Titre: 2 à 150 caractères, caractères alphanumériques et ponctuation simple autorisés.');
        if (form.titre) _markInvalid(form.titre);
    }

    // Description: 10-2000 characters (allow most chars)
    if (description.length < 10 || description.length > 2000) {
        errors.push('Description: entre 10 et 2000 caractères.');
        if (form.description) _markInvalid(form.description);
    }

    // Niveau: 1-50 chars, letters numbers and + - allowed
    var niveauPattern = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s\-+]{1,50}$/;
    if (!niveauPattern.test(niveau)) {
        errors.push('Niveau difficulté: 1 à 50 caractères, lettres, chiffres, espaces, + et - autorisés.');
        if (form.niveau_difficulte) _markInvalid(form.niveau_difficulte);
    }

    // Date: not strictly required, but if present check basic format
    var datePattern = /^\d{4}-\d{2}-\d{2}(?:\s+\d{2}:\d{2}:\d{2})?$/;
    if (dateAj.length > 0 && !datePattern.test(dateAj)) {
        // not an error, but ask user to confirm
        if (!confirm('Le format de la date semble inhabituel (attendu YYYY-MM-DD ou YYYY-MM-DD HH:MM:SS). Continuer quand même?')) {
            if (form.date_ajout) { _markInvalid(form.date_ajout); form.date_ajout.focus(); }
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
