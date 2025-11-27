<?php
// list_students.php
require_once 'db_connect.php';

try {
    $conn = getDBConnection();
    $stmt = $conn->query("SELECT * FROM students ORDER BY created_at DESC");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $students = [];
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 40px; 
            background: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .student-card { 
            background: white; 
            padding: 20px; 
            margin: 15px 0; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s;
        }
        .student-card:hover {
            transform: translateY(-2px);
        }
        .student-info { flex-grow: 1; }
        .student-name {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .student-details {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        .student-actions { display: flex; gap: 10px; }
        .btn { 
            padding: 8px 15px; 
            text-decoration: none; 
            border-radius: 6px; 
            color: white;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .btn-edit { background: #3498db; }
        .btn-edit:hover { background: #2980b9; }
        .btn-delete { background: #e74c3c; }
        .btn-delete:hover { background: #c0392b; }
        .btn-add { 
            background: #27ae60; 
            padding: 12px 20px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .btn-add:hover { background: #219652; }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .stats {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 Liste des Étudiants</h1>
        </div>

        <div class="stats">
            <div class="stat-number"><?php echo count($students); ?></div>
            <div>Étudiants inscrits</div>
        </div>
        
        <a href="test_form_mysql.html" class="btn btn-add">➕ Ajouter un Étudiant</a>
        
        <?php if (isset($error)): ?>
            <div style="color: red; padding: 15px; background: #ffeaea; border-radius: 8px; margin-bottom: 20px;">
                ❌ Erreur: <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($students)): ?>
            <div style="text-align: center; padding: 40px; background: white; border-radius: 10px;">
                <p style="font-size: 1.2em; color: #7f8c8d;">Aucun étudiant trouvé.</p>
                <a href="test_form_mysql.html" class="btn btn-add">Ajouter le premier étudiant</a>
            </div>
        <?php else: ?>
            <?php foreach ($students as $student): ?>
            <div class="student-card">
                <div class="student-info">
                    <div class="student-name"><?php echo htmlspecialchars($student['fullname']); ?></div>
                    <div class="student-details">
                        📋 Matricule: <?php echo htmlspecialchars($student['matricule']); ?>
                        | 🎯 Groupe: <?php echo htmlspecialchars($student['group_id']); ?>
                        | 📅 Inscrit le: <?php echo date('d/m/Y', strtotime($student['created_at'])); ?>
                    </div>
                </div>
                <div class="student-actions">
                    <a href="update_student.php?id=<?php echo $student['id']; ?>" class="btn btn-edit">✏️ Modifier</a>
                    <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="btn btn-delete" 
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">🗑️ Supprimer</a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 30px; padding: 20px;">
            <a href="take_attendance.php" style="color: #3498db; text-decoration: none; margin-right: 20px;">
                📋 Prendre la présence
            </a>
            <a href="index.php" style="color: #27ae60; text-decoration: none;">
                🏠 Retour au projet principal
            </a>
        </div>
    </div>
</body>
</html>