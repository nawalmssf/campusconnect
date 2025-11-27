<?php
// manage_sessions.php
require_once 'db_connect.php';

try {
    $conn = getDBConnection();
    
    // Récupérer toutes les sessions
    $sessionsStmt = $conn->query("SELECT * FROM attendance_sessions ORDER BY created_at DESC");
    $sessions = $sessionsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les groupes disponibles
    $groupsStmt = $conn->query("SELECT DISTINCT group_id FROM students ORDER BY group_id");
    $groups = $groupsStmt->fetchAll(PDO::FETCH_COLUMN);
    
} catch (Exception $e) {
    $sessions = [];
    $groups = [];
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Sessions</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 40px; 
            background: #f8f9fa;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .card {
            background: white;
            padding: 25px;
            margin-bottom: 25px;
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
        input, select { 
            padding: 12px; 
            width: 100%; 
            border: 2px solid #e9ecef; 
            border-radius: 6px; 
            font-size: 1em;
        }
        .btn { 
            padding: 12px 25px; 
            text-decoration: none; 
            border-radius: 6px; 
            color: white;
            border: none;
            font-size: 1em;
            cursor: pointer;
            display: inline-block;
        }
        .btn-primary { background: #3498db; }
        .btn-success { background: #27ae60; }
        .btn-warning { background: #f39c12; }
        .btn-danger { background: #e74c3c; }
        .session-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            margin: 15px 0;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        .session-open { border-left-color: #27ae60; }
        .session-closed { border-left-color: #95a5a6; }
        .session-info { flex-grow: 1; }
        .session-title {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 5px;
        }
        .session-details {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .status-open { background: #d4edda; color: #155724; }
        .status-closed { background: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📅 Gestion des Sessions de Présence</h1>
        
        <?php if (isset($error)): ?>
            <div class="card" style="background: #ffeaea; color: #e74c3c;">
                ❌ Erreur: <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulaire de création de session -->
        <div class="card">
            <h2>➕ Créer une Nouvelle Session</h2>
            <form id="createSessionForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="course_id">Cours:</label>
                        <input type="text" id="course_id" name="course_id" placeholder="Ex: MATH101" required>
                    </div>
                    <div class="form-group">
                        <label for="group_id">Groupe:</label>
                        <select id="group_id" name="group_id" required>
                            <option value="">Sélectionner un groupe</option>
                            <?php foreach ($groups as $group): ?>
                                <option value="<?php echo $group; ?>"><?php echo $group; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="session_date">Date de la session:</label>
                        <input type="date" id="session_date" name="session_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="opened_by">Professeur:</label>
                        <input type="text" id="opened_by" name="opened_by" placeholder="Ex: Prof. Dupont" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">🎯 Créer la Session</button>
            </form>
            <div id="createResult" style="margin-top: 15px;"></div>
        </div>
        
        <!-- Liste des sessions -->
        <div class="card">
            <h2>📋 Sessions Existantes</h2>
            <?php if (empty($sessions)): ?>
                <p style="text-align: center; color: #7f8c8d; padding: 20px;">
                    Aucune session créée pour le moment.
                </p>
            <?php else: ?>
                <?php foreach ($sessions as $session): ?>
                <div class="session-card <?php echo $session['status'] === 'open' ? 'session-open' : 'session-closed'; ?>">
                    <div class="session-info">
                        <div class="session-title">
                            <?php echo htmlspecialchars($session['course_id']); ?> - 
                            Groupe <?php echo htmlspecialchars($session['group_id']); ?>
                        </div>
                        <div class="session-details">
                            📅 <?php echo date('d/m/Y', strtotime($session['session_date'])); ?> | 
                            👨‍🏫 <?php echo htmlspecialchars($session['opened_by']); ?> |
                            🕒 Créée le: <?php echo date('d/m/Y H:i', strtotime($session['created_at'])); ?>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span class="status-badge <?php echo $session['status'] === 'open' ? 'status-open' : 'status-closed'; ?>">
                            <?php echo $session['status'] === 'open' ? '🟢 OUVERTE' : '🔴 FERMÉE'; ?>
                        </span>
                        <?php if ($session['status'] === 'open'): ?>
                            <button class="btn btn-warning close-session-btn" 
                                    data-session-id="<?php echo $session['id']; ?>">
                                🔒 Fermer
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Création de session
        document.getElementById('createSessionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('createResult');
            
            fetch('create_session.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `<div style="color: green; padding: 10px; background: #e8f5e8; border-radius: 5px;">
                        ✅ ${data.message} - ID: ${data.session_id}
                    </div>`;
                    setTimeout(() => location.reload(), 1500);
                } else {
                    let errorsHtml = data.errors ? data.errors.map(error => `<div>${error}</div>`).join('') : '';
                    resultDiv.innerHTML = `<div style="color: red; padding: 10px; background: #ffeaea; border-radius: 5px;">
                        ❌ ${data.message}${errorsHtml}
                    </div>`;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `<div style="color: red;">Erreur: ${error}</div>`;
            });
        });
        
        // Fermeture de session
        document.querySelectorAll('.close-session-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const sessionId = this.getAttribute('data-session-id');
                const sessionCard = this.closest('.session-card');
                
                if (confirm('Êtes-vous sûr de vouloir fermer cette session ?')) {
                    const formData = new FormData();
                    formData.append('session_id', sessionId);
                    
                    fetch('close_session.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            sessionCard.innerHTML = `<div style="color: green; text-align: center; padding: 20px;">
                                ✅ Session fermée avec succès
                            </div>`;
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            alert('Erreur: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Erreur: ' + error);
                    });
                }
            });
        });
    </script>
</body>
</html>