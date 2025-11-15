(function(){
    // Validation et gestion du formulaire de création d'événement (FrontOffice)
    var form = document.getElementById('createEventForm');
    if (!form) return;

    function showInlineError(message) {
        // Affiche une alerte simple; peut être remplacée par un affichage inline
        alert(message);
    }

    function isFutureDate(value) {
        if (!value) return false;
        // Accept formats: YYYY-MM-DD or YYYY-MM-DDTHH:MM or YYYY-MM-DD HH:MM
        var datePart = value;
        var timePart = '00:00';
        if (value.indexOf('T') !== -1) {
            var parts = value.split('T');
            datePart = parts[0];
            timePart = parts[1] || '00:00';
        } else if (value.indexOf(' ') !== -1) {
            var parts = value.split(' ');
            datePart = parts[0];
            timePart = parts[1] || '00:00';
        }
        // Validate basic patterns
        if (!/^\d{4}-\d{2}-\d{2}$/.test(datePart)) return false;
        if (!/^([01]\d|2[0-3]):[0-5]\d$/.test(timePart)) return false;

        var d = datePart.split('-');
        var t = timePart.split(':');
        var year = parseInt(d[0], 10);
        var month = parseInt(d[1], 10) - 1; // JS months 0-11
        var day = parseInt(d[2], 10);
        var hour = parseInt(t[0], 10);
        var minute = parseInt(t[1], 10);
        var dt = new Date(year, month, day, hour, minute, 0, 0);
        if (isNaN(dt.getTime())) return false;
        return dt.getTime() > Date.now();
    }

    form.addEventListener('submit', function(e){
        // Validation client
        var title = (form.querySelector('[name="title"]') || {value:''}).value.trim();
        var description = (form.querySelector('[name="description"]') || {value:''}).value.trim();
        var date = (form.querySelector('[name="date"]') || {value:''}).value.trim();
        var time = (form.querySelector('[name="time"]') || {value:''}).value.trim();
        var location = (form.querySelector('[name="location"]') || {value:''}).value.trim();
        var capacity = (form.querySelector('[name="capacity"]') || {value:''}).value.trim();
        var categoryEl = form.querySelector('[name="category"]');
        var category = categoryEl ? categoryEl.value : '';

        if (title.length < 3) { showInlineError('Le titre doit contenir au moins 3 caractères.'); e.preventDefault(); return; }
        if (description.length < 10) { showInlineError('La description doit contenir au moins 10 caractères.'); e.preventDefault(); return; }
        // date is optional: only validate if provided
        if (time !== '' && !/^([01]\d|2[0-3]):[0-5]\d$/.test(time)) { showInlineError('Le format de l\'heure est invalide (HH:MM).'); e.preventDefault(); return; }
        if (date !== '') {
            var dtCheck = date + (time !== '' ? 'T' + time : 'T00:00');
            if (!isFutureDate(dtCheck)) { showInlineError('La date/heure fournie est invalide ou dans le passé.'); e.preventDefault(); return; }
        }
        if (location.length < 3) { showInlineError('Le lieu doit contenir au moins 3 caractères.'); e.preventDefault(); return; }
        if (capacity !== '' && (!/^[0-9]+$/.test(capacity) || parseInt(capacity,10) < 1)) { showInlineError('La capacité doit être un nombre supérieur à 0.'); e.preventDefault(); return; }
        if (categoryEl && category === '') { showInlineError('Veuillez choisir une catégorie.'); e.preventDefault(); return; }

        // Laisser le formulaire s'envoyer normalement vers ../Controller/core_evenement.php
    });

    // Flash message si success=1 dans l'URL
    try {
        var params = new URLSearchParams(window.location.search);
        if (params.get('success') === '1'){
            var flash = document.getElementById('flashMessage');
            if (flash) {
                flash.style.display = 'block';
                setTimeout(function(){ flash.style.display = 'none'; window.history.replaceState({}, document.title, window.location.pathname); }, 3500);
            }
        }
    } catch (err) {
        // Ignorer
    }
})();
