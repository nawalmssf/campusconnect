<?php
// db_connect.php
// Exercice 3: Connexion à la base de données avec gestion d'erreurs

// Charger la configuration
require_once __DIR__ . '/config.php';

function getDBConnection() {
    static $conn = null;
    
    // Si la connexion existe déjà, la retourner
    if ($conn !== null) {
        return $conn;
    }
    
    $host = DB_HOST;
    $db = DB_NAME;
    $user = DB_USER;
    $pass = DB_PASS;
    $charset = DB_CHARSET;

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $conn = new PDO($dsn, $user, $pass, $options);
        
        // Enregistrer le succès dans le log (optionnel)
        $logFile = __DIR__ . '/database_errors.log';
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Connection successful\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND);
        
        return $conn;
    } catch (PDOException $e) {
        // Enregistrer l'erreur dans un fichier de log
        $logFile = __DIR__ . '/database_errors.log';
        $errorMessage = "[" . date('Y-m-d H:i:s') . "] Connection failed: " . $e->getMessage() . "\n";
        file_put_contents($logFile, $errorMessage, FILE_APPEND);
        
        // En production, ne pas afficher les détails de l'erreur
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            die('Database connection failed: ' . $e->getMessage());
        } else {
            die('Erreur de connexion à la base de données. Veuillez contacter l\'administrateur.');
        }
    }
}

