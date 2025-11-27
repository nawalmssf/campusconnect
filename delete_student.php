<?php
// delete_student.php
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    try {
        $conn = getDBConnection();
        
        // Supprimer l'étudiant
        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        
        header("Location: list_students.php?message=deleted");
        exit;
        
    } catch (Exception $e) {
        header("Location: list_students.php?message=error&error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: list_students.php");
    exit;
}
?>