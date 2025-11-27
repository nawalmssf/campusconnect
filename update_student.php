<?php
// update_student.php
require_once 'db_connect.php';

$student = null;
$error = '';

// Récupérer l'étudiant à modifier
if (isset($_GET['id'])) {
    try {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$student) {
            $error = "Étudiant non trouvé";
        }
    } catch (Exception $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}

// Traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $matricule = $_POST['matricule'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $group_id = $_POST['group_id'] ?? '';

    // Validation
    $errors = [];
    
    if (empty($matricule)) $errors[] = "Le matricule est requis";
    if (empty($fullname)) $errors[] = "Le nom complet est requis";
    if (empty($group_id)) $errors[] = "Le groupe est requis";

    if (empty($errors)) {
        try {
            $conn = getDBConnection();
            
            // Vérifier si le matricule existe déjà (pour un autre étudiant)
            $checkStmt = $conn->prepare("SELECT id FROM students WHERE matricule = ? AND id != ?");
            $checkStmt->execute([$matricule, $id]);
            
            if ($checkStmt->fetch()) {
                $errors[] = "Un autre étudiant a déjà ce matricule";
            } else {
                // Mettre à jour l'étudiant
                $updateStmt = $conn->prepare("UPDATE students SET matricule = ?, fullname = ?, group_id = ? WHERE id = ?");
                $updateStmt->execute([$matricule, $fullname, $group_id, $id]);
                
                header("Location: list_students.php?message=updated");
                exit;
            }
        } catch (Exception $e) {
            $errors[] = "Erreur base de données: " . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'Étudiant</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 40px; 
            background: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
            color: #2c3e50;
        }
        input { 
            padding: 12px; 
            width: 100%; 
            border: 2px solid #e9ecef; 
            border-radius: 6px; 
            font-size: 1em;
            transition: border-color 0.3s;
        }
        input:focus {
            border-color: #3498db;
            outline: none;
        }
        .btn { 
            padding: 12px 25px; 
            text-decoration: none; 
            border-radius: 6px; 
            color: white;
            border: none;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-block;
        }
        .btn-primary { 
            background: #3498db; 
        }
        .btn-primary:hover { 
            background: #2980b9; 
        }
        .btn-secondary { 
            background: #95a5a6; 
            margin-left: 10px;
        }
        .btn-secondary:hover { 
            background: #7f8c8d; 
        }
        .error { 
            color: #e74c3c; 
            padding: 15px;
            background: #ffeaea;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .success { 
            color: #27ae60; 
            padding: 15px;
            background: #e8f5e8;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Modifier l'Étudiant</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['message']) && $_GET['message'] === 'updated'): ?>
            <div class="success">✅ Étudiant modifié avec succès !</div>
        <?php endif; ?>
        
        <?php if ($student): ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
            
            <div class="form-group">
                <label for="matricule">Matricule:</label>
                <input type="text" id="matricule" name="matricule" 
                       value="<?php echo htmlspecialchars($student['matricule']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="fullname">Nom Complet:</label>
                <input type="text" id="fullname" name="fullname" 
                       value="<?php echo htmlspecialchars($student['fullname']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="group_id">Groupe:</label>
                <input type="text" id="group_id" name="group_id" 
                       value="<?php echo htmlspecialchars($student['group_id']); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">💾 Enregistrer les modifications</button>
            <a href="list_students.php" class="btn btn-secondary">← Retour à la liste</a>
        </form>
        <?php else: ?>
            <div class="error">Aucun étudiant sélectionné</div>
            <a href="list_students.php" class="btn btn-secondary">← Retour à la liste</a>
        <?php endif; ?>
    </div>
</body>
</html>