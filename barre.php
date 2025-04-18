<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tâches</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Style général */
        body {
            overflow-x: hidden; /* Désactive le défilement horizontal */
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Barre de navigation horizontale */
        .navbar {
            position: fixed; /* Fixer la barre de navigation */
            width: 100%; /* Largeur de 100% */
            top: 0; /* Positionner en haut */
            z-index: 1000; /* S'assurer qu'elle reste au-dessus des autres éléments */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: green; /* Couleur pour la barre horizontale */
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

        .sidebar-sticky {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
        }

        .sidebar img {
            width: 100%;
            max-width: 150px;
            margin-bottom: 20px;
        }

        .sidebar .nav {
            flex-direction: column;
        }

        .sidebar .nav-item {
            margin-bottom: 10px;
        }

        .sidebar .nav-link {
            color: white;
            font-size: 1rem;
            padding: 10px 0;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: color 0.3s;
        }

        .sidebar .nav-link:hover {
            color: #28a745; 
        }

        .sidebar .nav-link .icon-img {
            width: 30px;  
            height: 30px;
            margin-right: 10px;
        }

        /* Style pour petits écrans */
        @media (max-width: 768px) {
            .sidebar {
                width: 150px; /* Réduire la largeur sur petits écrans */
                padding: 10px; /* Réduire le padding sur petits écrans */
            }

            .navbar {
                justify-content: space-between;
                padding: 0.5rem 1rem;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .navbar-nav {
                margin-left: 0;
            }

            .icon-img {
                width: 25px;
                height: 25px;
            }
        }

        /* Style pour grands écrans */
        @media (min-width: 769px) {
            .container-fluid {
                margin-left: 200px; 
            }
        }

        /* Marges pour éviter que le contenu soit caché sous la barre de navigation */
        .content {
            margin-top: 56px; /* Ajuster en fonction de la hauteur de la barre de navigation */
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
                    <a class="dropdown-item" href="profiles.php">Profil</a>
                    <a class="dropdown-item" href="index.php">Déconnexion</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- Barre de navigation verticale -->
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 sidebar">
            <div class="sidebar-sticky">
                <img src="images/armo.png" alt="Logo" class="img-fluid my-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <img src="images/images.png" class="icon-img"> Tableau de Bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tasks.php">
                            <img src="images/taches.webp" class="icon-img"> Tâches
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
                </ul>
            </div>

             <!-- Slider ajouté ici -->
        <div class="slider-container">
            <label for="task-progress" style="color:white;">Progression des Tâches</label>
            <input type="range" class="slider" id="task-progress" name="progress" min="0" max="100" value="50">
        </div>
        </nav>

        <main class="content col-md-10">
            <!-- Votre contenu principal ici -->
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
