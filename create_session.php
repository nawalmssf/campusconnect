<?php
// create_session.php
require_once 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'] ?? '';
    $group_id = $_POST['group_id'] ?? '';
    $opened_by = $_POST['opened_by'] ?? '';
    $session_date = $_POST['session_date'] ?? date('Y-m-d');

    // Validation
    $errors = [];
    
    if (empty($course_id)) $errors[] = "Le cours est requis";
    if (empty($group_id)) $errors[] = "Le groupe est requis";
    if (empty($opened_by)) $errors[] = "Le professeur est requis";

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
        
        // Insérer la nouvelle session
        $stmt = $conn->prepare("INSERT INTO attendance_sessions (course_id, group_id, session_date, opened_by, status) VALUES (?, ?, ?, ?, 'open')");
        $stmt->execute([$course_id, $group_id, $session_date, $opened_by]);
        
        $session_id = $conn->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Session créée avec succès!',
            'session_id' => $session_id,
            'session_data' => [
                'course_id' => $course_id,
                'group_id' => $group_id,
                'session_date' => $session_date,
                'opened_by' => $opened_by
            ]
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