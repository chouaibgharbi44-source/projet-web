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

function validateForm(form) {
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
