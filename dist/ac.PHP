<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion des Tâches</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.print.min.css" rel="stylesheet" media="print" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Style général */
        body {
            overflow-x: hidden;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Barre de navigation horizontale */
        .navbar {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: green;
        }

        h1{
            text-align: center;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }

        .navbar-nav {
            margin-left: auto;
        }

        .icon-img {
            width: 30px;
            height: 30px;
            margin-right: 8px;
        }

        .navbar-toggler {
            border: none;
        }

        .dropdown-menu {
            background-color: white;
            color: white;
        }

        .dropdown-item {
            color: black;
        }

        .dropdown-item:hover {
            background-color: white;
        }

        /* Barre de navigation verticale */
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 56px; /* Ajusté pour ne pas chevaucher la barre horizontale */
            left: 0;
            z-index: 1000;
            background-color: #343a40;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            width: 250px; /* Augmentation de la largeur pour le slider */
            transition: width 0.3s ease; /* Transition douce pour le slider */
        }

        .sidebar img {
            width: 100%;
            max-width: 150px;
            margin-bottom: 20px;
        }

        .sidebar .nav {
            flex-direction: column;
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px 0;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: color 0.3s;
        }

        .sidebar .nav-link:hover {
            color: #28a745;
        }

        .icon-img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        /* Slider dans la barre verticale */
        .sidebar .slider-container {
            margin: 20px 0;
        }

        .sidebar .slider {
            width: 100%;
            max-width: 100%;
        }
        
        .sidebar .nav-link .icon-img {
            width: 30px;  
            height: 30px;
            margin-right: 10px;
        }

        /* Contenu principal */
        .content {
            margin-left: 250px; /* Ajusté pour laisser de l'espace à la barre latérale */
            padding: 20px;
            margin-top: 56px;
            transition: margin-left 0.3s ease; /* Transition douce pour le contenu */
        }

        /* Section des images et des liens */
        .image-link {
            display: block;
            text-align: center;
            margin-bottom: 30px;
        }

        .image-link img {
            width: 100%;
            max-width: 300px;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s;
        }

        .image-link:hover img {
            transform: scale(1.05);
        }

        .image-title {
            margin-top: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px; /* Réduction de la barre latérale pour les petits écrans */
            }

            .navbar {
                justify-content: space-between;
                padding: 0.5rem 1rem;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .icon-img {
                width: 25px;
                height: 25px;
            }

            .content {
                margin-left: 200px; /* Adaptation pour petits écrans */
            }
        }
    </style>
</head>
<body>

<!-- Barre de navigation horizontale -->
<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">GESTION_DES_TACHES</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="images/login.png" alt="login" class="icon-img"> 
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="profile.php">Profil</a>
                    <a class="dropdown-item" href="index.php">Déconnexion</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- Barre de navigation verticale avec slider -->
<nav class="sidebar">
    <img src="images/armo.png" alt="Logo" class="img-fluid my-3">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <img src="images/images.png" class="icon-img"> Tableau de Bord
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="tasks.php">
                <img src="images/newrap.png" class="icon-img"> Tâches
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="reports.php">
                <img src="images/rapports.jpg" class="icon-img"> Rapports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="notifications.php">
                <img src="images/notif.jpg" class="icon-img"> Notifications
            </a>
        </li>

        <!-- Slider ajouté ici -->
        <div class="slider-container">
            <label for="task-progress" style="color:white;">Progression des Tâches</label>
            <input type="range" class="slider" id="task-progress" name="progress" min="0" max="100" value="50">
        </div>

    </ul>
</nav>

<!-- Contenu principal -->
<div class="content">
    <h1> Suivi et gestion des tâches</h1>

    <!-- Section des images sous forme de liens -->
    <div class="row">
        <div class="col-md-4">
            <a href=" listedirect.php" class="image-link">
                <img src="images/AZ.jpg" alt="Dashboard">
                <div class="image-title">AGENTS</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="tasks.php" class="image-link">
                <img src="images/ttt.jpg" alt="Tâches">
                <div class="image-title">Tâches</div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="reports.php" class="image-link">
                <img src="images/re.jpeg" alt="Rapports">
                <div class="image-title">Rapports</div>
            </a>
        </div>

         <!-- Section du calendrier complet juste en dessous des images -->
<div class="calendar-container">
    <h2>Calendrier de l'année</h2>
    <div id="calendar"></div>
</div>

<style>
    /* Style pour le calendrier */
    #calendar {
            max-width: 100%;
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
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center">Calendrier des Événements</h1>
        <div id="calendar"></div>
    </div>

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
                        `,
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: 'Enregistrer',
                        cancelButtonText: 'Annuler',
                        preConfirm: () => {
                            const title = document.getElementById('swalEvtTitle').value;
                            const description = document.getElementById('swalEvtDesc').value;
                            const startDate = document.getElementById('swalEvtStartDate').value;
                            const startTime = document.getElementById('swalEvtStartTime').value;
                            const endDate = document.getElementById('swalEvtEndDate').value;
                            const endTime = document.getElementById('swalEvtEndTime').value;

                            if (!title || !startDate || !endDate || !startTime || !endTime) {
                                Swal.showValidationMessage('Veuillez remplir tous les champs.');
                                return false;
                            }
                            return {
                                title: title,
                                description: description,
                                start: `${startDate} ${startTime}`,
                                end: `${endDate} ${endTime}`
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
                                    fin: eventData.end
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
                    $('#eventModalTitle').text(event.title);
                    $('#eventModalStart').text(moment(event.start).format('DD/MM/YYYY HH:mm'));
                    $('#eventModalEnd').text(moment(event.end).format('DD/MM/YYYY HH:mm'));
                    $('#editTitle').val(event.title);
                    $('#editDescription').val(event.description);
                    $('#editStartDate').val(moment(event.start).format('YYYY-MM-DD'));
                    $('#editStartTime').val(moment(event.start).format('HH:mm'));
                    $('#editEndDate').val(moment(event.end).format('YYYY-MM-DD'));
                    $('#editEndTime').val(moment(event.end).format('HH:mm'));
                    $('#eventId').val(event.id);
                    $('#eventModal').modal('show');
                }
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
                url: 'updateevent.php',
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
                                'Erreur lors de la mise à jour de l\'événement : ' + result.message,
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
                        url: 'deleteevent.php',
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
                                        'Erreur lors de la suppression de l\'événement : ' + result.message,
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

</body>
</html>