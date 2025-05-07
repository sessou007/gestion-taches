<?php
session_start();
require 'config.php'; // Configuration de la base de données

if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirection vers la page de connexion
    header("Location: login.php");
    exit; // Terminez le script après la redirection
}

// Récupération de l'ID de l'utilisateur connecté depuis la session
$id = $_SESSION['user_id'];

try {
    // Préparation de la requête pour récupérer les colonnes "statut" et "role"
    $sql = "SELECT active, poste_id FROM users WHERE user_id = :id";
    $stmt = $pdo->prepare($sql);
    // Liaison du paramètre :id à la valeur de $user_id
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Exécution de la requête
    $stmt->execute();

    // Récupération du résultat
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $statut = $row['active']; // La valeur de "statut"
        $postid = $row['poste_id'];   

        // Vérification du statut
        if ($statut == 0) {

             // Désactivation du compte et redirection avec un message
             $_SESSION['error_message'] = "Votre compte est désactivé. Veuillez contacter l'administrateur pour l'activer.";
             session_destroy(); // Déconnecte l'utilisateur
             header("Location: login.php");
             exit;
        } else {
            // Le compte est actif, afficher les informations
             // echo "Statut de l'utilisateur connecté : Actif<br>";
             // echo "Rôle de l'utilisateur connecté : " . htmlspecialchars($role);
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet ID.";
    }
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
}

  $stmt = $pdo->prepare("SELECT id FROM postes  WHERE  poste_name = 'directeur'");
    $stmt->execute();
    $postv = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id FROM postes  WHERE  poste_name = 'secretaire'");
    $stmt->execute();
    $postv1 = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id FROM postes  WHERE  poste_name = 'supra_admin'");
    $stmt->execute();
    $postv2 = $stmt->fetch(PDO::FETCH_ASSOC);

    
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT active FROM Users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

 //if (!$user || $user['active'] == 0) { 
    // Si le compte est désactivé ou introuvable
     //session_destroy(); // Détruire la session
     //header("Location: login.php?error=compte_desactive");
    // exit;
 //}

if (!$user) { 
    // Si le compte est désactivé ou introuvable
    session_destroy(); // Détruire la session
    header("Location: login.php?error=compte_desactive");
    exit;
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion des Tâches</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome pour les icônes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Style général */
        body {
            overflow-x: hidden;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .custom-link {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: block;
            width: 100%;
            height: 50%;
        }
        .custom-link:hover {
            transform: scale(0.7);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .custom-icon {
            color: #ffffff; /* Couleur des icônes */
        }
        .row {
            margin: 0 !important; /* Supprime les marges par défaut */
            padding: 0 !important; /* Supprime les paddings par défaut */
        }
        .col-md-3 {
            padding: 0 !important; /* Supprime les paddings des colonnes */
        }
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e67e22;
            --success-color: #27ae60;
            --text-light: #ecf0f1;
            --glass-bg: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Style du tableau de bord */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color), #1a252f);
            box-shadow: 0 4px 25px rgba(0,0,0,0.1);
            padding: 1.5rem 0;
        }

        .dashboard-title {
            color: var(--text-light);
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
        }

        .dashboard-card {
            background: var(--glass-bg);
            border-radius: 15px;
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            min-height: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none !important;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.2);
            background: rgba(255, 255, 255, 0.15);
        }

        .card-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .dashboard-card:hover .card-icon {
            transform: scale(1.1);
        }

        .card-label {
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            margin: 0;
        }

        .card-label::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--text-light);
            transition: width 0.3s ease;
        }

        .dashboard-card:hover .card-label::after {
            width: 60%;
        }

        

        /* Style du calendrier */
        #calendar {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 2rem auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 1000px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
                padding: 1rem;
            }

            .dashboard-card {
                min-height: 200px;
                padding: 1.5rem;
            }

            .card-icon {
                font-size: 3rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-card {
                min-height: 180px;
            }
        }
        
    </style>
    




 <!-- Inclure la barre de navigation -->

<!-- Contenu principal -->
<div class="container-fluid p-0">
    <!-- Section des images sous forme de liens -->
    <div class="row text-center bg-secondary m-0">
    
  <!-- En-tête -->
  <div class="dashboard-header">
        <div class="container">
            <?php include('respons.php'); ?>
            <h1 class="dashboard-title text-center mb-0">Suivi et Gestion des Tâches</h1>
       
    <!-- Contenu principal -->
    <div class="container">
        <!-- Cartes de navigation -->
        <div class="dashboard-grid">
            <?php if ($postid == $postv['id']) : ?>
            <a href="listedirect.php" class="dashboard-card">
                <i class="bi bi-people-fill card-icon text-success"></i>
                <h3 class="card-label">Agents</h3>
            </a>
            <?php endif; ?>

            <a href="tac.php" class="dashboard-card">
                <i class="bi bi-graph-up-arrow card-icon text-info"></i>
                <h3 class="card-label">Tâches</h3>
            </a>

            <a href="rapport.php" class="dashboard-card">
                <i class="bi bi-stack card-icon text-warning"></i>
                <h3 class="card-label">Rapports</h3>
            </a>

            <?php if ($postid == $postv['id'] || $postid == $postv1['id']) : ?>
            <a href="assigned_tasks.php" class="dashboard-card">
                <i class="bi bi-list-task card-icon text-primary"></i>
                <h3 class="card-label">Tâches Assignées</h3>
            </a>
            <?php endif; ?>

            <?php if ($postid == $postv2['id']) : ?>
            <a href="home.php" class="dashboard-card">
                <i class="bi bi-gear-fill card-icon text-secondary"></i>
                <h3 class="card-label">Administration</h3>
            </a>
            <?php endif; ?>
        </div>
        </div>
    </div>

    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<style>   
   
    /* Style pour le calendrier */
    #calendar {
            max-width: 80%;
            margin: 0 auto;
        }

        /* Style pour le modal */
        .modal-body {
            padding: 20px;
        }

        /* Responsiveness pour le formulaire dans le modal */
        .form-group {
            margin-bottom: 1rem;
        }
        /* Media queries pour rendre tout responsive */
 
/* Pour écrans entre 1200px et 992px */
@media (max-width: 1200px) {
    .content {
        margin-left: 220px;
    }
    .sidebar {
        width: 220px;
    }
}

/* Pour écrans entre 992px et 768px */
@media (max-width: 992px) {
    .content {
        margin-left: 200px;
    }
    .sidebar {
        width: 200px;
    }
}

/* Pour écrans entre 768px et 576px */
@media (max-width: 768px) {
    .content {
        margin-left: 0;
        padding: 10px;
    }
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }
    .navbar {
        justify-content: space-between;
        padding: 10px;
    }
    .navbar-brand {
        font-size: 1.2rem;
    }
    .icon-img {
        width: 25px;
        height: 25px;
    }
    #calendar {
        max-width: 100%;
    }
}

/* Pour écrans en dessous de 576px */
@media (max-width: 576px) {
    .sidebar {
        padding: 5px;
    }
    .navbar {
        padding: 5px;
    }
    .content {
        padding: 5px;
    }
    .modal-body {
        padding: 10px;
    }
}

.blinking {
    animation: blink 1s infinite;
}

@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
}
#alarmAlert {
            display: none;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: red;
            color: white;
            border-radius: 10px;
            font-size: 24px;
            z-index: 1000;
            text-align: center;
        }
        .custom-swal-popup {
    font-family: 'Arial', sans-serif;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}
.custom-swal-popup {
    font-family: 'Arial', sans-serif;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    background-color: #f8f9fa;
    color: #333;
}
     

    </style>
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center">Calendrier des Tâches</h1>
        <div id="calendar"></div>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    </div>

    <div id="alarmAlert" style="display: none;">
    Un événement commence bientôt !
    <br>
    <button class="stopAlarmButton" style="margin-top: 10px;">Arrêter l'alarme</button>
    <button class="remindLaterButton" style="margin-top: 10px;">Me rappeler plus tard</button>
    <audio id="alarmSound" src="/TA/sounds/alert.mp3" preload="auto"></audio>
</div>

<script>
// Déclaration des variables globales
let notifiedEvents = JSON.parse(localStorage.getItem('notifiedEvents')) || {};
let delayedReminders = JSON.parse(localStorage.getItem('delayedReminders')) || {};

const alarmSound = document.getElementById('alarmSound');
alarmSound.src = '/TA/sounds/alert.mp3';

function stopAlarm(eventId) {
    // Marquer l'événement comme notifié
    notifiedEvents[eventId] = true;
    // Supprimer tout rappel différé
    delete delayedReminders[eventId];
    // Mettre à jour le stockage local
    localStorage.setItem('notifiedEvents', JSON.stringify(notifiedEvents));
    localStorage.setItem('delayedReminders', JSON.stringify(delayedReminders));
    // Arrêter le son
    alarmSound.pause();
    alarmSound.currentTime = 0;
}

function remindLater(eventId, eventStart) {
    const now = Date.now();
    const eventStartTime = new Date(eventStart).getTime();
    const nextReminder = now + 300000; // 5 minutes en millisecondes

    // Vérifier si le rappel est possible avant le début
    if (nextReminder < eventStartTime) {
        delayedReminders[eventId] = nextReminder;
    } else {
        delete delayedReminders[eventId];
    }

    // Réactiver les notifications pour cet événement
    delete notifiedEvents[eventId];
    
    // Mettre à jour le stockage local
    localStorage.setItem('delayedReminders', JSON.stringify(delayedReminders));
    localStorage.setItem('notifiedEvents', JSON.stringify(notifiedEvents));
    
    // Arrêter le son immédiatement
    alarmSound.pause();
    alarmSound.currentTime = 0;
}

function showAlarmAlert(eventTitle, eventId, eventStart) {
    alarmSound.play().catch(error => {
        console.error('Erreur audio:', error);
        Swal.fire('Erreur', 'Son d\'alarme introuvable', 'error');
    });
    
    Swal.fire({
        title: "La tâche \"" + eventTitle + "\" commence bientôt !",
        text: "Que souhaitez-vous faire ?",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Arrêter l'alarme",
        cancelButtonText: "Me rappeler plus tard",
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            stopAlarm(eventId);
        } else {
            remindLater(eventId, eventStart);
        }
    });
}

function checkAlarms() {
    console.log('Vérification des alarmes...');
    $.ajax({
        url: 'getevents.php',
        method: 'GET',
        dataType: 'json',
        success: function(events) {
            const now = Date.now();
            events.forEach(event => {
                if (!event.alarme) return;

                const eventStart = new Date(event.start).getTime();
                const alarmTime = eventStart - (event.alarme * 60000);

                // Déclencher l'alarme initiale
                if (now >= alarmTime && now <= eventStart) {
                    if (!notifiedEvents[event.id] && !delayedReminders[event.id]) {
                        showAlarmAlert(event.title, event.id, event.start);
                        notifiedEvents[event.id] = true;
                        localStorage.setItem('notifiedEvents', JSON.stringify(notifiedEvents));
                    }
                }

                // Gérer les rappels différés récurrents
                if (delayedReminders[event.id] && now >= delayedReminders[event.id]) {
                    showAlarmAlert(event.title, event.id, event.start);
                    delete delayedReminders[event.id];
                    localStorage.setItem('delayedReminders', JSON.stringify(delayedReminders));
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Erreur fetch:', status, error);
        }
    });
}

// Vérifier les alarmes toutes les minutes
setInterval(checkAlarms, 60000);
checkAlarms(); // Vérification initiale
</script>
    <!-- Modal pour modifier ou supprimer un événement -->
    <style>
    /* Styles personnalisés pour le modal */
    #eventModal .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    }

    #eventModal .modal-header {
        background: linear-gradient(135deg, #2c3e50, #3498db);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 1.5rem;
        border-bottom: none;
    }

    #eventModal .modal-title {
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 1.4rem;
    }

    #eventModal .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
        transition: opacity 0.2s ease;
    }

    #eventModal .close:hover {
        opacity: 1;
    }

    #eventModal .modal-body {
        padding: 2rem;
        background: #f8f9fa;
    }

    #eventModal .form-group {
        margin-bottom: 1.5rem;
    }

    #eventModal label {
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    #eventModal .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    #eventModal .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
    }

    #eventModal #eventModalDescription {
        border: 1px solid #e0e0e0;
        padding: 1rem;
        border-radius: 8px;
        background: white;
        min-height: 100px;
        color: #555;
    }

    #eventModal .modal-footer {
        border-top: 1px solid #eee;
        padding: 1.5rem;
        background: #f8f9fa;
    }

    #eventModal .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    #eventModal .btn-primary {
        background: #3498db;
        border-color: #3498db;
    }

    #eventModal .btn-primary:hover {
        background: #2980b9;
        transform: translateY(-1px);
    }

    #eventModal .btn-danger {
        background: #e74c3c;
        border-color: #e74c3c;
    }

    #eventModal .btn-danger:hover {
        background: #c0392b;
        transform: translateY(-1px);
    }

    /* Disposition des champs date/heure */
    .datetime-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    

    @media (max-width: 576px) {
        .datetime-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle">Gestion d'événement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <p class="text-muted mb-1"><i class="bi bi-calendar-event mr-2"></i>Début : <span id="eventModalStart" class="font-weight-bold text-dark"></span></p>
                    <p class="text-muted"><i class="bi bi-clock-history mr-2"></i>Fin : <span id="eventModalEnd" class="font-weight-bold text-dark"></span></p>
                </div>

                <div class="form-group">
                    <label for="editTitle">Titre de l'événement</label>
                    <input type="text" class="form-control" id="editTitle" placeholder="Saisir le titre">
                </div>

                <div class="form-group">
                    <label>Description actuelle</label>
                    <div id="eventModalDescription" class="editable-content"></div>
                </div>

                <div class="form-group">
                    <label for="editDescription">Modifier la description</label>
                    <textarea class="form-control" id="editDescription" rows="3" placeholder="Ajouter une description..."></textarea>
                </div>

                <div class="datetime-grid">
                    <div class="form-group">
                        <label for="editStartDate">Date de début</label>
                        <input type="date" class="form-control" id="editStartDate">
                    </div>
                    <div class="form-group">
                        <label for="editStartTime">Heure de début</label>
                        <input type="time" class="form-control" id="editStartTime">
                    </div>
                    <div class="form-group">
                        <label for="editEndDate">Date de fin</label>
                        <input type="date" class="form-control" id="editEndDate">
                    </div>
                    <div class="form-group">
                        <label for="editEndTime">Heure de fin</label>
                        <input type="time" class="form-control" id="editEndTime">
                    </div>
                </div>

                <input type="hidden" id="eventId">
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-dismiss="modal">
                    <a href="dashboard.php">Annuler</a>
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteEvent()">Supprimer</button>
                <button type="button" class="btn btn-primary" onclick="confirmUpdateEvent()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
    <script>
        // Assurez-vous que ces scripts sont bien chargés dans votre page
$(document).ready(function() {
    // Initialisation du modal
    $('#eventModal').modal({
        show: false,
        backdrop: 'static'
    });

    // Fonction pour fermer le modal
    function closeModal() {
        $('#eventModal').modal('hide');
    }

    // Gestionnaire d'événement pour le bouton Fermer
    $('.btn-secondary').on('click', function() {
        closeModal();
    });
        // Fonction pour convertir les URLs en liens cliquables
function urlify(text) {
    if (!text) return text;
    const urlRegex = /(https?:\/\/[^\s]+)/g;
    return text.replace(urlRegex, function(url) {
        return '<a href="' + url + '" target="_blank" style="color: #007bff; text-decoration: underline;">' + url + '</a>';
    });
}
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },


                
                defaultDate: moment().format('YYYY-MM-DD'),
                navLinks: true,
                selectable: true,
                selectHelper: true,
                events: 'getevents.php', // Récupération des événements depuis la base de données



// Formulaire SweetAlert2 pour ajouter un nouvel événement
             
select: function(start, end) {
    Swal.fire({
        title: '➕ Ajouter une nouvelle tâche',
        html: `
            <div class="swal2-form-container">
                <div class="form-group mas-form-group">
                    <input id="swalEvtTitle" 
                           class="form-control mas-input" 
                           placeholder="Titre de la tâche">
                </div>

                <div class="form-group mas-form-group">
                    <textarea id="swalEvtDesc" 
                              class="form-control mas-textarea" 
                              placeholder="Détails de la tâche..."
                              rows="3"></textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group mas-form-group">
                            <label class="mas-label"><i class="fas fa-calendar-start me-2"></i>Date début</label>
                            <input type="date" 
                                   id="swalEvtStartDate" 
                                   class="form-control mas-input-date" 
                                   value="${start.format('YYYY-MM-DD')}">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mas-form-group">
                            <label class="mas-label"><i class="fas fa-clock me-2"></i>Heure début</label>
                            <input type="time" 
                                   id="swalEvtStartTime" 
                                   class="form-control mas-input-time">
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="form-group mas-form-group">
                            <label class="mas-label"><i class="fas fa-calendar-end me-2"></i>Date fin</label>
                            <input type="date" 
                                   id="swalEvtEndDate" 
                                   class="form-control mas-input-date" 
                                   value="${end.format('YYYY-MM-DD')}">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mas-form-group">
                            <label class="mas-label"><i class="fas fa-clock me-2"></i>Heure fin</label>
                            <input type="time" 
                                   id="swalEvtEndTime" 
                                   class="form-control mas-input-time">
                        </div>
                    </div>
                </div>

                <div class="form-group mas-form-group mt-3">
                    <label class="mas-label"><i class="fas fa-bell me-2"></i>Rappel</label>
                    <select id="alarmTime" class="form-select mas-select">
                        <option value="">Aucun rappel</option>
                        <option value="5">5 minutes avant</option>
                        <option value="10">10 minutes avant</option>
                        <option value="30">30 minutes avant</option>
                    </select>
                </div>

                <div class="form-group mas-form-group mt-3" id="assignToEmployees">
                    <label class="mas-label"><i class="fas fa-users me-2"></i>Assignation</label>
                    <!-- Employés chargés dynamiquement -->
                </div>
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText:  ' <i class="fas fa-save me-2" ></i>Enregistrer',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler',
        customClass: {
            popup: 'swal2-mas-popup',
            confirmButton: 'btn btn-mas-primary',
            cancelButton: 'btn btn-mas-secondary',
            input: 'mas-swal-input'
        },
        didOpen: () => {
            // Charger les employés de la direction de l'utilisateur connecté
            $.ajax({

            
                url: 'getemployees.php',
                type: 'GET',
                success: function(response) {
                    <?php if ($postid == $postv['id'] || $postid == $postv1['id']) { ?>
                    const employees = JSON.parse(response);
                     
               
            
                    let html = '<label for="employees">Assigner à :</label>';
                    html += '<select id="employees" class="swal2-input" multiple>';
                    employees.forEach(employee => {
                        html += `<option value="${employee.user_id}">${employee.first_name} ${employee.last_name}</option>`;
                    });
                    html += '</select>';
                    $('#assignToEmployees').html(html);
                    <?php } ?>
                }
            });
        },
        preConfirm: () => {
            const title = document.getElementById('swalEvtTitle').value;
            const description = document.getElementById('swalEvtDesc').value;
            const startDate = document.getElementById('swalEvtStartDate').value;
            const startTime = document.getElementById('swalEvtStartTime').value;
            const endDate = document.getElementById('swalEvtEndDate').value;
            const endTime = document.getElementById('swalEvtEndTime').value;
            const alarmTime = document.getElementById('alarmTime').value || null;
            const employees = $('#employees').val();
            

            if (!title || !startDate || !endDate || !startTime || !endTime) {
                Swal.showValidationMessage('Veuillez remplir tous les champs.');
                return false;
            }

            if (endDate < startDate) {
                Swal.showValidationMessage('La date de fin ne peut pas être antérieure à la date de début.');
                return false;
            }

            if (endDate === startDate && endTime < startTime) {
                Swal.showValidationMessage('L\'heure de fin ne peut pas être antérieure à l\'heure de début pour la même journée.');
                return false;
            }

            return {
                title: title,
                description: description,
                start: `${startDate} ${startTime}`,
                end: `${endDate} ${endTime}`,
                alarm: alarmTime,
                employees: employees
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const eventData = result.value;

            // Envoi de la requête AJAX pour ajouter l'événement dans la base de données
            $.ajax({
                url: 'addevent.php',
                type: 'POST',
                data: {
                    titre: eventData.title,
                    description: eventData.description,
                    debut: eventData.start,
                    alarme: eventData.alarm,
                    fin: eventData.end,
                    employees: eventData.employees
                },
                success: function(response) {
                    try {
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            Swal.fire(
                                'Succès',
                                'L\'événement a été ajouté avec succès!',
                                'success'
                            );
                            $('#calendar').fullCalendar('refetchEvents');
                        } else {
                            Swal.fire(
                                'Erreur',
                                'Erreur lors de l\'ajout de l\'événement : ' + result.message,
                                'error'
                            );
                        }
                    } catch (e) {
                        Swal.fire(
                            'Erreur',
                            'Erreur lors du traitement de la réponse : ' + e.message,
                            'error'
                        );
                    }
                }
            });
        }
    });
    $('#calendar').fullCalendar('unselect');
} ,


                // Afficher un modal pour modifier ou supprimer l'événement
                
                eventClick: function(event) {
    // Afficher les détails de l'événement dans le modal
    $('#eventModalTitle').text(event.title);
    $('#eventModalStart').text(moment(event.start).format('DD/MM/YYYY HH:mm'));
    $('#eventModalEnd').text(moment(event.end).format('DD/MM/YYYY HH:mm'));
    
    // Afficher la description avec liens cliquables
    if (event.description) {
        $('#eventModalDescription').html(urlify(event.description));
    } else {
        $('#eventModalDescription').html('Aucune description');
    }
    $('#editTitle').val(event.title);
    $('#editDescription').val(event.description);
    $('#editStartDate').val(moment(event.start).format('YYYY-MM-DD'));
    $('#editStartTime').val(moment(event.start).format('HH:mm'));
    $('#editEndDate').val(moment(event.end).format('YYYY-MM-DD'));
    $('#editEndTime').val(moment(event.end).format('HH:mm'));
    $('#eventId').val(event.id);

    // Vérifier si l'utilisateur connecté est celui à qui la tâche est assignée
    const loggedInUserId = <?php echo $_SESSION['user_id']; ?>; // Récupérer l'ID de l'utilisateur connecté
    const assignedUserId = event.user_id; // Supposons que `user_id` est disponible dans l'objet `event`

    if (loggedInUserId === assignedUserId) {
        // Désactiver les boutons de modification et de suppression
        $('#updateEventBtn').prop('disabled', true);
        $('#deleteEventBtn').prop('disabled', true);

        // Afficher un message d'alerte stylisé
        Swal.fire({
    icon: 'warning',
    title: 'Opération non autorisée',
    text: 'Vous ne pouvez pas modifier ou supprimer une tâche qui vous a été assignée.',
    confirmButtonColor: '#3085d6',
    customClass: {
        popup: 'custom-swal-popup', // Ajouter une classe CSS personnalisée
    },
});
    } else {
        // Activer les boutons de modification et de suppression
        $('#updateEventBtn').prop('disabled', false);
        $('#deleteEventBtn').prop('disabled', false);
    }

    $('#eventModal').modal('show');
    }
});
            });
        });

        function confirmUpdateEvent() {
    const eventId = $('#eventId').val();
    const title = $('#editTitle').val();
    const description = $('#editDescription').val();
    const startDate = $('#editStartDate').val();
    const startTime = $('#editStartTime').val();
    const endDate = $('#editEndDate').val();
    const endTime = $('#editEndTime').val();

    $.ajax({
        url: 'updatevent.php',
        type: 'POST',
        data: {
            id: eventId,
            titre: title,
            description: description,
            debut: `${startDate} ${startTime}`,
            fin: `${endDate} ${endTime}`
        },
        success: function(response) {
            try {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    Swal.fire(
                        'Succès',
                        'L\'événement a été mis à jour avec succès!',
                        'success'
                    );
                    $('#calendar').fullCalendar('refetchEvents');
                    $('#eventModal').modal('hide');
                } else {
                    Swal.fire(
                        'Erreur',
                        result.message || 'Erreur lors de la mise à jour de l\'événement.',
                        'error'
                    );
                }
            } catch (e) {
                Swal.fire(
                    'Erreur',
                    'Erreur lors du traitement de la réponse : ' + e.message,
                    'error'
                );
            }
        }
    });
}

        function confirmDeleteEvent() {
    const eventId = $('#eventId').val();

    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Vous ne pourrez pas récupérer cet événement!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'deletevent.php',
                type: 'POST',
                data: { id: eventId },
                success: function(response) {
                    try {
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            Swal.fire(
                                'Supprimé!',
                                'L\'événement a été supprimé.',
                                'success'
                            );
                            $('#calendar').fullCalendar('refetchEvents');
                            $('#eventModal').modal('hide');
                        } else {
                            Swal.fire(
                                'Erreur',
                                result.message || 'Erreur lors de la suppression de l\'événement.',
                                'error'
                            );
                        }
                    } catch (e) {
                        Swal.fire(
                            'Erreur',
                            'Erreur lors du traitement de la réponse : ' + e.message,
                            'error'
                        );
                    }
                }
            });
        }
    });
}
    </script>




    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.php'; ?>
    <style>
        :root {
            --mas-primary: #2A2F4F;
            --mas-secondary: #917FB3;
            --mas-accent: #E5BEEC;
            --mas-light: #FDE2F3;
            --mas-dark: #2c3e50;
        }

        /* Styles personnalisés pour SweetAlert */
        .swal2-mas-popup {
            font-family: 'Inter', sans-serif;
            border-radius: 1rem !important;
            background: #f8f9fa !important;
            padding: 2rem !important;
        }

        .swal2-mas-popup .swal2-title {
            color: var(--mas-primary);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }

        .mas-form-group {
            margin-bottom: 1.5rem;
        }

        .mas-label {
            display: block;
            color: var(--mas-primary);
            font-weight: 600;
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .mas-input, .mas-textarea, .mas-select {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 2px solid #e9ecef;
            border-radius: 0.8rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            font-size: 1rem;
        }

        .mas-input:focus, .mas-textarea:focus, .mas-select:focus {
            border-color: var(--mas-secondary);
            box-shadow: 0 0 0 3px rgba(145, 127, 179, 0.15);
            outline: none;
        }

        .mas-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .mas-select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232A2F4F' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1.2rem center;
            background-size: 1.2em;
        }

        .btn-mas-primary {
            background: var(--mas-primary) !important;
            color: white !important;
            padding: 0.8rem 2rem !important;
            border-radius: 0.8rem !important;
            font-weight: 600 !important;
            transition: transform 0.2s ease;
        }

        .btn-mas-primary:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        .btn-mas-secondary {
            background: var(--mas-secondary) !important;
            color: white !important;
            padding: 0.8rem 2rem !important;
            border-radius: 0.8rem !important;
            font-weight: 600 !important;
        }

        .swal2-actions {
            gap: 1rem;
        }
    </style>
</body>
</html>