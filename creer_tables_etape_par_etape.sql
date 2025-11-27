-- ============================================
-- Script pour créer les tables ÉTAPE PAR ÉTAPE
-- À exécuter UNE TABLE à la fois dans phpMyAdmin
-- ============================================

-- ÉTAPE 1 : Créer la base de données (si elle n'existe pas)
CREATE DATABASE IF NOT EXISTS campusconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sélectionner la base
USE campusconnect;

-- ÉTAPE 2 : Créer la table students (EXÉCUTEZ CECI EN PREMIER)
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(50) NOT NULL UNIQUE,
    fullname VARCHAR(255) NOT NULL,
    group_id VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ÉTAPE 3 : Vérifier que la table students existe
SELECT 'Table students créée!' AS message;

-- ÉTAPE 4 : Créer la table attendance (EXÉCUTEZ APRÈS students)
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    session_1 TINYINT(1) DEFAULT 0,
    session_2 TINYINT(1) DEFAULT 0,
    session_3 TINYINT(1) DEFAULT 0,
    session_4 TINYINT(1) DEFAULT 0,
    session_5 TINYINT(1) DEFAULT 0,
    session_6 TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_attendance (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ÉTAPE 5 : Créer la table participation (EXÉCUTEZ APRÈS students)
CREATE TABLE IF NOT EXISTS participation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    session_1 TINYINT(1) DEFAULT 0,
    session_2 TINYINT(1) DEFAULT 0,
    session_3 TINYINT(1) DEFAULT 0,
    session_4 TINYINT(1) DEFAULT 0,
    session_5 TINYINT(1) DEFAULT 0,
    session_6 TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_participation (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ÉTAPE 6 : Créer la table attendance_sessions
CREATE TABLE IF NOT EXISTS attendance_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id VARCHAR(100) NOT NULL,
    group_id VARCHAR(50) NOT NULL,
    session_date DATE NOT NULL,
    opened_by VARCHAR(255) NOT NULL,
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ÉTAPE 7 : Vérifier toutes les tables
SHOW TABLES;

