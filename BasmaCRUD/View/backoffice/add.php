<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Événement - Campus Connect Admin</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="View/assets/style.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script type="text/javascript" src="View/assets/controle_saisie.js"></script>
    <style>
        /* Dashboard Specific Internal Styles */
        :root {
            --sidebar-width: 260px;
            --primary-gradient: linear-gradient(135deg, #7b2da8 0%, #ff6fb1 100%);
            --bg-body: #f4f7fe;
        }

        body {
            background-color: var(--bg-body);
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: white;
            position: fixed;
            left: 0;
            top: 0;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 40px;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            z-index: 100;
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 15px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            color: #888;
            text-decoration: none;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .nav-item a:hover,
        .nav-item.active a {
            background: rgba(123, 45, 168, 0.08);
            color: #7b2da8;
            font-weight: 600;
        }

        .nav-item a i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
        }

        .header-title-page h1 {
            font-size: 24px;
            color: #2b3674;
            margin: 0 0 10px 0;
            font-weight: 700;
        }

        .form-container-clean {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            max-width: 900px;
            margin: 0 auto;
        }

        .form-row-cs {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
        }

        .form-group-cs {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group-cs label {
            font-weight: 600;
            color: #2b3674;
            font-size: 14px;
        }

        .form-input-cs {
            padding: 14px;
            border: 1px solid #e0e5f2;
            border-radius: 12px;
            background: #fdfaff;
            font-size: 14px;
            color: #333;
            outline: none;
            transition: all 0.3s;
        }

        .form-input-cs:focus {
            border-color: #7b2da8;
            box-shadow: 0 0 0 4px rgba(123, 45, 168, 0.1);
            background: #fff;
        }

        textarea.form-input-cs {
            resize: vertical;
            min-height: 120px;
        }

        .btn-submit-cs {
            background: var(--primary-gradient);
            color: white;
            padding: 16px 32px;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 10px 20px rgba(123, 45, 168, 0.2);
            align-self: flex-end;
            margin-top: 10px;
        }

        .btn-submit-cs:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(123, 45, 168, 0.3);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #888;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #7b2da8;
        }

        .error-msg {
            margin-top: 4px;
            color: #e14d5a;
            font-size: 12px;
            display: none;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-layer-group"></i> Campus Connect
        </div>
        <ul class="nav-menu">
            <li class="nav-item active">
                <a href="index.php?area=admin">
                    <i class="fas fa-calendar-alt"></i> Événements
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?entity=reservation&area=admin">
                    <i class="fas fa-ticket-alt"></i> Réservations
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php?entity=stats&area=admin">
                    <i class="fas fa-chart-pie"></i> Statistiques
                </a>
            </li>
            <li class="nav-item">
                <a href="index.php">
                    <i class="fas fa-desktop"></i> Frontoffice
                </a>
            </li>
            <li class="nav-item" style="margin-top:auto">
                <a href="index.php?admin=logout" style="color:#e14d5a;">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <a href="index.php?area=admin" class="back-link"><i class="fas fa-arrow-left"></i> Retour à la liste</a>

        <div class="header-title-page">
            <h1>Nouveau Événement</h1>
        </div>

        <div class="form-container-clean">
            <form method="post" action="index.php?action=store&area=admin" onsubmit="return validateForm(this);"
                novalidate>

                <div class="form-row-cs">
                    <div class="form-group-cs">
                        <label for="title">Titre de l'événement</label>
                        <input type="text" name="title" id="title" class="form-input-cs"
                            placeholder="Ex: Hackathon 2025" required />
                        <span class="error-msg">Ce champ est requis</span>
                    </div>
                    <div class="form-group-cs">
                        <label for="category">Catégorie</label>
                        <select name="category" id="category" class="form-input-cs">
                            <option value="Autre">Autre</option>
                            <option value="Conférence">Conférence</option>
                            <option value="Atelier">Atelier</option>
                            <option value="Rencontre">Rencontre</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-cs" style="margin-bottom:20px;">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-input-cs"
                        placeholder="Détails de l'événement..."></textarea>
                </div>

                <div class="form-row-cs">
                    <div class="form-group-cs">
                        <label for="date">Date & Heure</label>
                        <input type="datetime-local" name="date" id="date" class="form-input-cs"
                            value="<?php echo date('Y-m-d\TH:i'); ?>" />
                    </div>
                    <div class="form-group-cs">
                        <label for="capacity">Capacité (personnes)</label>
                        <input type="number" name="capacity" id="capacity" class="form-input-cs" placeholder="Ex: 50" />
                    </div>
                </div>

                <div class="form-row-cs">
                    <div class="form-group-cs">
                        <label for="status">Statut Initial</label>
                        <select name="status" id="status" class="form-input-cs">
                            <option value="approved">Approuvé (Visible)</option>
                            <option value="pending">En attente</option>
                            <option value="rejected">Rejeté</option>
                        </select>
                    </div>
                    <div class="form-group-cs">
                        <label for="image">Image (URL)</label>
                        <input type="text" name="image" id="image" class="form-input-cs" placeholder="https://..." />
                    </div>
                </div>

                <div class="form-group-cs" style="margin-bottom:20px;">
                    <label for="location">Lieu</label>
                    <input type="text" name="location" id="location" class="form-input-cs" readonly
                        placeholder="Cliquez sur la carte..." style="background-color: #f9f9f9; margin-bottom:10px;" />
                    <div id="map"
                        style="height: 300px; width: 100%; border-radius: 12px; border: 1px solid #e0e5f2; z-index: 1;">
                    </div>
                </div>

                <input type="hidden" name="created_by" value="1" />

                <div style="text-align:right;">
                    <button type="submit" class="btn-submit-cs">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>

            </form>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Map Integration
            var map = L.map('map').setView([36.8065, 10.1815], 13);
            var marker;

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            map.on('click', function (e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(map);

                var locInput = document.getElementById('location');
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
                            // Construct address
                            if (p.name) parts.push(p.name);
                            if (p.street) parts.push(p.street + (p.housenumber ? " " + p.housenumber : ""));
                            if (p.district) parts.push(p.district);
                            if (p.city) parts.push(p.city);
                            if (p.country) parts.push(p.country);

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
        });
    </script>
</body>

</html>