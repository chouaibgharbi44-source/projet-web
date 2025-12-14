<?php
// Prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <title>Campus Connect - Événements</title>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" type="text/css" href="View/assets/style.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script type="text/javascript" src="View/assets/controle_saisie.js?v=<?php echo time(); ?>"></script>
    <script>console.log("DEBUG: Template version loaded successfully at " + new Date().toLocaleTimeString());</script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>

    <!-- Header & Navigation -->
    <header class="header">
        <div class="header-inner">
            <div class="logo">CAMPUS CONNECT</div>
            <nav class="navbar">
                <a href="../view/FrontOffice/homepage.php" class="nav-link">Accueil</a>
                <a href="#" class="nav-link active">Événements</a>
                <a href="#" class="nav-link">Messages</a>
                <a href="index.php?entity=reservation" class="nav-link">Réservations</a>
                <a href="#" class="nav-link">Groupes</a>
                <a href="../view/FrontOffice/profile.php" class="nav-link">Profil</a>
            </nav>

        </div>
    </header>

    <!-- Hero Section 1 -->
    <section class="hero-section">
        <div class="hero-content">
            <h2>Campus Events — Trouvez, partagez, participez</h2>
        </div>
    </section>

    <!-- Hero Section 2 & Add Form -->
    <section class="section-with-form">
        <div class="hero-text">
            <h3>Calendrier des événements</h3>
            <p>Explorez, triez et rejoignez les événements organisés par la communauté.</p>
        </div>
        <div class="filters">
            <input id="searchInput" class="search" placeholder="Rechercher par titre ou lieu..." />
            <select id="categoryFilter">
                <option value="">Toutes catégories</option>
                <option>Conférence</option>
                <option>Atelier</option>
                <option>Autre</option>
            </select>
            <button class="btn btn-add" onclick="openEventModal()">➕ Ajouter
                un événement</button>
        </div>
    </section>

    <!-- Modal Form -->
    <div id="formModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="document.getElementById('formModal').style.display='none';">&times;</span>
            <h3>Ajouter un événement</h3>
            <form method="post" action="index.php?action=store" onsubmit="return validateForm(this);" novalidate
                class="premium-form">
                <div class="form-group">
                    <label for="modal_title">Titre</label>
                    <input type="text" name="title" id="modal_title" placeholder="Titre de l'événement"
                        class="form-control" />
                </div>

                <div class="form-group">
                    <label for="modal_description">Description</label>
                    <textarea name="description" id="modal_description" rows="4" placeholder="Décrivez l'événement..."
                        class="form-control"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="modal_date">Date & Heure</label>
                        <input type="datetime-local" name="date" id="modal_date"
                            value="<?php echo date('Y-m-d\TH:i'); ?>" class="form-control" />
                    </div>
                    <div class="form-group half">
                        <label for="modal_capacity">Capacité</label>
                        <input type="number" name="capacity" id="modal_capacity" placeholder="Ex: 100"
                            class="form-control" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="modal_category">Catégorie</label>
                    <select name="category" id="modal_category" class="form-control">
                        <option value="Autre">Autre</option>
                        <option value="Conférence">Conférence</option>
                        <option value="Atelier">Atelier</option>
                        <option value="Rencontre">Rencontre</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="modal_location">Lieu</label>
                    <input type="text" name="location" id="modal_location" placeholder="Cliquez sur la carte..."
                        class="form-control" readonly style="margin-bottom:5px; background-color: #f9f9f9;" />
                    <div id="map"
                        style="height: 250px; width: 100%; border-radius: 6px; border: 1px solid #ccc; z-index: 1;">
                    </div>
                </div>

                <input type="hidden" name="created_by" value="1" />

                <div class="modal-actions">
                    <button class="btn btn-primary" type="submit">➕ Ajouter l'événement</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Shared Content Cards Section -->
    <section class="shared-content">
        <h3>Événements à venir</h3>
        <p class="section-desc">Rejoignez les événements qui vous intéressent — conférences, ateliers, rencontres.</p>
        <div class="cards-container">
            <?php if (!empty($evenements)): ?>
                <?php foreach ($evenements as $e): ?>
                    <div class="content-card">
                        <div class="card-top">
                            <span class="tag"><?php echo htmlspecialchars($e['category']); ?></span>
                            <span class="badge <?php echo $e['status']; ?>"><?php echo htmlspecialchars($e['status']); ?></span>
                        </div>
                        <h4 class="card-title"><?php echo htmlspecialchars($e['title']); ?></h4>
                        <div class="card-user">📍 <?php echo htmlspecialchars($e['location']); ?> •
                            <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($e['date']))); ?>
                        </div>
                        <p class="card-desc"><?php echo nl2br(htmlspecialchars($e['description'])); ?></p>
                        <div class="card-actions">
                            <a href="#" class="card-link">Voir</a>
                            <a class="btn-comment"
                                href="index.php?entity=reservation&action=add&event_id=<?php echo $e['id']; ?>">S'inscrire</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="content-card">Aucun événement prévu.</div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Campus Connect</h4>
                <p>Votre université, unie</p>
            </div>
            <div class="footer-col">
                <h4>Notre Contact</h4>
                <p>Email: compus@gmail.com</p>
                <p>Facebook: Compus Connect</p>
                <p>LinkedIn: Compus Connect</p>
                <p>Tel: +21655678904</p>
            </div>
            <div class="footer-col">
                <h4>Pages</h4>
                <a href="#">Accueil</a><br />
                <a href="#">Matériel</a><br />
                <a href="#">Événements</a>
            </div>
            <div class="footer-col">
                <h4>Pages</h4>
                <a href="#">Messages</a><br />
                <a href="#">Groupes</a><br />
                <a href="#">Profil</a>
            </div>
        </div>
        <div class="footer-copy">© 2025 - Campus Connect. Tous droits réservés.</div>
    </footer>

    <script type="text/javascript">
        // Map Integration
        var map;
        var marker;

        function initMap() {
            if (map) return; // Already initialized
            // Default to a central location (e.g., Tunisia generic or specific campus)
            // Using default coords: 36.8065, 10.1815 (Tunis)
            map = L.map('map').setView([36.8065, 10.1815], 13);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            map.on('click', function (e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(map);

                // Loading text
                var locInput = document.getElementById('modal_location');
                locInput.value = "Chargement de l'adresse...";

                // Photon (Komoot) Geocoding via standard Fetch (CORS friendly)
                var url = `https://photon.komoot.io/reverse?lon=${e.latlng.lng}&lat=${e.latlng.lat}&lang=fr`;

                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error("Erreur réseau");
                        return response.json();
                    })
                    .then(data => {
                        if (data.features && data.features.length > 0) {
                            var p = data.features[0].properties;
                            var parts = [];
                            // Construct address from available properties
                            if (p.name) parts.push(p.name);
                            if (p.street) parts.push(p.street + (p.housenumber ? " " + p.housenumber : ""));
                            if (p.district) parts.push(p.district);
                            if (p.city) parts.push(p.city);
                            if (p.country) parts.push(p.country);

                            // De-duplicate parts roughly (sometimes name == street)
                            var uniqueParts = [...new Set(parts)];

                            locInput.value = uniqueParts.join(', ');
                        } else {
                            locInput.value = e.latlng.lat.toFixed(6) + ", " + e.latlng.lng.toFixed(6);
                        }
                    })
                    .catch(err => {
                        console.error('Geocoding error:', err);
                        locInput.value = e.latlng.lat.toFixed(6) + ", " + e.latlng.lng.toFixed(6);
                    });
            });
        }

        function openEventModal() {
            document.getElementById('formModal').style.display = 'block';
            // Slight delay to allow modal to render so map can size correctly
            setTimeout(function () {
                if (!map) {
                    initMap();
                }
                map.invalidateSize();
            }, 100);
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            var modal = document.getElementById('formModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }


        // Basic client-side filtering of event cards
        document.addEventListener('DOMContentLoaded', function () {
            var search = document.getElementById('searchInput');
            var category = document.getElementById('categoryFilter');
            var cards = document.querySelectorAll('.cards-container .content-card');

            function filterCards() {
                var q = search.value.trim().toLowerCase();
                var cat = category.value;
                cards.forEach(function (card) {
                    var title = (card.querySelector('.card-title') || { innerText: '' }).innerText.toLowerCase();
                    var loc = (card.querySelector('.card-user') || { innerText: '' }).innerText.toLowerCase();
                    var tag = (card.querySelector('.tag') || { innerText: '' }).innerText;
                    var show = true;
                    if (q && title.indexOf(q) === -1 && loc.indexOf(q) === -1) show = false;
                    if (cat && tag.indexOf(cat) === -1) show = false;
                    card.style.display = show ? '' : 'none';
                });
            }

            if (search) search.addEventListener('input', filterCards);
            if (category) category.addEventListener('change', filterCards);
        });
    </script>

</body>

</html>