<?php
// close_session.php
require_once 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session_id = $_POST['session_id'] ?? '';

    if (empty($session_id)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'ID de session requis'
        ]);
        exit;
    }

    try {
        $conn = getDBConnection();
        
        // Vérifier si la session existe
        $checkStmt = $conn->prepare("SELECT id, status FROM attendance_sessions WHERE id = ?");
        $checkStmt->execute([$session_id]);
        $session = $checkStmt->fetch();
        
        if (!$session) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Session non trouvée'
            ]);
            exit;
        }
        
        if ($session['status'] === 'closed') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Cette session est déjà fermée'
            ]);
            exit;
        }

        // Fermer la session
        $updateStmt = $conn->prepare("UPDATE attendance_sessions SET status = 'closed', closed_at = NOW() WHERE id = ?");
        $updateStmt->execute([$session_id]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Session fermée avec succès!',
            'session_id' => $session_id
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