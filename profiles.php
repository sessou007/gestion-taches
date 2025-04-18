<?php     
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Inclure la connexion à la base de données
$host = 'localhost';
$dbname = 'gestion_tache';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Initialiser toutes les variables avec des valeurs par défaut
$user_id = $_SESSION['user_id'] ?? null;
$prenom = '';
$nom = '';
$email = '';
$active = false;
$poste_name = 'Non défini';
$department_name = 'Non défini';
$message = "";

// Récupération des informations utilisateur depuis la base de données
if ($user_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $prenom = $user['first_name'] ?? '';
            $nom = $user['last_name'] ?? '';
            $email = $user['email'] ?? '';
            $active = $user['active'] ?? false;

            // Récupérer le poste si poste_id est défini
            if (!empty($user['poste_id'])) {
                $stmt = $pdo->prepare("SELECT poste_name FROM postes WHERE id = ?");
                $stmt->execute([$user['poste_id']]);
                $poste = $stmt->fetch(PDO::FETCH_ASSOC);
                $poste_name = $poste['poste_name'] ?? 'Non défini';
            }

            // Récupérer le département si department_id est défini
            if (!empty($user['department_id'])) {
                $stmt = $pdo->prepare("SELECT department_name FROM departments WHERE department_id = ?");
                $stmt->execute([$user['department_id']]);
                $department = $stmt->fetch(PDO::FETCH_ASSOC);
                $department_name = $department['department_name'] ?? 'Non défini';
            }
        } else {
            header('Location: login.php');
            exit();
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la récupération des données utilisateur: " . $e->getMessage();
    }
}

// Gestion de la mise à jour des informations
if (isset($_POST['update'])) {
    $nouveau_email = trim($_POST['email'] ?? '');
    $nouveau_prenom = trim($_POST['prenom'] ?? '');
    $nouveau_nom = trim($_POST['nom'] ?? '');

    if ($user_id) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET email = :email, first_name = :prenom, last_name = :nom WHERE user_id = :user_id");
            $stmt->bindParam(':email', $nouveau_email);
            $stmt->bindParam(':prenom', $nouveau_prenom);
            $stmt->bindParam(':nom', $nouveau_nom);
            $stmt->bindParam(':user_id', $user_id);

            if ($stmt->execute()) {
                $_SESSION['email'] = $nouveau_email;
                $_SESSION['prenom'] = $nouveau_prenom;
                $_SESSION['nom'] = $nouveau_nom;
                $message = "Informations mises à jour avec succès.";
            } else {
                $message = "Une erreur est survenue lors de la mise à jour.";
            }
        } catch (PDOException $e) {
            $message = "Erreur de base de données: " . $e->getMessage();
        }
    }
}

// Vérification de l'état actif du compte
if ($user_id) {
    try {
        $stmt = $pdo->prepare("SELECT active FROM Users WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || ($user && $user['active'] == 0)) {
            session_destroy();
            header("Location: login.php?error=compte_desactive");
            exit;
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la vérification du compte: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Gestion des Tâches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --mas-primary: #005f87;
            --mas-secondary: #e67e22;
            --mas-light: #ecf0f1;
            --mas-dark: #2c3e50;
            --sidebar-width: 280px;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            color: var(--mas-dark);
        }
        
        .sidebar-mas {
            width: var(--sidebar-width);
            min-height: 100vh;
            position: fixed;
            background: linear-gradient(180deg, var(--mas-primary), #004a6a);
            color: white;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand img {
            height: 60px;
            margin-bottom: 10px;
        }
        
        .sidebar-menu {
            padding: 1.5rem 0;
        }
        
        .sidebar-item {
            margin-bottom: 5px;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 4px solid var(--mas-secondary);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }
        
        .profile-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--mas-light);
            margin-right: 1.5rem;
        }
        
        .profile-title {
            color: var(--mas-primary);
            margin-bottom: 0.5rem;
        }
        
        .profile-subtitle {
            color: var(--mas-secondary);
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        
        .profile-info {
            color: var(--mas-dark);
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }
        
        .info-label {
            font-weight: 500;
            color: var(--mas-primary);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--mas-primary);
        }
        
        .form-control {
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.75rem;
            border-radius: 8px;
        }
        
        .form-control:focus {
            border-color: var(--mas-primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 95, 135, 0.1);
        }
        
        .btn-primary {
            background-color: var(--mas-primary);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #004a6a;
        }
        
        .btn-outline-primary {
            color: var(--mas-primary);
            border-color: var(--mas-primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--mas-primary);
        }
        
        .alert-success {
            background-color: rgba(39, 174, 96, 0.1);
            border-color: rgba(39, 174, 96, 0.3);
            color: #27ae60;
        }
        
        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            border-color: rgba(231, 76, 60, 0.3);
            color: #e74c3c;
        }
        
        @media (max-width: 992px) {
            .sidebar-mas {
                transform: translateX(-100%);
                z-index: 1000;
            }
            
            .sidebar-mas.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-avatar {
                margin-right: 0;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar-mas" id="sidebar">
        <div class="sidebar-brand">
            <img src="images/logo_masm.png" alt="Logo">
        </div>
        
        <div class="sidebar-menu">
            <div class="sidebar-item">
                <a href="dashboard.php" class="sidebar-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a>
            </div>
            
            <div class="sidebar-item">
                <a href="tac.php" class="sidebar-link">
                    <i class="fas fa-tasks"></i>
                    <span>Gestion des Tâches</span>
                </a>
            </div>
            
            <div class="sidebar-item">
                <a href="rapport.php" class="sidebar-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Rapports</span>
                </a>
            </div>
            
            <div class="sidebar-item">
                <a href="profiles.php" class="sidebar-link active">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-primary"><i class="fas fa-user-circle me-2"></i> Mon Profile</h1>
            <button class="btn btn-outline-primary d-lg-none" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <div class="profile-card">
            <div class="profile-header">
                <img src="https://www.w3schools.com/w3images/avatar2.png" alt="Photo de profil" class="profile-avatar">
                <div>
                    <h2 class="profile-title"><?php echo htmlspecialchars($nom . ' ' . $prenom); ?></h2>
                    <p class="profile-subtitle"><?php echo htmlspecialchars($poste_name); ?></p>
                    <p class="profile-info"><span class="info-label">Direction:</span> <?php echo htmlspecialchars($department_name); ?></p>
                    <p class="profile-info"><span class="info-label">Email:</span> <?php echo htmlspecialchars($email); ?></p>
                    <p class="profile-info"><span class="info-label">ID Utilisateur:</span> <?php echo htmlspecialchars($user_id); ?></p>
                </div>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo strpos($message, 'succès') !== false ? 'alert-success' : 'alert-danger'; ?> mb-4">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" name="update" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                    
                    <a href="dashboard.php" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
</body>
</html>