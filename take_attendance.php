<?php
// take_attendance.php

// 1. Charger les étudiants depuis students.json
$studentsFile = 'students.json';
$students = [];

if (file_exists($studentsFile)) {
    $jsonData = file_get_contents($studentsFile);
    $students = json_decode($jsonData, true) ?? [];
} else {
    die("Aucun étudiant trouvé. Veuillez d'abord ajouter des étudiants.");
}

// 2. Vérifier si la présence a déjà été prise aujourd'hui
$today = date('Y-m-d');
$attendanceFile = "attendance_{$today}.json";

if (file_exists($attendanceFile)) {
    $message = "❌ La présence pour aujourd'hui ({$today}) a déjà été prise.";
    $attendanceTaken = true;
} else {
    $message = "📋 Prenez la présence pour aujourd'hui : {$today}";
    $attendanceTaken = false;
}

// 3. Traitement du formulaire de présence
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$attendanceTaken) {
    $attendanceData = [];
    
    foreach ($students as $student) {
        $studentId = $student['student_id'];
        $status = isset($_POST['attendance'][$studentId]) ? 'present' : 'absent';
        
        $attendanceData[] = [
            'student_id' => $studentId,
            'name' => $student['name'],
            'group' => $student['group'],
            'status' => $status,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    // Sauvegarder dans le fichier JSON
    if (file_put_contents($attendanceFile, json_encode($attendanceData, JSON_PRETTY_PRINT))) {
        $message = "✅ Présence enregistrée avec succès pour aujourd'hui ({$today}) !";
        $attendanceTaken = true;
        
        // Redirection pour éviter le re-soumission
        header("Refresh: 2; URL=take_attendance.php");
    } else {
        $message = "❌ Erreur lors de l'enregistrement de la présence.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prise de Présence</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f8f9fa;
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        .attendance-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .student-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.3s;
        }
        .student-item:hover {
            background-color: #f8f9fa;
        }
        .student-info {
            flex-grow: 1;
        }
        .student-name {
            font-weight: bold;
            font-size: 1.1em;
            color: #2c3e50;
        }
        .student-details {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        .attendance-checkbox {
            transform: scale(1.3);
            margin-left: 20px;
        }
        .submit-btn {
            background: #27ae60;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .submit-btn:hover:not(:disabled) {
            background: #219652;
        }
        .submit-btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            padding: 15px;
            background: #e8f5e8;
            border-radius: 8px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 1.5em;
            font-weight: bold;
            color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎯 Prise de Présence</h1>
        <div class="message <?php echo $attendanceTaken ? 'warning' : 'info'; ?>">
            <?php echo $message; ?>
        </div>
    </div>

    <?php if (!$attendanceTaken): ?>
    <form method="POST" class="attendance-form">
        <h2>Liste des Étudiants</h2>
        
        <?php foreach ($students as $student): ?>
        <div class="student-item">
            <div class="student-info">
                <div class="student-name"><?php echo htmlspecialchars($student['name']); ?></div>
                <div class="student-details">
                    ID: <?php echo htmlspecialchars($student['student_id']); ?> 
                    | Groupe: <?php echo htmlspecialchars($student['group']); ?>
                </div>
            </div>
            <div>
                <label>
                    <input type="checkbox" 
                           class="attendance-checkbox" 
                           name="attendance[<?php echo $student['student_id']; ?>]" 
                           value="present" 
                           checked>
                    Présent
                </label>
            </div>
        </div>
        <?php endforeach; ?>

        <button type="submit" class="submit-btn">
            ✅ Enregistrer la Présence
        </button>
    </form>
    <?php else: ?>
    <div class="attendance-form">
        <h3>📊 Résumé du jour</h3>
        <?php
        if (file_exists($attendanceFile)) {
            $todayAttendance = json_decode(file_get_contents($attendanceFile), true);
            $presentCount = 0;
            $absentCount = 0;
            
            foreach ($todayAttendance as $record) {
                if ($record['status'] === 'present') {
                    $presentCount++;
                } else {
                    $absentCount++;
                }
            }
            
            echo "<div class='stats'>";
            echo "<div class='stat-item'><div class='stat-value'>$presentCount</div><div>Présents</div></div>";
            echo "<div class='stat-item'><div class='stat-value'>$absentCount</div><div>Absents</div></div>";
            echo "<div class='stat-item'><div class='stat-value'>" . count($students) . "</div><div>Total</div></div>";
            echo "</div>";
        }
        ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="test_form.html" style="color: #3498db; text-decoration: none;">
                ← Retour à l'ajout d'étudiants
            </a>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>