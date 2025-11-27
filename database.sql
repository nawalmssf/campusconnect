-- ============================================
-- CampusConnect - Script de création de base de données
-- Exercices 3, 4 et 5
-- ============================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS campusconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE campusconnect;

-- ============================================
-- EXERCICE 4: Table students
-- ============================================
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(50) NOT NULL UNIQUE,
    fullname VARCHAR(255) NOT NULL,
    group_id VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_matricule (matricule),
    INDEX idx_group (group_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table attendance (Présence aux sessions)
-- ============================================
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
    UNIQUE KEY unique_student_attendance (student_id),
    INDEX idx_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table participation (Participation aux sessions)
-- ============================================
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
    UNIQUE KEY unique_student_participation (student_id),
    INDEX idx_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- EXERCICE 5: Table attendance_sessions
-- ============================================
CREATE TABLE IF NOT EXISTS attendance_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id VARCHAR(100) NOT NULL,
    group_id VARCHAR(50) NOT NULL,
    session_date DATE NOT NULL,
    opened_by VARCHAR(255) NOT NULL,
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL,
    INDEX idx_course (course_id),
    INDEX idx_group (group_id),
    INDEX idx_date (session_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Données de test (optionnel)
-- ============================================

-- Insérer quelques étudiants de test
INSERT INTO students (matricule, fullname, group_id) VALUES
('2024001', 'Ahmed Sara', 'TD-A'),
('2024002', 'Yasine Ali', 'TD-B'),
('2024003', 'Houcine Rania', 'TD-A')
ON DUPLICATE KEY UPDATE fullname=VALUES(fullname);

-- Créer les enregistrements de présence et participation pour les étudiants
INSERT INTO attendance (student_id, session_1, session_2, session_3, session_4, session_5, session_6)
SELECT id, 1, 1, 0, 0, 0, 0 FROM students WHERE matricule = '2024001'
ON DUPLICATE KEY UPDATE session_1=1, session_2=1;

INSERT INTO attendance (student_id, session_1, session_2, session_3, session_4, session_5, session_6)
SELECT id, 1, 0, 1, 1, 1, 1 FROM students WHERE matricule = '2024002'
ON DUPLICATE KEY UPDATE session_1=1;

INSERT INTO attendance (student_id, session_1, session_2, session_3, session_4, session_5, session_6)
SELECT id, 1, 1, 1, 0, 0, 0 FROM students WHERE matricule = '2024003'
ON DUPLICATE KEY UPDATE session_1=1;

INSERT INTO participation (student_id, session_1, session_2, session_3, session_4, session_5, session_6)
SELECT id, 1, 0, 0, 0, 0, 0 FROM students WHERE matricule = '2024001'
ON DUPLICATE KEY UPDATE session_1=1;

INSERT INTO participation (student_id, session_1, session_2, session_3, session_4, session_5, session_6)
SELECT id, 0, 1, 1, 1, 1, 0 FROM students WHERE matricule = '2024002'
ON DUPLICATE KEY UPDATE session_2=1;

INSERT INTO participation (student_id, session_1, session_2, session_3, session_4, session_5, session_6)
SELECT id, 1, 0, 1, 0, 0, 0 FROM students WHERE matricule = '2024003'
ON DUPLICATE KEY UPDATE session_1=1;

-- Insérer quelques sessions de test (Exercice 5)
INSERT INTO attendance_sessions (course_id, group_id, session_date, opened_by, status) VALUES
('MATH101', 'TD-A', '2025-01-15', 'Prof. Dupont', 'closed'),
('MATH101', 'TD-B', '2025-01-20', 'Prof. Martin', 'open'),
('INFO201', 'TD-A', '2025-01-25', 'Prof. Durand', 'open')
ON DUPLICATE KEY UPDATE status=VALUES(status);

-- ============================================
-- Vérification
-- ============================================
SELECT 'Base de données créée avec succès!' AS message;
SELECT COUNT(*) AS nombre_etudiants FROM students;
SELECT COUNT(*) AS nombre_sessions FROM attendance_sessions;

