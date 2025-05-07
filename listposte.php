<?php
// Appel au fichier connect
include('config.php');

// Initialiser la variable de message
$message = '';

// Vérifier si un formulaire d'ajout a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['poste_name'])) {
    $nomPoste = trim($_POST['poste_name']);
    
    if (!empty($nomPoste)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO postes (poste_name) VALUES (?)");
            $stmt->execute([$nomPoste]);
            $message = '<div class="alert alert-success">Poste enregistré avec succès</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Erreur lors de l\'enregistrement : ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $message = '<div class="alert alert-warning">Veuillez saisir un nom de poste</div>';
    }
}

// Récupérer les postes
$donnees = [];
try {
    $requete = "SELECT * FROM postes ORDER BY id";
    $reponse = $pdo->query($requete);
    $donnees = $reponse->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = '<div class="alert alert-danger">Erreur de base de données : ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des postes</title>
    <!-- Bootstrap CSS -->
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
            font-weight: 600;
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
        
        .page-header {
             
            color: black;
            padding: 30px 0;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .page-header h1 {
            font-weight: 700;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }
        
        .card-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .btn-add {
            background-color: var(--success-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-add:hover {
            background-color: #219653;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: white;
        }
        
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .table-custom th {
            background-color: var(--secondary-color);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 500;
        }
        
        .table-custom td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }
        
        .table-custom tr:last-child td {
            border-bottom: none;
        }
        
        .table-custom tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .id-badge {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: var(--secondary-color);
            background-color: rgba(44, 62, 80, 0.1);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .action-group {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
        }
        
        .edit-btn {
            background-color: var(--primary-color);
        }
        
        .delete-btn {
            background-color: var(--accent-color);
        }
        
        .action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .card-container {
                padding: 20px;
            }
            
            .table-custom th, 
            .table-custom td {
                padding: 12px 10px;
            }
            
            .action-group {
                flex-direction: column;
                gap: 8px;
            }
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
                        <a class="nav-link " href="home.php">
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
    
    <!-- En-tête amélioré -->
    <header class="page-header">
        <div class="container text-center">
            <h1><i class="fas fa-user-tie me-2"></i>Gestion des Postes</h1>
            
        </div>
    </header>
    
    <div class="container">
        <!-- Afficher le message de succès/erreur -->
        <?php if (!empty($message)) echo $message; ?>
        
        <div class="card-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <!-- Formulaire d'ajout -->
                <form method="post" class="d-flex align-items-center" style="gap: 10px;">
                    <input type="text" name="poste_name" class="form-control" placeholder="Nom du poste" required>
                    <button type="submit" class="btn btn-add">
                        <i class="fas fa-plus-circle"></i> Ajouter
                    </button>
                </form>
                <span class="badge bg-secondary">
                    <?php echo !empty($donnees) ? count($donnees) : '0'; ?> poste(s) enregistré(s)
                </span>
            </div>
            
            <?php if (!empty($donnees)): ?>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Libellé</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($donnees as $ligne): ?>
                        <tr>
                            <td><span class="id-badge"><?php echo htmlspecialchars($ligne['id']); ?></span></td>
                            <td><?php echo htmlspecialchars($ligne['poste_name']); ?></td>
                            <td>
                                <div class="action-group">
                                    <a href="modifepost.php?id=<?php echo $ligne['id']; ?>" 
                                       class="action-btn edit-btn" 
                                       title="Modifier">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="suppreposte.php?id=<?php echo $ligne['id']; ?>" 
                                       class="action-btn delete-btn" 
                                       title="Supprimer"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce poste ? Cette action est irréversible.');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>Aucun poste enregistré pour le moment.
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>