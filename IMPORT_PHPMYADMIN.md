# 📋 Guide d'import dans phpMyAdmin - ÉTAPE PAR ÉTAPE

## ✅ Vous avez accès à phpMyAdmin sur http://localhost:8080/

Voici comment importer correctement :

---

## 🎯 Méthode 1 : Import du fichier complet (RECOMMANDÉ)

### Étape 1 : Sélectionner la base
1. Dans phpMyAdmin, à **gauche**, cherchez "campusconnect"
2. Si elle n'existe pas, créez-la :
   - Cliquez sur "Nouveau" ou "Nouvelle base de données"
   - Nom : `campusconnect`
   - Interclassement : `utf8mb4_unicode_ci`
   - Cliquez "Créer"

### Étape 2 : Importer le fichier
1. Cliquez sur l'onglet **"Importer"** en haut
2. Cliquez sur **"Choisir un fichier"**
3. Sélectionnez : `C:\xampp\htdocs\campusconnect\database.sql`
4. Cliquez sur **"Exécuter"**

✅ **C'est tout !** Toutes les tables seront créées automatiquement.

---

## 🎯 Méthode 2 : Copier-coller dans SQL (si l'import ne fonctionne pas)

### Étape 1 : Créer la base
1. Cliquez sur "Nouveau" à gauche
2. Nom : `campusconnect`
3. Interclassement : `utf8mb4_unicode_ci`
4. Cliquez "Créer"

### Étape 2 : Sélectionner la base
- Cliquez sur `campusconnect` dans la liste à gauche

### Étape 3 : Ouvrir l'onglet SQL
1. Cliquez sur l'onglet **"SQL"** en haut
2. **IMPORTANT** : Collez TOUT le contenu du fichier `database.sql`
3. Pas juste les lignes INSERT ! Tout le fichier !
4. Cliquez sur **"Exécuter"**

---

## ⚠️ ERREUR : "Table doesn't exist"

**Vous avez cette erreur parce que vous avez collé seulement les lignes INSERT sans créer les tables d'abord !**

### Solution :
1. **Ouvrez le fichier `database.sql` complet**
2. **Copiez TOUT** (depuis CREATE DATABASE jusqu'à la fin)
3. **Collez TOUT dans phpMyAdmin** (onglet SQL)
4. **Exécutez**

---

## 🔍 Ordre correct des opérations

Le script SQL fait dans cet ordre :
1. ✅ Crée la base de données
2. ✅ Sélectionne la base
3. ✅ Crée la table `students`
4. ✅ Crée la table `attendance`
5. ✅ Crée la table `participation`
6. ✅ Crée la table `attendance_sessions`
7. ✅ Insère les données de test

**Tout doit être exécuté ensemble !**

---

## ✅ Vérification

Après l'import, vous devriez voir dans phpMyAdmin :

1. Base `campusconnect` dans la liste de gauche
2. 4 tables :
   - `attendance`
   - `attendance_sessions`
   - `participation`
   - `students`

3. Si vous cliquez sur `students`, vous devriez voir 3 étudiants de test

---

## 🚀 Méthode la plus simple

**Dans phpMyAdmin :**
1. Cliquez sur "Importer" (pas SQL !)
2. Choisissez le fichier `database.sql`
3. Cliquez "Exécuter"

**C'est tout !** ✅

