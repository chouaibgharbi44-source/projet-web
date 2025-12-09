# Système de Validation JavaScript - Contrôle de Saisie

## 📋 Résumé des modifications

La validation HTML5 a été remplacée par un système de validation JavaScript personnalisé qui affiche les erreurs en **rouge sous chaque champ** au lieu d'utiliser les alertes natives du navigateur.

---

## 🎯 Fichiers modifiés

### 1. **Nouveau fichier créé**
- `View/assets/controle_saisie.js` - Système de validation JavaScript complet

### 2. **Formulaires modifiés**
- `View/backoffice/add.php` - Formulaire d'ajout d'événement
- `View/backoffice/reservations/add.php` - Formulaire d'ajout de réservation

### 3. **Styles mis à jour**
- `View/assets/style.css` - Ajout des styles pour `.error-message`

### 4. **Fichier de test**
- `test_validation.html` - Page de démonstration

---

## ✨ Fonctionnalités du nouveau système

### Affichage des erreurs
- ✅ Messages d'erreur en **rouge** sous chaque champ invalide
- ✅ Bordure rouge avec animation de secousse sur les champs invalides
- ✅ Animation d'apparition fluide des messages d'erreur
- ✅ Les erreurs disparaissent automatiquement lors de la saisie

### Validation en temps réel
- ✅ Détection automatique du type de formulaire (événement ou réservation)
- ✅ Validation déclenchée à la soumission du formulaire
- ✅ Focus automatique sur le premier champ invalide
- ✅ Effacement des erreurs lors de la saisie (événement `input` et `change`)

### Règles de validation

#### **Formulaire Événement**
| Champ | Règle | Message d'erreur |
|-------|-------|------------------|
| Titre | Obligatoire, 2-150 caractères | "Le titre est obligatoire" / "Le titre doit contenir entre 2 et 150 caractères" |
| Description | Obligatoire, 10-4000 caractères | "La description est obligatoire" / "La description doit contenir au moins 10 caractères" |
| Date | Obligatoire, format YYYY-MM-DD HH:MM | "La date est obligatoire" / "Format attendu: YYYY-MM-DD HH:MM" |
| Lieu | Obligatoire, min 2 caractères | "Le lieu est obligatoire" / "Le lieu doit contenir au moins 2 caractères" |
| Capacité | Optionnel, nombre entier positif | "La capacité doit être un nombre entier positif" |

#### **Formulaire Réservation**
| Champ | Règle | Message d'erreur |
|-------|-------|------------------|
| Événement | Obligatoire | "Vous devez sélectionner un événement" |
| Nom | Obligatoire, 2-255 caractères | "Le nom est obligatoire" / "Le nom doit contenir au moins 2 caractères" |
| Email | Optionnel, format email valide | "L'adresse email n'est pas valide" |
| Places | Obligatoire, nombre entier ≥ 1 | "Le nombre de places est obligatoire" / "Le nombre de places doit être au moins 1" |

---

## 🔧 Changements techniques

### Attributs HTML5 retirés
Les attributs suivants ont été **supprimés** des formulaires :
- `required` - Validation obligatoire
- `type="email"` - Validation email
- `type="number"` - Validation numérique
- `min="1"` - Valeur minimale

### Nouveaux attributs ajoutés
- `id` unique pour chaque champ (nécessaire pour l'affichage des erreurs)
- `type="text"` pour tous les champs (au lieu de types spécifiques HTML5)

### Structure du code JavaScript

```javascript
// Fonctions principales
validateForm(form)              // Détecte le type et appelle la bonne validation
validateEventForm(form)         // Validation spécifique aux événements
validateReservationForm(form)   // Validation spécifique aux réservations

// Fonctions utilitaires
showError(field, message)       // Affiche une erreur sous un champ
clearError(field)               // Efface l'erreur d'un champ
clearAllErrors(form)            // Efface toutes les erreurs d'un formulaire
getErrorElement(field)          // Crée ou récupère l'élément d'erreur
```

---

## 🎨 Styles CSS

### Classe `.error-message`
```css
.error-message {
  color: #dc143c;              /* Rouge vif */
  font-size: 0.9em;            /* Légèrement plus petit */
  margin-top: 5px;             /* Espacement au-dessus */
  margin-bottom: 10px;         /* Espacement en-dessous */
  font-weight: 500;            /* Semi-gras */
  display: none;               /* Caché par défaut */
  animation: fadeInError 300ms ease;  /* Animation d'apparition */
}
```

### Classe `.is-invalid` (déjà existante)
- Bordure rouge de 2px
- Ombre portée rouge
- Animation de secousse (shake)

---

## 🧪 Comment tester

### Option 1 : Page de test dédiée
Ouvrez `test_validation.html` dans votre navigateur pour tester les deux formulaires avec des instructions détaillées.

### Option 2 : Formulaires réels
1. Accédez au backoffice
2. Allez sur "Ajouter un événement" ou "Ajouter une réservation"
3. Essayez de soumettre le formulaire vide
4. Observez les messages d'erreur en rouge sous chaque champ

### Scénarios de test

#### Test 1 : Champs vides
1. Laissez tous les champs vides
2. Cliquez sur "Enregistrer"
3. **Résultat attendu** : Messages d'erreur sous tous les champs obligatoires

#### Test 2 : Formats invalides
1. Entrez "a" dans le titre (trop court)
2. Entrez "test" dans la description (trop court)
3. Entrez "2025/12/15" dans la date (mauvais format)
4. Entrez "abc" dans la capacité (non numérique)
5. **Résultat attendu** : Messages d'erreur spécifiques pour chaque problème

#### Test 3 : Correction en temps réel
1. Soumettez le formulaire vide pour voir les erreurs
2. Commencez à taper dans un champ invalide
3. **Résultat attendu** : L'erreur disparaît immédiatement

---

## 📝 Notes importantes

### Compatibilité
- ✅ Fonctionne avec tous les navigateurs modernes
- ✅ Compatible avec HTML4 (DOCTYPE utilisé)
- ✅ Pas de dépendances externes (vanilla JavaScript)

### Avantages par rapport à HTML5
- ✅ Messages d'erreur personnalisés en français
- ✅ Affichage visuel sous les champs (plus intuitif)
- ✅ Contrôle total sur le style et le comportement
- ✅ Validation cohérente sur tous les navigateurs
- ✅ Possibilité d'ajouter des règles métier complexes

### Maintenance future
Pour ajouter de nouvelles règles de validation :
1. Ouvrez `View/assets/controle_saisie.js`
2. Modifiez les fonctions `validateEventForm()` ou `validateReservationForm()`
3. Ajoutez vos règles avec `showError(field, "Votre message")`

---

## 🚀 Prochaines étapes possibles

- [ ] Ajouter la validation sur les formulaires d'édition
- [ ] Implémenter la validation côté serveur (PHP)
- [ ] Ajouter des indicateurs visuels de champs valides (✓ vert)
- [ ] Créer un système de validation réutilisable pour d'autres formulaires
- [ ] Ajouter la validation en temps réel (pendant la saisie)

---

## 📞 Support

Si vous rencontrez des problèmes :
1. Vérifiez que `controle_saisie.js` est bien chargé (F12 → Console)
2. Vérifiez que tous les champs ont un attribut `id` unique
3. Vérifiez que le formulaire a `onsubmit="return validateForm(this);"`

---

**Date de création** : 2 décembre 2025  
**Version** : 1.0  
**Auteur** : Système de validation personnalisé
