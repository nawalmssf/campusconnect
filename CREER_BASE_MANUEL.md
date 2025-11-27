# 🔧 Guide pour créer la base de données manuellement

Si phpMyAdmin ne fonctionne pas, voici comment créer la base de données manuellement :

## Méthode 1 : Via la ligne de commande MySQL

### Étape 1 : Ouvrir MySQL en ligne de commande

1. Ouvrez l'Invite de commandes (cmd) en tant qu'administrateur
2. Allez dans le dossier MySQL de XAMPP :
   ```cmd
   cd C:\xampp\mysql\bin
   ```
3. Connectez-vous à MySQL :
   ```cmd
   mysql -u root
   ```
   (Si vous avez un mot de passe, utilisez : `mysql -u root -p`)

### Étape 2 : Créer la base de données

Copiez et collez ces commandes une par une dans MySQL :

```sql
CREATE DATABASE IF NOT EXISTS campusconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE campusconnect;
```

### Étape 3 : Créer les tables

Copiez et collez le contenu de `database.sql` dans MySQL, ou exécutez :

```cmd
mysql -u root campusconnect < C:\xampp\htdocs\campusconnect\database.sql
```

---

## Méthode 2 : Créer juste la structure minimale

Si vous voulez créer juste les tables essentielles, voici les commandes SQL :

```sql
CREATE DATABASE IF NOT EXISTS campusconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE campusconnect;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(50) NOT NULL UNIQUE,
    fullname VARCHAR(255) NOT NULL,
    group_id VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    session_1 TINYINT(1) DEFAULT 0,
    session_2 TINYINT(1) DEFAULT 0,
    session_3 TINYINT(1) DEFAULT 0,
    session_4 TINYINT(1) DEFAULT 0,
    session_5 TINYINT(1) DEFAULT 0,
    session_6 TINYINT(1) DEFAULT 0,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS participation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    session_1 TINYINT(1) DEFAULT 0,
    session_2 TINYINT(1) DEFAULT 0,
    session_3 TINYINT(1) DEFAULT 0,
    session_4 TINYINT(1) DEFAULT 0,
    session_5 TINYINT(1) DEFAULT 0,
    session_6 TINYINT(1) DEFAULT 0,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS attendance_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id VARCHAR(100) NOT NULL,
    group_id VARCHAR(50) NOT NULL,
    session_date DATE NOT NULL,
    opened_by VARCHAR(255) NOT NULL,
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## Méthode 3 : Via HeidiSQL (Interface graphique alternative)

1. Téléchargez HeidiSQL : https://www.heidisql.com/
2. Installez-le
3. Connectez-vous avec :
   - Host: localhost
   - User: root
   - Password: (laissez vide)
   - Port: 3306
4. Importez le fichier `database.sql`

---

## Vérification

Après avoir créé la base, vérifiez :

```sql
USE campusconnect;
SHOW TABLES;
```

Vous devriez voir :
- attendance
- attendance_sessions
- participation
- students

