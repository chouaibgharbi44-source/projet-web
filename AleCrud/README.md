# AleCrud - CRUD Matière (MVC)

Structure minimale (MVC):

- `Model/` : classes d'accès aux données (`db.php`, `Matiere.php`)
- `Control/` : contrôleurs (`matiereController.php`)
- `View/` : vues PHP et HTML (`View/html/*.html` pour versions simples, `View/*.php` pour versions dynamiques)
- `index.php` : routeur frontal
- `database/matiere.sql` : script SQL pour créer la base et la table

Installation rapide (XAMPP sous Windows) :

1. Copier ce répertoire dans `c:\xampp\htdocs\AleCrud`.
2. Lancer Apache et MySQL via le panneau XAMPP.
3. Importer `database/matiere.sql` dans phpMyAdmin (ou via la ligne de commande MySQL) pour créer la base `alecrud` et la table `matiere`.
4. Vérifier les identifiants DB dans `Model/db.php` (par défaut `root` sans mot de passe).
5. Ouvrir `http://localhost/AleCrud/index.php` dans le navigateur.

Notes d'utilisation :

- Les vues HTML simples sont dans `View/html/` (HTML 4 simple, non-HTML5).
- Les vues PHP dynamiques sont dans `View/` et sont utilisées par le contrôleur.
- Le contrôleur lit `?action=` dans l'URL. Actions disponibles : `list`, `add`, `store`, `edit`, `update`, `delete`.

Exemples d'URL :

- `index.php` ou `index.php?action=list` : liste
- `index.php?action=add` : formulaire d'ajout
- `index.php?action=edit&id=1` : formulaire édit (id=1)
- `index.php?action=delete&id=1` : supprimer

Si vous voulez que je crée des validations, templates CSS ou boutons supplémentaires, dites-le et je les ajoute.
