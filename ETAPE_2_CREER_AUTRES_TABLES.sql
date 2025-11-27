-- ============================================
-- ÉTAPE 2 : Créer les autres tables
-- Exécutez CECI après avoir créé la table students
-- ============================================

USE campusconnect;

-- Table attendance
CREATE TABLE attendance (
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

-- Table participation
CREATE TABLE participation (
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

-- Table attendance_sessions
CREATE TABLE attendance_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id VARCHAR(100) NOT NULL,
    group_id VARCHAR(50) NOT NULL,
    session_date DATE NOT NULL,
    opened_by VARCHAR(255) NOT NULL,
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vérification
SHOW TABLES;

