# AleCrud - CRUD Matière & Ressources (MVC)

Structure minimale (MVC) avec deux entités : **Matière** et **Ressource**

## Structure des répertoires

- `Model/` : classes d'accès aux données (`db.php`, `Matiere.php`, `Ressource.php`)
- `Control/` : contrôleurs (`matiereController.php`, `ressourceController.php`)
- `View/` : vues PHP et HTML
  - `backoffice/` : vues d'administration
  - `frontoffice/` : vues publiques
  - `assets/` : CSS et JavaScript
- `config/` : configuration de la base de données
- `database/` : scripts SQL (`matiere.sql`, `ressource.sql`)
- `index.php` : routeur frontal

## Installation rapide (XAMPP sous Windows)

1. Copier ce répertoire dans `c:\xampp\htdocs\AleCrud`.
2. Lancer Apache et MySQL via le panneau XAMPP.
3. Importer les scripts SQL dans phpMyAdmin :
   - `database/matiere.sql` (crée la table `matiere`)
   - `database/ressource.sql` (crée la table `ressource`)
4. Vérifier les identifiants DB dans `config/config.php` (par défaut `root` sans mot de passe).
5. Ouvrir `http://localhost/AleCrud/index.php` dans le navigateur.

## Utilisation

### Frontoffice (Public)

- `index.php` : page d'accueil avec les matières
- `index.php?entity=ressource` : page des ressources
- Formulaires modaux pour ajouter du contenu

### Backoffice (Admin)

- `index.php?area=admin` : dashboard d'administration
- `index.php?area=admin` : gestion des matières
- `index.php?entity=ressource&area=admin` : gestion des ressources

### Paramètres d'URL

Le routeur utilise les paramètres suivants :

- `entity` : entité à gérer (`matiere` par défaut ou `ressource`)
- `area` : zone (`admin` pour backoffice, omis pour frontoffice)
- `action` : action CRUD (`list`, `add`, `store`, `edit`, `update`, `delete`)
- `id` : identifiant de l'enregistrement

### Exemples d'URL

#### Matière
- `index.php` : liste des matières (frontoffice)
- `index.php?area=admin` : gestion des matières (backoffice)
- `index.php?action=add&area=admin` : ajouter une matière
- `index.php?action=edit&id=1&area=admin` : modifier une matière
- `index.php?action=delete&id=1&area=admin` : supprimer une matière

#### Ressource
- `index.php?entity=ressource` : liste des ressources (frontoffice)
- `index.php?entity=ressource&area=admin` : gestion des ressources (backoffice)
- `index.php?entity=ressource&action=add&area=admin` : ajouter une ressource
- `index.php?entity=ressource&action=edit&id=1&area=admin` : modifier une ressource
- `index.php?entity=ressource&action=delete&id=1&area=admin` : supprimer une ressource

## Entités

### Matière

Champs :
- `id` : identifiant unique
- `nom_matiere` : nom de la matière
- `titre` : titre du contenu
- `description` : description détaillée
- `date_ajout` : date d'ajout (auto)
- `niveau_difficulte` : niveau de difficulté

### Ressource

Champs :
- `id` : identifiant unique
- `titre` : titre de la ressource
- `description` : description détaillée
- `type_ressource` : type (PDF, Tutoriel, Document, etc.)
- `url` : lien vers la ressource
- `auteur` : auteur de la ressource
- `date_ajout` : date d'ajout (auto)

## Architecture

Le projet suit le pattern MVC :

- **Model** : gestion des données et requêtes SQL
- **Controller** : traitement des requêtes et logique métier
- **View** : présentation des données (HTML/CSS)

Les contrôleurs utilisent un système de routage basé sur les paramètres GET.


