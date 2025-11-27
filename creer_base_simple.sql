-- Script simplifié pour créer la base de données
-- À exécuter directement dans MySQL

CREATE DATABASE IF NOT EXISTS campusconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE campusconnect;

-- Table students
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(50) NOT NULL UNIQUE,
    fullname VARCHAR(255) NOT NULL,
    group_id VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table attendance
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table participation
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table attendance_sessions
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

-- Vérification
SELECT 'Base de données créée avec succès!' AS message;
SHOW TABLES;

