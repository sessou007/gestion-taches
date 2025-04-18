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

    $stmt = $pdo->prepare("SELECT id FROM postes  WHERE  poste_name = 'super admin'");
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
        
    </style>
    
</head>

<body>

 <!-- Inclure la barre de navigation -->

<!-- Contenu principal -->
<div class="container-fluid p-0">
    <!-- Section des images sous forme de liens -->
    <div class="row text-center bg-secondary m-0">
    

        <div class="row text-center bg-secondary w-100 m-0">
        <?php include('respons.php'); ?>
        <h1 class="text-center text-white p-3 w-100">Suivi et gestion des tâches</h1>
        
            <?php if ($postid == $postv['id']) { ?>
                <div class="col-md-3 col-12 h-50 p-0">
                    <a href="listedirect.php" class="d-flex flex-column justify-content-center align-items-center custom-link" style="height: 250px; text-decoration: none;">
                        <i class="bi bi-people-fill custom-icon" style="font-size: 100px; width: 100%; color:green;"></i>
                        <h5 class="text-white">AGENTS</h5>
                    </a>
                </div>
            <?php } ?>

            <div class="col-md-3 col-12 h-50 p-0">
                <a href="tac.php" class="d-flex flex-column justify-content-center align-items-center custom-link" style="height: 250px; text-decoration: none;">
                    <i class="bi bi-graph-up-arrow custom-icon" style="font-size: 100px; width: 100%;"></i>
                    <h5 class="text-white">TACHE</h5>
                </a>
            </div>

            <div class="col-md-3 col-12 h-50 p-0">
                <a href="rapport.php" class="d-flex flex-column justify-content-center align-items-center custom-link" style="height: 250px; text-decoration: none;">
                    <i class="bi bi-stack custom-icon" style="font-size: 100px; width: 100%; color:orange;"></i>
                    <h5 class="text-white">RAPPORT</h5>
                </a>
            </div>

            <?php if ($postid == $postv['id'] || $postid == $postv1['id']) { ?>
                <div class="col-md-3 col-12 h-50 p-0">
                    <a href="assigned_tasks.php" class="d-flex flex-column justify-content-center align-items-center custom-link" style="height:250px; text-decoration: none;">
                        <i class="bi bi-list-task custom-icon" style="font-size: 100px; width: 100%; color:blue;"></i>
                        <h5 class="text-white">TÂCHES ASSIGNÉES</h5>
                    </a>
                </div>
            <?php } ?>

            <?php if ($postid == $postv2['id'] ) { ?>
                <div class="col-md-3 col-12 h-50 p-0">
                    <a href="home.php" class="d-flex flex-column justify-content-center align-items-center custom-link" style="height:250px; text-decoration: none;">
                        <i class="bi bi-list-task custom-icon" style="font-size: 100px; width: 100%; color:blue;"></i>
                        <h5 class="text-white">PAGE ADMIN</h5>
                    </a>
                </div>
            <?php } ?>
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
        <h1 class="text-center">Calendrier des Événements</h1>
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
    <audio id="alarmSound" src="/t/images/ALRMClok_Reveil electronique sonnerie 1 (ID 0035)_LS.mp3" preload="auto"></audio>
</div>

<script>
    const alarmSound = document.getElementById('alarmSound');
let notifiedEvents = JSON.parse(localStorage.getItem('notifiedEvents')) || {};
let delayedReminders = JSON.parse(localStorage.getItem('delayedReminders')) || {};

// Gestion des erreurs audio
alarmSound.addEventListener('error', (e) => {
    console.error('Erreur de chargement de l\'audio :', e);
    console.error('Code d\'erreur :', alarmSound.error.code);
    console.error('Message d\'erreur :', alarmSound.error.message);
});

function showAlarmAlert(eventTitle, eventId) {
    alarmSound.play().then(() => {
        console.log('Audio is playing');
    }).catch(error => {
        console.error('Erreur lors de la lecture de l\'audio :', error);
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
            console.log('Alarm stopped');
            stopAlarm();
        } else {
            console.log('Remind later');
            remindLater(eventId);
        }
    });
}

function stopAlarm() {
    alarmSound.pause();
    alarmSound.currentTime = 0;
    Swal.close(); // Fermer l'alerte
}

function remindLater(eventId) {
    alarmSound.pause();
    alarmSound.currentTime = 0;
    delayedReminders[eventId] = Date.now() + 5 * 60 * 1000; // Rappel dans 5 minutes
    localStorage.setItem('delayedReminders', JSON.stringify(delayedReminders));
}

function checkAlarms() {
    console.log('Checking alarms...');
    $.ajax({
        url: 'getevents.php',
        method: 'GET',
        dataType: 'json',
        success: function(events) {
            console.log('Events retrieved:', events);
            const currentTime = new Date();
            events.forEach(event => {
                const eventStartTime = new Date(event.start);
                const alarmTime = new Date(eventStartTime - event.alarme * 60 * 1000);
                if (currentTime >= alarmTime && currentTime < eventStartTime) {
                    if (!notifiedEvents[event.id] && !delayedReminders[event.id]) {
                        if (Notification.permission === "granted") {
                            console.log('Showing alarm for event:', event.title);
                            showAlarmAlert(event.title, event.id);
                            notifiedEvents[event.id] = true;
                            localStorage.setItem('notifiedEvents', JSON.stringify(notifiedEvents));
                        }
                    }
                } else if (delayedReminders[event.id] && currentTime >= delayedReminders[event.id]) {
                    if (Notification.permission === "granted") {
                        console.log('Showing delayed alarm for event:', event.title);
                        showAlarmAlert(event.title, event.id);
                        delete delayedReminders[event.id];
                        localStorage.setItem('delayedReminders', JSON.stringify(delayedReminders));
                    }
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Erreur lors de la récupération des événements : ', textStatus, errorThrown);
            console.error('Réponse du serveur :', jqXHR.responseText);
        }
    });
}

setInterval(checkAlarms, 60000);
checkAlarms();
</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>


    <!-- Modal pour modifier ou supprimer un événement -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Début :</strong> <span id="eventModalStart"></span></p>
                <p><strong>Fin :</strong> <span id="eventModalEnd"></span></p>
                <div class="form-group">
                    <label for="editTitle">Modifier le titre :</label>
                    <input type="text" class="form-control" id="editTitle">
                </div>
                <div class="form-group">
                    <label>Description :</label>
                    <div id="eventModalDescription" style="border: 1px solid #ddd; padding: 10px; border-radius: 4px; margin-bottom: 15px; background-color: #f9f9f9;"></div>
                </div>
                <div class="form-group">
                    <label for="editDescription">Modifier la description :</label>
                    <textarea class="form-control" id="editDescription" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="editStartDate">Date de début :</label>
                    <input type="date" class="form-control" id="editStartDate">
                </div>
                <div class="form-group">
                    <label for="editStartTime">Heure de début :</label>
                    <input type="time" class="form-control" id="editStartTime">
                </div>
                <div class="form-group">
                    <label for="editEndDate">Date de fin :</label>
                    <input type="date" class="form-control" id="editEndDate">
                </div>
                <div class="form-group">
                    <label for="editEndTime">Heure de fin :</label>
                    <input type="time" class="form-control" id="editEndTime">
                </div>

                <input type="hidden" id="eventId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="confirmUpdateEvent()">Modifier</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteEvent()">Supprimer</button>
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
        title: 'Ajouter un événement',
        html: `
            <div class="row">
                <div class="col-12 mb-2">
                    <input id="swalEvtTitle" class="swal2-input" placeholder="Titre de l'événement">
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-2">
                    <textarea id="swalEvtDesc" class="swal2-textarea" placeholder="Description de l'événement"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-2">
                    <label>Date de début</label>
                    <input id="swalEvtStartDate" type="date" class="swal2-input" value="${start.format('YYYY-MM-DD')}">
                </div>
                <div class="col-6 mb-2">
                    <label>Heure de début</label>
                    <input id="swalEvtStartTime" type="time" class="swal2-input">
                </div>
            </div>
            <div class="row">
                <div class="col-6 mb-2">
                    <label>Date de fin</label>
                    <input id="swalEvtEndDate" type="date" class="swal2-input" value="${end.format('YYYY-MM-DD')}">
                </div>
                <div class="col-6 mb-2">
                    <label>Heure de fin</label>
                    <input id="swalEvtEndTime" type="time" class="swal2-input">
                </div>
            </div>
            <div class="form-group">
                <label for="alarmTime">Définir une alarme de rappel:</label>
                <select class="swal2-input" id="alarmTime">
                    <option value="">Aucun rappel</option>
                    <option value="5">5 minutes avant</option>
                    <option value="10">10 minutes avant</option>
                    <option value="30">30 minutes avant</option>
                </select>
            </div>
            <div class="form-group" id="assignToEmployees">
                <!-- Les employés seront chargés ici dynamiquement -->
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Enregistrer',
        cancelButtonText: 'Annuler',
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
},


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
</body>
</html>