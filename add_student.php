<?php
// add_student.php - Version MySQL
require_once 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricule = $_POST['matricule'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $group_id = $_POST['group_id'] ?? '';

    // Validation
    $errors = [];
    
    if (empty($matricule)) {
        $errors[] = "Le matricule est requis";
    }
    
    if (empty($fullname)) {
        $errors[] = "Le nom complet est requis";
    } elseif (!preg_match('/^[A-Za-zÀ-ÿ\s\-]+$/', $fullname)) {
        $errors[] = "Le nom doit contenir uniquement des lettres et espaces";
    }
    
    if (empty($group_id)) {
        $errors[] = "Le groupe est requis";
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Erreurs de validation',
            'errors' => $errors
        ]);
        exit;
    }

    try {
        $conn = getDBConnection();
        
        // Vérifier si le matricule existe déjà
        $checkStmt = $conn->prepare("SELECT id FROM students WHERE matricule = ?");
        $checkStmt->execute([$matricule]);
        
        if ($checkStmt->fetch()) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur',
                'errors' => ['Un étudiant avec ce matricule existe déjà']
            ]);
            exit;
        }

        // Insérer le nouvel étudiant
        $insertStmt = $conn->prepare("INSERT INTO students (matricule, fullname, group_id) VALUES (?, ?, ?)");
        $insertStmt->execute([$matricule, $fullname, $group_id]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Étudiant ajouté avec succès!',
            'student_id' => $conn->lastInsertId()
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erreur base de données: ' . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
}
?>