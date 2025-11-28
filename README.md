# 🎓 CampusConnect - Système de Gestion Scolaire

## 📚 Description
Système complet de gestion des étudiants, présence et sessions académiques, développé dans le cadre d'une série d'exercices progressifs.

---

## 🛠️ Technologies Utilisées
- **Backend**: PHP 8.0+
- **Base de données**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Bibliothèques**: 
  - jQuery 3.6.4
  - Chart.js (pour les graphiques)
  - Font Awesome 6.4.0

---

## 🚀 Installation

### Prérequis
1. **XAMPP**  installé
2. **PHP 8.0+**
3. **MySQL**

### Étapes d'installation

1. **Cloner/Télécharger le projet**
   ```bash
   cd C:\xampp\htdocs\
   # Placer le dossier campusconnect ici
   ```

2. **Créer la base de données**
   - Ouvrir phpMyAdmin (http://localhost/phpmyadmin)
   - Importer le fichier `database.sql`
   - Ou exécuter le script SQL manuellement

3. **Configurer la connexion**
   - Ouvrir `config.php`
   - Vérifier les paramètres de connexion :
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'campusconnect');
     ```

4. **Démarrer les services**
   - Démarrer Apache dans XAMPP
   - Démarrer MySQL dans XAMPP

5. **Accéder au projet**
   ```
   http://localhost/campusconnect/
   ```

---

## 📁 Structure du Projet

```
campusconnect/
├── index.php                 # Page principale (SPA)
├── script.js                 # Logique JavaScript
├── style.css                 # Styles CSS
├── config.php                # Configuration
├── db_connect.php            # Connexion base de données
│
├── add_student_handler.php   # Handler ajout étudiant (MySQL)
├── add_student_json.php      # Version JSON (Exercice 1)
├── list_students.php         # Liste des étudiants
├── update_student.php        # Modification étudiant
├── delete_student.php        # Suppression étudiant
│
├── take_attendance.php       # Prise de présence (JSON)
│
├── create_session.php        # Création session (API)
├── close_session.php         # Fermeture session (API)
├── manage_sessions.php       # Gestion des sessions
│
├── students.json             # Données étudiants (JSON)
├── attendance_*.json         # Fichiers de présence
│
├── database.sql              # Script de création BD
├── database_errors.log       # Logs d'erreurs BD
│
└── README.md                 # Documentation
```

---

## 📋 Exercices Implémentés

### ✅ Tutorial 2: JavaScript

#### Exercice 1: Modifier le Tableau de Présence
- ✅ Tableau avec 6 sessions
- ✅ Comptage des absences et participations
- ✅ Mise en évidence par couleur (vert/jaune/rouge)
- ✅ Messages dynamiques

#### Exercice 2: Validation de Formulaire
- ✅ Validation ID étudiant (chiffres uniquement)
- ✅ Validation nom/prénom (lettres uniquement)
- ✅ Messages d'erreur en temps réel
- ✅ Blocage de soumission si erreurs

---

### ✅ Exercices PHP/JSON

#### Exercice 1: `add_student.php` (JSON)
- ✅ Chargement depuis `students.json`
- ✅ Ajout d'étudiant
- ✅ Sauvegarde dans JSON
- ✅ Validation complète

#### Exercice 2: `take_attendance.php`
- ✅ Chargement depuis `students.json`
- ✅ Création de fichiers `attendance_YYYY-MM-DD.json`
- ✅ Vérification de doublons

---

### ✅ Exercices Base de Données

#### Exercice 3: Connexion Base de Données
- ✅ `config.php` avec paramètres
- ✅ `db_connect.php` avec try/catch
- ✅ Logging des erreurs
- ✅ Gestion d'erreurs propre

#### Exercice 4: CRUD Étudiants
- ✅ `add_student_handler.php` - Ajouter
- ✅ `list_students.php` - Lister
- ✅ `update_student.php` - Modifier
- ✅ `delete_student.php` - Supprimer

#### Exercice 5: Gestion des Sessions
- ✅ `create_session.php` - Créer session
- ✅ `close_session.php` - Fermer session
- ✅ `manage_sessions.php` - Interface de gestion

---

## 🎯 Fonctionnalités

### 1. Gestion des Étudiants
- ✅ Ajout d'étudiants (formulaire avec validation)
- ✅ Liste des étudiants
- ✅ Modification des informations
- ✅ Suppression d'étudiants

### 2. Tableau de Présence
- ✅ Visualisation des présences par session
- ✅ Visualisation des participations
- ✅ Calcul automatique des absences
- ✅ Mise en évidence par couleur
- ✅ Messages de statut dynamiques

### 3. Prise de Présence
- ✅ Prise de présence quotidienne (JSON)
- ✅ Vérification des doublons
- ✅ Sauvegarde par date

### 4. Gestion des Sessions
- ✅ Création de sessions
- ✅ Fermeture de sessions
- ✅ Suivi des sessions ouvertes/fermées

### 5. Rapports et Statistiques
- ✅ Statistiques globales
- ✅ Graphiques de présence
- ✅ Graphiques de participation
- ✅ Taux de présence moyen

---

## 🎨 Interface Utilisateur

### Pages Disponibles

1. **Tableau de Présence**
   - Statistiques en temps réel
   - Tableau interactif
   - Boutons de mise en évidence

2. **Formulaire d'Ajout**
   - Validation en temps réel
   - Messages d'erreur clairs
   - Design moderne

3. **Gestion des Étudiants**
   - Cartes d'étudiants
   - Actions rapides

4. **Gestion des Sessions**
   - Interface dédiée
   - Création/Fermeture

5. **Rapports**
   - Graphiques interactifs
   - Statistiques détaillées

---

## 🔧 Configuration

### Fichier `config.php`

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'campusconnect');
define('DEBUG_MODE', true); // false en production
```

### Base de Données

Le fichier `database.sql` contient :
- Création de toutes les tables
- Contraintes et index
- Données de test (optionnel)

---

## 📊 Structure de la Base de Données

### Table `students`
- `id` - Identifiant unique
- `matricule` - Matricule de l'étudiant
- `fullname` - Nom complet
- `group_id` - Groupe
- `created_at` - Date de création

### Table `attendance`
- `id` - Identifiant unique
- `student_id` - ID de l'étudiant (FK)
- `session_1` à `session_6` - Présence par session

### Table `participation`
- `id` - Identifiant unique
- `student_id` - ID de l'étudiant (FK)
- `session_1` à `session_6` - Participation par session

### Table `attendance_sessions`
- `id` - Identifiant unique
- `course_id` - ID du cours
- `group_id` - Groupe
- `session_date` - Date de la session
- `opened_by` - Professeur
- `status` - Statut (open/closed)

---

