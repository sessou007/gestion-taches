<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion des utilisateurs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
               :root {
            --primary-color:rgb(98, 100, 107);
            --primary-light: #eef2ff;
            --secondary-color:rgb(16, 12, 24);
            --accent-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color:rgb(110, 177, 197);
            --warning-color: #f8961e;
            --danger-color: #ef233c;
            --border-radius: 12px;
            --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f5f7ff;
            color: var(--dark-color);
            line-height: 1.6;
        }
        
        /* Navbar Élégante */
        .navbar {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            box-shadow: 0 4px 20px rgba(58, 12, 163, 0.15);
            padding: 0.8rem 2rem;
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.6rem;
            color: white !important;
            letter-spacing: 0.5px;
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 12px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.8rem 1.2rem;
            margin: 0 0.2rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
            position: relative;
            color: rgba(255,255,255,0.9) !important;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.15);
            transform: translateY(-2px);
            color: white !important;
        }
        
        .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            font-weight: 600;
        }
        
        .nav-link i {
            margin-right: 8px;
        }
        
    </style>
</head>
<body>
<!-- Navbar Élégante -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="">
                <img src="images/armo.png" alt="Logo" />
                <span>Tableau de Bord</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="listdepart.php">
                            <i class="fas fa-building"></i> Directions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listposte.php">
                            <i class="fas fa-user-tie"></i> Postes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">
                            <i class="fas fa-users-cog"></i> Utilisateurs
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" class="text-white dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profiles.php"><i class="fas fa-user me-2"></i> Profil</a></li>
        
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
<br><br>
    
    <?php include 'footer.php'; ?>
</body>
</html>