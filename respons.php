<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tâches</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #1e40af;
            --accent-color: #ef4444;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
        }
        
        /* Navbar stylisée */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 2rem;
            border-radius: 9999px;
            margin: 1.5rem auto;
            max-width: 1400px;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--dark-color) !important;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
        }
        
        .navbar-brand img {
            height: 50px;
            margin-right: 15px;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link {
            color: var(--dark-color) !important;
            padding: 0.8rem 1.5rem;
            margin: 0 0.2rem;
            border-radius: 9999px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        
        .navbar-nav .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
        }
        
        .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover::before {
            width: 60%;
        }
        
        .navbar-nav .nav-link.active {
            color: var(--primary-color) !important;
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }
        
        /* Notification Bell */
        #notification-container {
            position: relative;
            margin-left: 1rem;
        }
        
        #notification-bell {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            position: relative;
            color: var(--dark-color);
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        #notification-bell:hover {
            color: var(--primary-color);
            background-color: rgba(59, 130, 246, 0.1);
            transform: rotate(15deg);
        }
        
        #notification-count {
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            padding: 0.2rem 0.5rem;
            font-size: 0.7rem;
            position: absolute;
            top: -5px;
            right: -5px;
            font-weight: bold;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        /* Notification Dropdown */
        #notification-dropdown {
            display: none;
            position: absolute;
            right: 0;
            background: white;
            border: none;
            width: 350px;
            max-height: 500px;
            overflow-y: auto;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            z-index: 1000;
            color: var(--dark-color);
            font-family: 'Poppins', sans-serif;
            margin-top: 0.5rem;
        }
        
        .notification-header {
            padding: 1rem;
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-header .badge {
            background: var(--accent-color);
        }
        
        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            color: var(--dark-color);
            transition: all 0.3s ease;
            display: flex;
            align-items: flex-start;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item.unread {
            background: #f8fafc;
            font-weight: 500;
        }
        
        .notification-item:hover {
            background-color: #f1f5f9;
            cursor: pointer;
            transform: translateX(5px);
        }
        
        .notification-icon {
            margin-right: 1rem;
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-time {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.3rem;
        }
        
        .mark-all-read {
            padding: 0.5rem 1rem;
            background: white;
            color: var(--primary-color);
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .mark-all-read:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            margin-left: 1rem;
        }
        
        .dropdown-toggle {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .dropdown-toggle:hover {
            transform: translateY(-2px);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.5rem;
            border: 2px solid var(--primary-color);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            overflow: hidden;
            min-width: 200px;
            padding: 0.5rem 0;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .dropdown-item i {
            margin-right: 10px;
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: white !important;
        }
        
        .dropdown-item:hover i {
            color: white;
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
            border-top: 1px solid #e2e8f0;
        }
        
        /* Mobile Menu */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            font-size: 1.5rem;
            color: var(--dark-color);
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        /* Responsive design */
        @media (max-width: 992px) {
            .navbar {
                padding: 0.8rem 1.5rem;
                border-radius: 12px;
            }
            
            .navbar-nav {
                margin-top: 1rem;
            }
            
            .nav-item {
                margin-bottom: 0.5rem;
            }
            
            #notification-dropdown {
                right: -50px;
                width: 300px;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.3rem;
            }
            
            .navbar-brand img {
                height: 40px;
            }
            
            .dropdown-menu {
                position: static;
                border: none;
                box-shadow: none;
            }
        }











        .navbar {
            background: linear-gradient(135deg, var(--light-color) 0%, var(--light-color) 100%);
            box-shadow: 0 4px 20px rgba(58, 12, 163, 0.15);
            padding: 0.8rem 2rem;
        }
        
        .navbar-brand {
         
            font-weight: 600;
            font-size: 1.6rem;
            
            letter-spacing: 0.5px;
        }
        
        
        
       
        
    </style>
</head>
<body>
    <!-- Navbar intégrée -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Logo et Nom -->
            <a class="navbar-brand" href="dashboard.php">
                <img src="images/logo-masm.png" alt="Logo Ministère" />
                <span>Gestion des Tâches</span>
            </a>

            <!-- Bouton menu burger (Responsive) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Contenu de la navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Liens principaux -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Tableau de Bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tac.php">
                            <i class="fas fa-tasks"></i> Tâches
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rapport.php">
                            <i class="fas fa-file-alt"></i> Rapports
                        </a>
                    </li>
                    <!-- Notification Bell -->
                    <li class="nav-item">
                        <div id="notification-container">
                            <button id="notification-bell">
                                <i class="fas fa-bell"></i>
                                <span id="notification-count">0</span>
                            </button>
                            <div id="notification-dropdown">
                                <div class="notification-header">
                                    <span>Notifications <span class="badge" id="notification-badge">0</span></span>
                                    <button class="mark-all-read">Tout marquer comme lu</button>
                                </div>
                                <div id="notification-list">
                                    <!-- Les notifications seront ajoutées ici dynamiquement -->
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <!-- Menu utilisateur avec profil et déconnexion -->
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                    <a href="#" class="text-dark dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
    <i class="fas fa-user-circle fa-lg"></i>
</a>
                        </a>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="profiles.php">
                                    <i class="fas fa-user-circle"></i> Mon Profil
                                </a>
                            </li>
                             
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Élément audio pour le bip sonore -->
    <audio id="notification-sound" src="/t/sounds/mixkit-bell-notification-933.mp3"></audio>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fonction pour afficher ou masquer la liste des notifications
            function toggleNotifications() {
                const dropdown = $('#notification-dropdown');
                dropdown.toggle();
                if (dropdown.is(':visible')) {
                    fetchNotifications();
                }
            }

            // Attacher l'événement au bouton
            $('#notification-bell').click(function(e) {
                e.stopPropagation();
                toggleNotifications();
            });

            // Fermer le dropdown quand on clique ailleurs
            $(document).click(function() {
                $('#notification-dropdown').hide();
            });

            // Empêcher la fermeture quand on clique dans le dropdown
            $('#notification-dropdown').click(function(e) {
                e.stopPropagation();
            });

            // Fonction pour récupérer les notifications
            function fetchNotifications() {
                $.ajax({
                    url: 'fetch_notifications.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            console.error("Erreur du serveur: ", data.error);
                            return;
                        }

                        const previousCount = parseInt($('#notification-count').text(), 10);
                        const newCount = data.count;

                        if (newCount > previousCount) {
                            const notificationSound = document.getElementById('notification-sound');
                            notificationSound.play();
                            
                            $('#notification-bell').addClass('animate-bell');
                            setTimeout(() => {
                                $('#notification-bell').removeClass('animate-bell');
                            }, 1000);
                        }

                        updateNotificationCount(newCount);
                        updateNotificationList(data.notifications);
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur lors de la récupération des notifications: ", error);
                    }
                });
            }

            // Fonction pour mettre à jour le compteur de notifications
            function updateNotificationCount(count) {
                $('#notification-count').text(count);
                $('#notification-badge').text(count);
                
                if (count > 0) {
                    $('#notification-count').addClass('has-notifications');
                } else {
                    $('#notification-count').removeClass('has-notifications');
                }
            }

            // Fonction pour mettre à jour la liste des notifications
            function updateNotificationList(notifications) {
                const notificationList = $('#notification-list');
                notificationList.empty();

                if (notifications.length === 0) {
                    notificationList.append(
                        '<div class="notification-item text-center text-muted py-3">Aucune notification</div>'
                    );
                    return;
                }

                notifications.forEach(notification => {
                    const iconClass = notification.read ? 'far fa-bell' : 'fas fa-bell';
                    const notificationItem = $(`
                        <div class="notification-item ${notification.read ? '' : 'unread'}" data-id="${notification.id}">
                            <div class="notification-icon">
                                <i class="${iconClass}"></i>
                            </div>
                            <div class="notification-content">
                                <div>${notification.message}</div>
                                <div class="notification-time">${notification.time}</div>
                            </div>
                        </div>
                    `).click(function() {
                        markNotificationAsRead(notification.id);
                    });

                    notificationList.append(notificationItem);
                });
            }

            // Fonction pour marquer une notification comme lue
            function markNotificationAsRead(notificationId) {
                $.ajax({
                    url: 'mark_notification_as_read.php',
                    method: 'POST',
                    data: { id: notificationId },
                    success: function() {
                        fetchNotifications();
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur lors du marquage de la notification comme lue: ", error);
                    }
                });
            }

            // Marquer toutes les notifications comme lues
            $('.mark-all-read').click(function() {
                $.ajax({
                    url: 'mark_all_notifications_as_read.php',
                    method: 'POST',
                    success: function() {
                        fetchNotifications();
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur lors du marquage de toutes les notifications comme lues: ", error);
                    }
                });
            });

            // Recharge les notifications toutes les 30 secondes
            setInterval(fetchNotifications, 30000);

            // Charge les notifications au chargement de la page
            fetchNotifications();

            // Effet de scroll sur la navbar
            $(window).scroll(function() {
                if ($(this).scrollTop() > 10) {
                    $('.navbar').addClass('scrolled');
                } else {
                    $('.navbar').removeClass('scrolled');
                }
            });
        });
    </script>
</body>
</html>