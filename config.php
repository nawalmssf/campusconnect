<?php
// config.php - Configuration XAMPP
// Exercice 3: Configuration de la base de données

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'campusconnect');
define('DB_CHARSET', 'utf8mb4');

// Mode debug (mettre à false en production)
define('DEBUG_MODE', true);

// Configuration générale
define('SITE_NAME', 'CampusConnect');
define('SITE_URL', 'http://localhost/campusconnect');

// Configuration des sessions JSON (pour les exercices)
define('STUDENTS_JSON_FILE', __DIR__ . '/students.json');
define('ATTENDANCE_JSON_DIR', __DIR__ . '/');
?>