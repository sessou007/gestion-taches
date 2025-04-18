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
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --success-color: #28a745;
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }
        
        /* Navbar stylisée */
        .navbar {
            background-color: var(--secondary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1.4rem;
            color: var(--light-color) !important;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 12px;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover img {
            transform: rotate(5deg);
        }
        
        .navbar-nav .nav-link {
            color: var(--light-color) !important;
            padding: 0.8rem 1.2rem;
            margin: 0 0.2rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
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
            background-color: var(--success-color);
            transition: width 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover::before {
            width: 80%;
        }
        
        .navbar-nav .nav-link.active {
            color: var(--light-color) !important;
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        /* Menu burger */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Responsive design */
        @media (max-width: 992px) {
            .navbar-nav {
                margin-top: 1rem;
            }
            
            .nav-item {
                margin-bottom: 0.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .navbar-brand img {
                height: 35px;
            }
            
            .navbar-nav .nav-link {
                padding: 0.8rem 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar intégrée -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <!-- Logo et Nom -->
            <a class="navbar-brand" href="#">
                <img src="images/armo.png" alt="Armoirie du Bénin" />
                <span>Gestion des Tâches</span>
            </a>

            <!-- Bouton menu burger (Responsive) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Contenu de la navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Liens principaux -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listdepart.php">
                            <i class="fas fa-building"></i> Directions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listposte.php">
                            <i class="fas fa-user-tie"></i> Postes
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>