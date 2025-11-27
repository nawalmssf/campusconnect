<?php
// add_student_handler.php
// Exercice 4: Ajout d'étudiant avec gestion d'erreurs améliorée
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricule = trim($_POST['matricule'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $group_id = trim($_POST['group_id'] ?? '');
    
    // Validation des données
    $errors = [];
    
    if (empty($matricule)) {
        $errors[] = 'matricule_required';
    } elseif (!preg_match('/^\d+$/', $matricule)) {
        $errors[] = 'matricule_invalid';
    }
    
    if (empty($fullname)) {
        $errors[] = 'fullname_required';
    } elseif (!preg_match('/^[A-Za-zÀ-ÿ\s\-\']+$/', $fullname)) {
        $errors[] = 'fullname_invalid';
    }
    
    if (empty($group_id)) {
        $errors[] = 'group_required';
    }
    
    if (!empty($errors)) {
        header("Location: index.php?error=" . urlencode(implode(',', $errors)));
        exit;
    }

    try {
        $conn = getDBConnection();
        
        // Vérifier si le matricule existe déjà
        $checkStmt = $conn->prepare("SELECT id FROM students WHERE matricule = ?");
        $checkStmt->execute([$matricule]);
        
        if ($checkStmt->fetch()) {
            header("Location: index.php?page=formulaire&error=matricule_exists");
            exit;
        }

        // Démarrer une transaction
        $conn->beginTransaction();

        // Insérer le nouvel étudiant
        $insertStmt = $conn->prepare("INSERT INTO students (matricule, fullname, group_id) VALUES (?, ?, ?)");
        $insertStmt->execute([$matricule, $fullname, $group_id]);
        $student_id = $conn->lastInsertId();
        
        // Créer les enregistrements de présence et participation par défaut
        $attendanceStmt = $conn->prepare("INSERT INTO attendance (student_id) VALUES (?)");
        $attendanceStmt->execute([$student_id]);
        
        $participationStmt = $conn->prepare("INSERT INTO participation (student_id) VALUES (?)");
        $participationStmt->execute([$student_id]);
        
        // Valider la transaction
        $conn->commit();
        
        header("Location: index.php?page=formulaire&message=success");
        exit;
        
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        
        // Logger l'erreur
        error_log("Erreur lors de l'ajout d'étudiant: " . $e->getMessage());
        
        header("Location: index.php?page=formulaire&error=database_error");
        exit;
    } catch (Exception $e) {
        if (isset($conn) && $conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("Erreur générale: " . $e->getMessage());
        header("Location: index.php?page=formulaire&error=database_error");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>