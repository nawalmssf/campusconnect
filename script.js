// ===== DONNÉES =====
let students = (typeof studentsData !== 'undefined' && Array.isArray(studentsData) && studentsData.length > 0)
    ? studentsData.map(s => ({
        id: s.id || '',
        lastName: s.lastName || '',
        firstName: s.firstName || '',
        group: s.group || 'N/A',
        email: s.email || '',
        presence: Array.isArray(s.presence) ? s.presence.map(p => !!p) : [false, false, false, false, false, false],
        participation: Array.isArray(s.participation) ? s.participation.map(p => !!p) : [false, false, false, false, false, false]
    }))
    : [
        {
            id: "2024001",
            lastName: "Ahmed",
            firstName: "Sara",
            group: "TD-A",
            presence: [true, true, false, false, false, false],
            participation: [true, false, false, false, false, false]
        },
        {
            id: "2024002",
            lastName: "Yasine", 
            firstName: "Ali",
            group: "TD-B",
            presence: [true, false, true, true, true, true],
            participation: [false, true, true, true, true, false]
        },
        {
            id: "2024003",
            lastName: "Houcine",
            firstName: "Rania", 
            group: "TD-A",
            presence: [true, true, true, false, false, false],
            participation: [true, false, true, false, false, false]
        }
    ];

// Variables pour les graphiques et le tri
let attendanceChart, participationChart;
let currentSortMode = 'none';

// ===== EXERCICE 7: RECHERCHE ET TRI =====

// Fonction de recherche par nom (Exercice 7 - Point 1)
function initSearchByName() {
    const searchInput = document.getElementById('searchByName');
    if (!searchInput) return;
    
    searchInput.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        
        // Utiliser jQuery .filter() comme demandé
        $('#tableBody tr').filter(function() {
            const lastName = $(this).find('td:eq(0)').text().toLowerCase();
            const firstName = $(this).find('td:eq(1)').text().toLowerCase();
            const fullName = lastName + ' ' + firstName;
            
            // Afficher/masquer selon la recherche
            const matches = fullName.indexOf(searchValue) > -1;
            $(this).toggle(matches);
        });
        
        // Mettre à jour le compteur de résultats
        const visibleRows = $('#tableBody tr:visible').length;
        const totalRows = $('#tableBody tr').length;
        updateSearchResultMessage(visibleRows, totalRows, searchValue);
    });
}

// Message de résultats de recherche
function updateSearchResultMessage(visible, total, searchTerm) {
    let message = document.getElementById('searchResultMessage');
    if (!message) {
        message = document.createElement('div');
        message.id = 'searchResultMessage';
        const searchContainer = document.querySelector('.search-container');
        if (searchContainer) {
            searchContainer.appendChild(message);
        }
    }
    
    if (searchTerm === '') {
        message.textContent = `Affichage de tous les étudiants (${total})`;
    } else {
        message.textContent = `${visible} résultat(s) sur ${total} pour "${searchTerm}"`;
    }
}

// Fonction de tri par absences (Exercice 7 - Point 2)
function sortByAbsencesAscending() {
    console.log('🔼 TRI PAR ABSENCES (CROISSANT)');
    currentSortMode = 'absences-asc';
    
    const tbody = document.getElementById('tableBody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Trier les lignes
    rows.sort((a, b) => {
        const absencesA = getAbsencesCount(a);
        const absencesB = getAbsencesCount(b);
        return absencesA - absencesB; // Ordre croissant
    });
    
    // Réorganiser le tableau
    rows.forEach(row => tbody.appendChild(row));
    
    // Afficher le message de tri
    updateSortMessage('absences (croissant)');
    
    // Animation visuelle
    animateSortedRows();
}

// Fonction de tri par participation (Exercice 7 - Point 2)
function sortByParticipationDescending() {
    console.log('🔽 TRI PAR PARTICIPATION (DÉCROISSANT)');
    currentSortMode = 'participation-desc';
    
    const tbody = document.getElementById('tableBody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Trier les lignes
    rows.sort((a, b) => {
        const participationA = getParticipationCount(a);
        const participationB = getParticipationCount(b);
        return participationB - participationA; // Ordre décroissant
    });
    
    // Réorganiser le tableau
    rows.forEach(row => tbody.appendChild(row));
    
    // Afficher le message de tri
    updateSortMessage('participation (décroissant)');
    
    // Animation visuelle
    animateSortedRows();
}

// Obtenir le nombre d'absences d'une ligne
function getAbsencesCount(row) {
    const absencesCell = row.querySelector('.absences-count');
    if (!absencesCell) return 0;
    const text = absencesCell.textContent.trim();
    return parseInt(text.split(' ')[0]) || 0;
}

// Obtenir le nombre de participations d'une ligne
function getParticipationCount(row) {
    const participationCell = row.querySelector('.participations-count');
    if (!participationCell) return 0;
    const text = participationCell.textContent.trim();
    return parseInt(text.split(' ')[0]) || 0;
}

// Message de tri actuel (Exercice 7 - Point 4)
function updateSortMessage(mode) {
    let message = document.getElementById('sortMessage');
    if (!message) {
        message = document.createElement('div');
        message.id = 'sortMessage';
        message.className = 'sort-message';
        const tableContainer = document.querySelector('.table-container');
        if (tableContainer) {
            tableContainer.insertBefore(message, tableContainer.firstChild);
        }
    }
    
    message.innerHTML = `
        <i class="fas fa-info-circle"></i> 
        <strong>Currently sorted by:</strong> ${mode}
    `;
    message.style.display = 'block';
    
    // Animation d'apparition
    message.style.animation = 'fadeIn 0.5s ease';
}

// Animation des lignes triées
function animateSortedRows() {
    $('#tableBody tr').each(function(index) {
        $(this).css({
            animation: `fadeInUp 0.5s ease ${index * 0.05}s`,
            animationFillMode: 'backwards'
        });
    });
    
    setTimeout(() => {
        $('#tableBody tr').css('animation', '');
    }, 2000);
}

// Réinitialiser le tri
function resetSort() {
    console.log('🔄 RÉINITIALISATION DU TRI');
    currentSortMode = 'none';
    
    // Réafficher toutes les lignes
    $('#tableBody tr').show();
    
    // Re-rendre le tableau dans l'ordre original
    renderTable();
    
    // Masquer le message de tri
    const sortMessage = document.getElementById('sortMessage');
    if (sortMessage) {
        sortMessage.style.display = 'none';
    }
    
    // Réinitialiser la recherche
    const searchInput = document.getElementById('searchByName');
    if (searchInput) {
        searchInput.value = '';
    }
    
    const searchMessage = document.getElementById('searchResultMessage');
    if (searchMessage) {
        searchMessage.textContent = `Affichage de tous les étudiants (${students.length})`;
    }
    
    showNotification('🔄 Tri et recherche réinitialisés');
}

// ===== EXERCICE 6: FONCTIONS JQUERY =====
$(document).ready(function() {
    // Bouton "Highlight Excellent Students"
    $('#highlightBtn').click(function() {
        console.log('🌟 HIGHLIGHT EXCELLENT STUDENTS');
        
        // Réinitialiser d'abord les couleurs
        resetColors();
        
        // Trouver les étudiants avec moins de 3 absences
        let excellentStudents = 0;
        
        $('#tableBody tr').each(function() {
            const absencesCell = $(this).find('td').eq(12); // Colonne absences
            const absencesText = absencesCell.text().trim();
            const absences = parseInt(absencesText.split(' ')[0]);
            
            if (absences < 3) {
                excellentStudents++;
                
                // Animer la ligne
                $(this)
                    .addClass('status-excellent highlight-animation')
                    .find('td:last').prev() // Cellule message
                    .removeClass('message-good message-warning message-danger')
                    .addClass('message-excellent')
                    .text('🌟 Excellent - Bonnes présences');
                
                // Animation jQuery
                $(this)
                    .hide()
                    .fadeIn(1000)
                    .animate({
                        backgroundColor: 'rgba(155, 89, 182, 0.2)'
                    }, 1000);
            }
        });
        
        // Message de confirmation
        if (excellentStudents > 0) {
            showNotification(`🎉 ${excellentStudents} étudiant(s) excellent(s) mis en avant !`);
        } else {
            showNotification('ℹ️ Aucun étudiant avec moins de 3 absences trouvé.');
        }
    });
    
    // Bouton "Reset Colors"
    $('#resetBtn').click(function() {
        console.log('🔄 RESET COLORS');
        resetColors();
        showNotification('🎨 Couleurs réinitialisées !');
    });
});

function resetColors() {
    $('#tableBody tr').each(function() {
        const absencesCell = $(this).find('td').eq(12);
        const absencesText = absencesCell.text().trim();
        const absences = parseInt(absencesText.split(' ')[0]);
        const participationsCell = $(this).find('td').eq(13);
        const participationsText = participationsCell.text().trim();
        const participations = parseInt(participationsText.split(' ')[0]);
        
        // Réinitialiser les classes
        $(this)
            .removeClass('status-excellent highlight-animation excellent-student')
            .stop(true, true)
            .show();
        
        // Remettre la couleur originale selon les absences
        if (absences < 3) {
            $(this).addClass('status-good');
        } else if (absences <= 4) {
            $(this).addClass('status-warning');
        } else {
            $(this).addClass('status-danger');
        }
        
        // Remettre le message original
        const messageCell = $(this).find('td:last').prev();
        messageCell
            .removeClass('message-excellent')
            .text(generateMessage(absences, participations));
        
        // Réinitialiser l'animation
        $(this).css({
            backgroundColor: '',
            boxShadow: ''
        });
    });
}

function showNotification(message) {
    // Créer une notification temporaire
    const notification = $('<div>')
        .text(message)
        .css({
            position: 'fixed',
            top: '20px',
            right: '20px',
            background: 'var(--success)',
            color: 'white',
            padding: '15px 20px',
            borderRadius: '10px',
            boxShadow: '0 5px 15px rgba(0,0,0,0.2)',
            zIndex: '1000',
            fontWeight: '600',
            animation: 'fadeIn 0.5s ease'
        })
        .appendTo('body');
    
    setTimeout(() => {
        notification.fadeOut(500, function() {
            $(this).remove();
        });
    }, 3000);
}

// ===== GESTION DE L'APPLICATION =====
document.getElementById('enterBtn').addEventListener('click', function() {
    const welcomeScreen = document.getElementById('welcomeScreen');
    const mainApp = document.getElementById('mainApp');
    
    welcomeScreen.classList.add('hidden');
    setTimeout(() => {
        mainApp.classList.add('visible');
        renderTable();
    }, 1000);
});

function showPage(pageId) {
    // Reset active nav buttons
    document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Activate correct nav button
    const navBtn = document.querySelector(`.nav-btn[onclick="showPage('${pageId}')"]`);
    if (navBtn) navBtn.classList.add('active');

    // Hide all pages
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });

    // Show requested page
    const targetPage = document.getElementById(pageId);
    if (targetPage) targetPage.classList.add('active');

    // Extra actions
    if (pageId === 'tableau') {
        renderTable();
    } else if (pageId === 'rapport') {
        generateReport();
    }
}

// ===== FONCTIONS DU TABLEAU =====
function calculateStats() {
    const totalStudents = students.length;
    let totalPresence = 0;
    let totalParticipation = 0;
    let totalSessions = 0;
    let atRiskCount = 0;

    students.forEach(student => {
        const absences = student.presence.filter(p => !p).length;
        if (absences >= 3) atRiskCount++;
        
        totalPresence += student.presence.filter(p => p).length;
        totalParticipation += student.participation.filter(p => p).length;
        totalSessions += student.presence.length;
    });

    const avgAttendance = totalSessions > 0 ? ((totalPresence / totalSessions) * 100).toFixed(1) : 0;
    const avgParticipation = totalSessions > 0 ? ((totalParticipation / totalSessions) * 100).toFixed(1) : 0;

    document.getElementById('totalStudents').textContent = totalStudents;
    document.getElementById('avgAttendance').textContent = avgAttendance + '%';
    document.getElementById('avgParticipation').textContent = avgParticipation + '%';
    document.getElementById('studentsAtRisk').textContent = atRiskCount;
}

function generateMessage(absences, participations) {
    if (absences < 3) {
        if (participations >= 4) return "Bonnes présences - Excellente participation";
        if (participations >= 2) return "Bonnes présences - Participation moyenne";
        return "Bonnes présences - Doit participer plus";
    } else if (absences <= 4) {
        if (participations >= 3) return "Attention - Absences élevées - Bonne participation";
        return "Attention - Absences élevées - Doit participer plus";
    } else {
        if (participations >= 3) return "Exclu - Trop d'absences - Bonne participation";
        return "Exclu - Trop d'absences - Doit participer plus";
    }
}

function getStatusClass(absences) {
    if (absences < 3) return "status-good";
    if (absences <= 4) return "status-warning";
    return "status-danger";
}

function getMessageClass(absences) {
    if (absences < 3) return "message-good";
    if (absences <= 4) return "message-warning";
    return "message-danger";
}

// ===== FONCTIONS DU TABLEAU INTERACTIF =====
function renderTable() {
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';

    console.log('🔄 RENDU DU TABLEAU INTERACTIF - Étudiants:', students.length);
    
    students.forEach((student, index) => {
        const absences = student.presence.filter(p => !p).length;
        const participations = student.participation.filter(p => p).length;
        const statusClass = getStatusClass(absences);
        const messageClass = getMessageClass(absences);
        const message = generateMessage(absences, participations);

        const row = document.createElement('tr');
        row.className = statusClass;
        row.setAttribute('data-student-index', index);

        row.innerHTML = `
            <td><strong>${student.lastName}</strong></td>
            <td>${student.firstName}</td>
            
            ${student.presence.map((present, sessionIndex) => `
                <td>
                    <input type="checkbox" ${present ? 'checked' : ''} 
                           onchange="togglePresence(${index}, ${sessionIndex})"
                           class="presence-checkbox">
                    <span class="${present ? 'present' : 'absent'}">
                        ${present ? '✓' : '✗'}
                    </span>
                </td>
            `).join('')}
            
            ${student.participation.map((participated, sessionIndex) => `
                <td>
                    <input type="checkbox" ${participated ? 'checked' : ''} 
                           onchange="toggleParticipation(${index}, ${sessionIndex})"
                           class="participation-checkbox">
                    <span class="${participated ? 'participation-yes' : 'participation-no'}">
                        ${participated ? '✓' : ''}
                    </span>
                </td>
            `).join('')}
            
            <td><strong class="absences-count">${absences} Abs</strong></td>
            <td><strong class="participations-count">${participations} Par</strong></td>
            <td class="${messageClass} student-message">${message}</td>
            <td>
                <button class="btn-action btn-delete" onclick="deleteStudent(${index})">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </td>
        `;

        tbody.appendChild(row);
    });

    calculateStats();
    
    // Initialiser le message de recherche
    updateSearchResultMessage(students.length, students.length, '');
}

// ===== FONCTIONS POUR COCHER/DÉCOCHER =====
function togglePresence(studentIndex, sessionIndex) {
    students[studentIndex].presence[sessionIndex] = !students[studentIndex].presence[sessionIndex];
    updateStudentRow(studentIndex);
    calculateStats();
}

function toggleParticipation(studentIndex, sessionIndex) {
    students[studentIndex].participation[sessionIndex] = !students[studentIndex].participation[sessionIndex];
    updateStudentRow(studentIndex);
    calculateStats();
}

function updateStudentRow(studentIndex) {
    const student = students[studentIndex];
    const absences = student.presence.filter(p => !p).length;
    const participations = student.participation.filter(p => p).length;
    
    const row = document.querySelector(`tr[data-student-index="${studentIndex}"]`);
    if (!row) return;
    
    // Mettre à jour les cases de présence
    student.presence.forEach((present, sessionIndex) => {
        const cell = row.cells[2 + sessionIndex];
        const checkbox = cell.querySelector('.presence-checkbox');
        const span = cell.querySelector('span');
        
        checkbox.checked = present;
        span.textContent = present ? '✓' : '✗';
        span.className = present ? 'present' : 'absent';
    });
    
    // Mettre à jour les cases de participation
    student.participation.forEach((participated, sessionIndex) => {
        const cell = row.cells[8 + sessionIndex];
        const checkbox = cell.querySelector('.participation-checkbox');
        const span = cell.querySelector('span');
        
        checkbox.checked = participated;
        span.textContent = participated ? '✓' : '';
        span.className = participated ? 'participation-yes' : 'participation-no';
    });
    
    // Mettre à jour les compteurs
    const absencesCell = row.querySelector('.absences-count');
    const participationsCell = row.querySelector('.participations-count');
    const messageCell = row.querySelector('.student-message');
    
    absencesCell.textContent = `${absences} Abs`;
    participationsCell.textContent = `${participations} Par`;
    
    // Mettre à jour le message et les classes
    const statusClass = getStatusClass(absences);
    const messageClass = getMessageClass(absences);
    const message = generateMessage(absences, participations);
    
    row.className = statusClass;
    messageCell.className = `${messageClass} student-message`;
    messageCell.textContent = message;
}

function deleteStudent(index) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')) {
        students.splice(index, 1);
        renderTable();
        if (document.getElementById('rapport').classList.contains('active')) {
            generateReport();
        }
    }
}

// ===== FONCTIONS RAPPORT =====
function generateReport() {
    console.log('📊 GÉNÉRATION DU RAPPORT');

    const totalStudents = students.length;
    const presentStudents = students.filter(student => 
        student.presence.some(present => present)
    ).length;
    const participatingStudents = students.filter(student => 
        student.participation.some(participated => participated)
    ).length;
    const totalSessions = students.length * 6;
    const totalPresences = students.reduce((sum, student) => 
        sum + student.presence.filter(p => p).length, 0
    );
    const attendanceRate = totalSessions > 0 ? 
        Math.round((totalPresences / totalSessions) * 100) : 0;

    document.getElementById('reportTotalStudents').textContent = totalStudents;
    document.getElementById('reportPresentStudents').textContent = presentStudents;
    document.getElementById('reportParticipatingStudents').textContent = participatingStudents;
    document.getElementById('reportAttendanceRate').textContent = attendanceRate + '%';

    createCharts(totalStudents, presentStudents, participatingStudents, attendanceRate);
}

function createCharts(totalStudents, presentStudents, participatingStudents, attendanceRate) {
    const absentStudents = totalStudents - presentStudents;

    if (attendanceChart) attendanceChart.destroy();
    if (participationChart) participationChart.destroy();

    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    attendanceChart = new Chart(attendanceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Présents', 'Absents'],
            datasets: [{
                data: [presentStudents, absentStudents],
                backgroundColor: ['#27ae60', '#e74c3c'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    const participationBySession = [0, 1, 2, 3, 4, 5].map(sessionIndex => 
        students.filter(student => student.participation[sessionIndex]).length
    );

    const participationCtx = document.getElementById('participationChart').getContext('2d');
    participationChart = new Chart(participationCtx, {
        type: 'bar',
        data: {
            labels: ['Session 1', 'Session 2', 'Session 3', 'Session 4', 'Session 5', 'Session 6'],
            datasets: [{
                label: 'Nombre de participants',
                data: participationBySession,
                backgroundColor: '#3498db',
                borderColor: '#2980b9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: totalStudents
                }
            }
        }
    });
}

// ===== VALIDATION DU FORMULAIRE =====
const matriculeInput = document.getElementById('matricule');
const fullnameInput = document.getElementById('fullname');
const groupInput = document.getElementById('group_id');

const patterns = {
    studentId: /^\d+$/,
    name: /^[A-Za-zÀ-ÿ\s'-]+$/,
    group: /^[A-Za-z0-9\- ]+$/
};

function validateStudentId(value) {
    return patterns.studentId.test(value) && value.length > 0;
}

function validateName(value) {
    return patterns.name.test(value) && value.length >= 2;
}

function validateGroup(value) {
    return patterns.group.test(value) && value.length > 0;
}

function showError(inputElement, errorElement, isValid) {
    if (!isValid && inputElement.value.length > 0) {
        inputElement.classList.add('invalid');
        inputElement.classList.remove('valid');
        errorElement.classList.add('show');
    } else if (isValid) {
        inputElement.classList.add('valid');
        inputElement.classList.remove('invalid');
        errorElement.classList.remove('show');
    } else {
        inputElement.classList.remove('valid', 'invalid');
        errorElement.classList.remove('show');
    }
}

if (matriculeInput) {
    matriculeInput.addEventListener('input', function() {
        const isValid = validateStudentId(this.value);
        const errorEl = document.getElementById('matriculeError');
        if (errorEl) showError(this, errorEl, isValid);
    });
}

if (fullnameInput) {
    fullnameInput.addEventListener('input', function() {
        const isValid = validateName(this.value);
        const errorEl = document.getElementById('fullnameError');
        if (errorEl) showError(this, errorEl, isValid);
    });
}

if (groupInput) {
    groupInput.addEventListener('input', function() {
        const isValid = validateGroup(this.value);
        const errorEl = document.getElementById('groupError');
        if (errorEl) showError(this, errorEl, isValid);
    });
}

// ===== INITIALISATION EXERCICE 7 =====
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 APPLICATION DÉMARRÉE');
    console.log('🔍 INITIALISATION EXERCICE 7 - Recherche et Tri');
    
    renderTable();
    
    // Attendre que le tableau soit rendu
    setTimeout(() => {
        initSearchByName();
        
        // Ajouter les événements aux boutons de tri
        const sortAbsencesBtn = document.getElementById('sortByAbsences');
        const sortParticipationBtn = document.getElementById('sortByParticipation');
        const resetSortBtn = document.getElementById('resetSort');
        
        if (sortAbsencesBtn) {
            sortAbsencesBtn.addEventListener('click', sortByAbsencesAscending);
        }
        
        if (sortParticipationBtn) {
            sortParticipationBtn.addEventListener('click', sortByParticipationDescending);
        }
        
        if (resetSortBtn) {
            resetSortBtn.addEventListener('click', resetSort);
        }
    }, 500);
});