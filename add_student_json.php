<?php
// add_student_json.php
// Exercice 1: Version JSON pour ajouter un étudiant

$studentsFile = 'students.json';

// 1. Charger les étudiants existants depuis students.json (s'il existe)
$students = [];
if (file_exists($studentsFile)) {
    $jsonData = file_get_contents($studentsFile);
    $students = json_decode($jsonData, true) ?? [];
}

// 2. Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $group = trim($_POST['group'] ?? '');
    
    // 3. Valider les entrées
    $errors = [];
    
    if (empty($student_id)) {
        $errors[] = "L'ID étudiant est requis";
    } elseif (!preg_match('/^\d+$/', $student_id)) {
        $errors[] = "L'ID étudiant doit contenir uniquement des chiffres";
    } else {
        // Vérifier si l'ID existe déjà
        foreach ($students as $student) {
            if ($student['student_id'] === $student_id) {
                $errors[] = "Un étudiant avec cet ID existe déjà";
                break;
            }
        }
    }
    
    if (empty($name)) {
        $errors[] = "Le nom est requis";
    } elseif (!preg_match('/^[A-Za-zÀ-ÿ\s\-\']+$/', $name)) {
        $errors[] = "Le nom doit contenir uniquement des lettres";
    }
    
    if (empty($group)) {
        $errors[] = "Le groupe est requis";
    }
    
    // 4. Si pas d'erreurs, ajouter le nouvel étudiant au tableau
    if (empty($errors)) {
        $newStudent = [
            'student_id' => $student_id,
            'name' => $name,
            'group' => $group,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $students[] = $newStudent;
        
        // 5. Sauvegarder le tableau mis à jour dans students.json
        if (file_put_contents($studentsFile, json_encode($students, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
            $message = "✅ Étudiant ajouté avec succès !";
            $success = true;
        } else {
            $errors[] = "Erreur lors de l'enregistrement dans le fichier JSON";
            $success = false;
        }
    } else {
        $success = false;
    }
} else {
    $success = null;
    $errors = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Étudiant (Version JSON)</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background: #f8f9fa;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
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
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 1em;
            box-sizing: border-box;
        }
        input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background: #219652;
        }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .error-list li {
            padding: 5px 0;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>➕ Ajouter un Nouvel Étudiant</h1>
        
        <?php if (isset($success) && $success): ?>
            <div class="message success">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="message error">
                <strong>Erreurs :</strong>
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="student_id">ID Étudiant:</label>
                <input type="text" id="student_id" name="student_id" 
                       value="<?php echo htmlspecialchars($student_id ?? ''); ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="name">Nom Complet:</label>
                <input type="text" id="name" name="name" 
                       value="<?php echo htmlspecialchars($name ?? ''); ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="group">Groupe:</label>
                <input type="text" id="group" name="group" 
                       value="<?php echo htmlspecialchars($group ?? ''); ?>" 
                       required>
            </div>
            
            <button type="submit">Ajouter l'étudiant</button>
        </form>
        
        <a href="test_form.html" class="back-link">← Retour à la liste</a>
        <a href="take_attendance.php" class="back-link">📋 Prendre la présence</a>
    </div>
</body>
</html>

