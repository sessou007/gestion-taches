<?php
// Appel au fichier connect
include('config.php');

// Récupérer l'ID du département à partir de l'URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($id === null) {
    echo "ID non spécifié.";
    exit;
}

// Préparer la requête pour récupérer le département spécifique
$requete = "SELECT poste_name FROM postes WHERE id = :id";
$stmt = $pdo->prepare($requete);
$stmt->execute(['id' => $id]);

// Récupérer les données de la requête
$postes = $stmt->fetch();

if (!$postes) {
    echo "Aucun poste trouvé pour cet ID.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleform.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   
     <!-- Navbar Élégante -->
 <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
         <!-- Logo et Nom -->
         <a class="navbar-brand" href="dashboard.php">
                <img src="images/logo-masm.png" alt="Logo Ministère" />
                <span>Gestion des Tâches</span>
            </a>

            <!-- Bouton menu burger (Responsive) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
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
                    <li class="nav-item">
                        <a class="nav-link active" href="home.php">
                            <i class="fas fa-users-cog"></i> Utilisateurs
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" class="text-white dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fa-lg me-2"></i>
                          
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
</head>

<body>
<style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --dark-color: #343a40;
            --text-color: #333;
            --text-light: #f8f9fa;
            --info-color: #17a2b8;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: var(--text-color);
            overflow-x: hidden;
        }
        
        /* Navbar stylisée */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), #1a252f);
            box-shadow: 0 4px 20px rgba(58, 12, 163, 0.15);
            padding: 0.8rem 2rem;
            
            
            
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 1000;
            font-size: 1.6rem;
            
            letter-spacing: 0.5px;
            color: var(--text-light) !important;
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
        
        
        
        .navbar-nav .nav-link {
            color: var(--text-light) !important;
            padding: 0.8rem 1.2rem;
            margin: 0 0.2rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
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
        
                  /* En-tête de Page */
                  .page-header {
            background: white;
            padding: 40px 0;
            margin-bottom: 40px;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: var(--box-shadow);
            background-image: linear-gradient(to right, var(--primary-light) 1px, transparent 1px),
                              linear-gradient(to bottom, var(--primary-light) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        
        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }
        
        .page-header .lead {
            color: #6c757d;
            font-weight: 400;
        }
        
        /* Formulaire de Modification */
        .edit-form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 40px;
            transition: var(--transition);
        }
        
        .edit-form-container:hover {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 10px;
            display: block;
        }
        
        .form-control {
            padding: 14px 16px;
            border-radius: var(--border-radius);
            border: 1px solid #e0e0e0;
            transition: var(--transition);
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }
        
        .btn-submit:hover {
            background-color: #3a56d4;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(67, 97, 238, 0.4);
        }
        
        .btn-submit i {
            margin-right: 8px;
        }
        
        .btn-cancel {
            background-color: white;
            color: var(--danger-color);
            padding: 14px 28px;
            border: 1px solid var(--danger-color);
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-cancel:hover {
            background-color: var(--danger-color);
            color: white;
            transform: translateY(-3px);
        }
        
        .btn-cancel i {
            margin-right: 8px;
        }
        
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.8rem 1rem;
            }
            
            .edit-form-container {
                padding: 30px 20px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-submit, .btn-cancel {
                width: 100%;
                justify-content: center;
            }
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
            .container {
                padding: 15px;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .navbar-brand img {
                height: 35px;
            }
            
            .stats-card .icon {
                font-size: 2rem;
            }
            
            .stats-card .count {
                font-size: 1.5rem;
            }
            
            .users-table td, .users-table th {
                padding: 12px 8px;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .stats-card {
                margin-bottom: 15px;
            }
            
            .users-table {
                font-size: 0.85rem;
            }
        }
    </style>
   
   
<body>
    
   <!-- En-tête de Page -->
   <br><br>
        <div class="container text-center">
            <h1>
                <i class="fas fa-user-tie me-2"></i>Modifier un Poste
            </h1>
            <p class="lead mb-0">Mise à jour des informations du poste</p>
        </div>
     <br><br>

    <!-- Formulaire de Modification -->
    <div class="container">
        <div class="edit-form-container">
            <form action="validermodifeposte.php" method="post">
                <div class="mb-4">
                    <label for="poste_name" class="form-label">Nom du poste</label>
                    <input type="text" class="form-control" id="poste_name" 
                           name="s_poste_name" value="<?= htmlspecialchars($postes['poste_name']) ?>" required>
                </div>
                
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                
                <div class="action-buttons">
                    <a href="listposte.php" class="btn-cancel">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>