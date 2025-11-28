# AleCrud - CRUD Événements (MVC)

Structure minimale (MVC):

- `Model/` : classes d'accès aux données (`db.php`, `Evenement.php`, `Reservation.php`)
- `Control/` : contrôleurs (`evenementController.php`, `reservationController.php`)
- `View/` : vues PHP et HTML (`View/html/*.html` pour versions simples, `View/*.php` pour versions dynamiques)
- `index.php` : routeur frontal avec dispatcher d'entités
 - `database/basmacrud.sql` : **script SQL à utiliser** — crée la base `basmacrud` avec tables `evenements` (sans FK) et `reservations` + données d'exemple

Installation rapide (XAMPP sous Windows) :

1. Copier ce répertoire dans `c:\xampp\htdocs\BasmaCRUD`.
2. Lancer Apache et MySQL via le panneau XAMPP.
3. Importer `database/basmacrud.sql` dans phpMyAdmin (ou via la ligne de commande MySQL) pour créer la base `basmacrud` et les tables `evenements`, `reservations` avec données d'exemple.
4. Vérifier les identifiants DB dans `config/config.php` (par défaut `root` sans mot de passe, base `basmacrud`).
5. Ouvrir `http://localhost/BasmaCRUD/index.php` dans le navigateur.

Notes d'utilisation :

- Les vues HTML simples sont dans `View/html/` (HTML 4 simple, non-HTML5).
- Les vues PHP dynamiques sont dans `View/` et sont utilisées par le contrôleur.
- Le contrôleur lit `?action=` dans l'URL. Actions disponibles : `list`, `add`, `store`, `edit`, `update`, `delete`.
- Le dispatcher dans `index.php` lit `?entity=` pour charger le bon contrôleur (défaut : `evenement`).

Exemples d'URL :

**Événements (défaut)**
- `index.php` ou `index.php?entity=evenement` — liste frontoffice
- `index.php?area=admin` — liste backoffice admin
- `index.php?action=add&area=admin` — ajouter (admin)
- `index.php?action=edit&id=1&area=admin` — modifier (admin)
- `index.php?action=delete&id=1&area=admin` — supprimer (admin)

**Réservations**
- `index.php?entity=reservation` — liste frontoffice + créer réservation
- `index.php?entity=reservation&area=admin` — liste backoffice admin
- `index.php?entity=reservation&action=add&event_id=1` — pré-sélectionner un événement
- `index.php?entity=reservation&action=edit&id=1&area=admin` — modifier (admin)
- `index.php?entity=reservation&action=delete&id=1&area=admin` — supprimer (admin)