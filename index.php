<?php
// Charger la connexion DB
require_once __DIR__ . '/db_connect.php';

try {
    $conn = getDBConnection();
    
    // Récupérer les étudiants depuis la base
    $stmt = $conn->query("
        SELECT s.*, 
               a.session_1, a.session_2, a.session_3, a.session_4, a.session_5, a.session_6,
               p.session_1 as p1, p.session_2 as p2, p.session_3 as p3, p.session_4 as p4, p.session_5 as p5, p.session_6 as p6
        FROM students s
        LEFT JOIN attendance a ON s.id = a.student_id
        LEFT JOIN participation p ON s.id = p.student_id
    ");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $students = [];
    $error = $e->getMessage();
}

// Préparer les données pour JavaScript
$js_students = [];
foreach ($students as $student) {
    $name_parts = explode(' ', $student['fullname'], 2);
    $last_name = $name_parts[0] ?? '';
    $first_name = $name_parts[1] ?? '';
    
    $js_students[] = [
        'id' => $student['matricule'] ?? '',
        'lastName' => $last_name,
        'firstName' => $first_name,
        'group' => $student['group_id'] ?? 'N/A',
        'email' => ($student['matricule'] ?? '') . '@campus.edu',
        'presence' => [
            !empty($student['session_1']),
            !empty($student['session_2']),
            !empty($student['session_3']),
            !empty($student['session_4']),
            !empty($student['session_5']),
            !empty($student['session_6'])
        ],
        'participation' => [
            !empty($student['p1']),
            !empty($student['p2']),
            !empty($student['p3']),
            !empty($student['p4']),
            !empty($student['p5']),
            !empty($student['p6'])
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusConnect - Gestion Scolaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <!-- Écran de bienvenue -->
    <div class="welcome-screen" id="welcomeScreen">
        <div class="logo">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h1 class="welcome-title">CampusConnect</h1>
        <p class="welcome-subtitle">Votre portail de gestion académique</p>
        <button class="enter-btn" id="enterBtn">
            <i class="fas fa-arrow-right"></i> Accéder au système
        </button>
    </div>

    <!-- Application principale -->
    <div class="main-app" id="mainApp">
        <nav class="main-nav">
            <div class="nav-brand">
                <i class="fas fa-graduation-cap"></i>
                <span>CampusConnect</span>
            </div>
            <div class="nav-menu">
                <a href="#" class="nav-btn active" onclick="showPage('tableau')"><i class="fas fa-table"></i> Tableau</a>
                <a href="#" class="nav-btn" onclick="showPage('formulaire')"><i class="fas fa-user-plus"></i> Ajouter</a>
                <a href="#" class="nav-btn" onclick="showPage('gestion')"><i class="fas fa-users"></i> Gestion</a>
                <a href="#" class="nav-btn" onclick="showPage('sessions')"><i class="fas fa-calendar"></i> Sessions</a>
                <a href="#" class="nav-btn" onclick="showPage('rapport')"><i class="fas fa-chart-bar"></i> Rapports</a>
            </div>
        </nav>

        <!-- Page Tableau -->
        <section id="tableau" class="page active">
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-value" id="totalStudents">0</div>
                    <div class="stat-label">Étudiants inscrits</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                    <div class="stat-value" id="avgAttendance">0%</div>
                    <div class="stat-label">Présence moyenne</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="stat-value" id="avgParticipation">0%</div>
                    <div class="stat-label">Participation moyenne</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-value" id="studentsAtRisk">0</div>
                    <div class="stat-label">Étudiants à risque</div>
                </div>
            </div>

            <div class="table-container">
                <div class="table-header">
                    <h2 class="table-title"><i class="fas fa-clipboard-list"></i> Tableau de Présence Détaillé</h2>
                    <div class="table-actions">
                        <button class="nav-btn highlight-btn" id="highlightBtn"><i class="fas fa-star"></i> Highlight Excellent Students</button>
                        <button class="nav-btn reset-btn" id="resetBtn"><i class="fas fa-sync-alt"></i> Reset Colors</button>
                        <button class="nav-btn report-btn" onclick="showPage('rapport')"><i class="fas fa-chart-bar"></i> Voir les Rapports</button>
                    </div>
                </div>

                <table class="attendance-table" id="attendanceTable">
                    <thead>
                        <tr>
                            <th rowspan="2">Nom</th>
                            <th rowspan="2">Prénom</th>
                            <th colspan="6">Présence aux Sessions</th>
                            <th colspan="6">Participation</th>
                            <th rowspan="2">Absences</th>
                            <th rowspan="2">Participations</th>
                            <th rowspan="2">Message</th>
                            <th rowspan="2">Actions</th>
                        </tr>
                        <tr>
                            <th>S1</th><th>S2</th><th>S3</th><th>S4</th><th>S5</th><th>S6</th>
                            <th>P1</th><th>P2</th><th>P3</th><th>P4</th><th>P5</th><th>P6</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </section>
        <section id="formulaire" class="page">
            <div class="form-container">
                <h2 class="form-title">
                    <i class="fas fa-user-plus"></i> Ajouter un Nouvel Étudiant
                </h2>

                <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
                    <div class="success-message" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                        ✅ Étudiant ajouté avec succès !
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                        <?php
                        $error = $_GET['error'];
                        if ($error === 'matricule_exists') {
                            echo '❌ Un étudiant avec ce matricule existe déjà.';
                        } elseif ($error === 'database_error') {
                            echo '❌ Erreur lors de l\'ajout. Veuillez réessayer.';
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <form id="studentForm" method="POST" action="add_student_handler.php">
                    <div class="form-group">
                        <label for="matricule">Matricule:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-fingerprint input-icon"></i>
                            <input type="text" id="matricule" name="matricule" placeholder="Ex: 2024001" required>
                            <div class="error-message" id="matriculeError"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fullname">Nom Complet:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="fullname" name="fullname" placeholder="Ex: Sara Ahmed" required>
                            <div class="error-message" id="fullnameError"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="group_id">Groupe:</label>
                        <div class="input-with-icon">
                            <i class="fas fa-users input-icon"></i>
                            <input type="text" id="group_id" name="group_id" placeholder="Ex: Groupe A" required>
                            <div class="error-message" id="groupError"></div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-user-plus"></i> Ajouter l'étudiant
                    </button>
                </form>
            </div>
        </section>
        <section id="gestion" class="page">
            <div class="table-container">
                <h2 class="table-title">
                    <i class="fas fa-users"></i> Gestion des Étudiants
                </h2>

                <?php
                try {
                    $conn = getDBConnection();
                    $stmt = $conn->query("SELECT * FROM students ORDER BY created_at DESC");
                    $allStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $allStudents = [];
                    $error = $e->getMessage();
                }
                ?>

                <?php if (isset($error)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                        ❌ Erreur: <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($allStudents)): ?>
                    <div style="text-align: center; padding: 40px; background: white; border-radius: 10px;">
                        <p style="font-size: 1.2em; color: #7f8c8d;">Aucun étudiant trouvé.</p>
                    </div>
                <?php else: ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                        <?php foreach ($allStudents as $student): ?>
                        <div class="student-card">
                            <h4><?php echo htmlspecialchars($student['fullname']); ?></h4>
                            <p>📋 <?php echo htmlspecialchars($student['matricule']); ?></p>
                            <p>🎯 Groupe: <?php echo htmlspecialchars($student['group_id']); ?></p>
                            <p>📅 Inscrit le: <?php echo date('d/m/Y', strtotime($student['created_at'])); ?></p>

                            <a class="btn-action" href="update_student.php?id=<?php echo $student['id']; ?>">✏️ Modifier</a>
                            <a class="btn-action delete" href="delete_student.php?id=<?php echo $student['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">🗑️ Supprimer</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <section id="sessions" class="page">
            <div class="table-container">
                <h2 class="table-title">
                    <i class="fas fa-calendar"></i> Gestion des Sessions
                </h2>

                <div style="text-align: center; padding: 40px;">
                    <p style="font-size: 1.1em; margin-bottom: 20px;">Gérez les sessions de présence, créez et suivez les présences.</p>
                    <a href="manage_sessions.php" class="nav-btn">
                        <i class="fas fa-cog"></i> Ouvrir le gestionnaire de sessions
                    </a>
                </div>
            </div>
        </section>
        <section id="rapport" class="page">
            <div class="report-container">
                <h2 class="report-title"><i class="fas fa-chart-bar"></i> Rapports et Statistiques</h2>
                
                <div class="report-stats">
                    <div class="report-stat-card">
                        <div class="report-stat-icon"><i class="fas fa-users"></i></div>
                        <div class="report-stat-value" id="reportTotalStudents">0</div>
                        <div class="report-stat-label">Total Étudiants</div>
                    </div>
                    <div class="report-stat-card">
                        <div class="report-stat-icon"><i class="fas fa-user-check"></i></div>
                        <div class="report-stat-value" id="reportPresentStudents">0</div>
                        <div class="report-stat-label">Étudiants Présents</div>
                    </div>
                    <div class="report-stat-card">
                        <div class="report-stat-icon"><i class="fas fa-hand-paper"></i></div>
                        <div class="report-stat-value" id="reportParticipatingStudents">0</div>
                        <div class="report-stat-label">Étudiants Participants</div>
                    </div>
                    <div class="report-stat-card">
                        <div class="report-stat-icon"><i class="fas fa-percentage"></i></div>
                        <div class="report-stat-value" id="reportAttendanceRate">0%</div>
                        <div class="report-stat-label">Taux de Présence</div>
                    </div>
                </div>

                <div class="charts-container">
                    <div class="chart-card">
                        <h3><i class="fas fa-chart-pie"></i> Répartition Présence/Absence</h3>
                        <div class="chart-wrapper">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-card">
                        <h3><i class="fas fa-chart-bar"></i> Participation par Session</h3>
                        <div class="chart-wrapper">
                            <canvas id="participationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        const studentsData = <?php echo json_encode($js_students); ?>;
    </script>
    <script src="script.js"></script>
</body>
</html>
