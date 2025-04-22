<?php
session_start();
require 'config.php'; // Configuration de la base de données

if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirection vers la page de connexion
    header("Location: login.php");
    exit;
}

// Vérification de la connexion
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT active FROM Users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['active'] == 0) { 
    // Si le compte est désactivé ou introuvable
    session_destroy();
    header("Location: login.php?error=compte_desactive");
    exit;
}

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['status' => 'error', 'message' => 'Erreur de connexion : ' . $e->getMessage()]));
}

// Vérification du poste de directeur
$directeurId = null;
$stmt = $pdo->prepare("SELECT id FROM postes WHERE poste_name = 'directeur'");
if ($stmt->execute()) {
    $postv = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($postv) {
        $directeurId = $postv['id'];
    }
}

$isDirector = false;
if (isset($_SESSION['poste_id']) && $directeurId !== null) {
    $isDirector = ($_SESSION['poste_id'] == $directeurId);
}

$utilisateurs = [];

if ($isDirector) {
    // Récupérer la direction et le département du directeur
    $stmt = $conn->prepare("SELECT department_id FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $director = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($director && isset($director['department_id'])) {
        $departmentId = $director['department_id'];
        
        // Récupérer les IDs des postes à exclure
        $posteIdsToExclude = [];
        
        $stmt = $pdo->prepare("SELECT id FROM postes WHERE poste_name = 'directeur'");
        if ($stmt->execute()) {
            $postdr = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($postdr) {
                $posteIdsToExclude[] = $postdr['id'];
            }
        }
        
        $stmt = $pdo->prepare("SELECT id FROM postes WHERE poste_name = 'supra_admin'");
        if ($stmt->execute()) {
            $postsup = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($postsup) {
                $posteIdsToExclude[] = $postsup['id'];
            }
        }
        
        // Construire la requête en fonction des postes à exclure
        if (!empty($posteIdsToExclude)) {
            $placeholders = implode(',', array_fill(0, count($posteIdsToExclude), '?'));
            $requete = "SELECT * FROM users WHERE department_id = ? AND poste_id NOT IN ($placeholders)";
            $params = array_merge([$departmentId], $posteIdsToExclude);
            
            $stmt = $conn->prepare($requete);
            $stmt->execute($params);
            $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs - Ministère</title>
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
        }
        
        .mas-header {
            background-color: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        
        .mas-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .mas-table thead th {
            background-color: var(--mas-primary);
            color: white;
            padding: 1rem;
            font-weight: 500;
        }
        
        .mas-table tbody tr:nth-child(even) {
            background-color: rgba(0, 95, 135, 0.03);
        }
        
        .mas-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .btn-mas {
            background-color: var(--mas-primary);
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            font-weight: 500;
        }
        
        .btn-mas:hover {
            background-color: #004a6a;
            color: white;
        }
        
        .btn-dashboard {
            background-color: #17a2b8;
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
            
            .mas-table td::before {
                content: attr(data-label);
                font-weight: bold;
                display: inline-block;
                width: 120px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar-mas fixed-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="images/logo_masm.png" alt="Logo Bénin">
        </div>

        <div class="sidebar-menu">
            <a href="dashboard.php" class="sidebar-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
            </a>

            <a href="tac.php" class="sidebar-link">
                <i class="fas fa-tasks"></i>
                <span>Gestion des Tâches</span>
            </a>

            <a href="rapport.php" class="sidebar-link">
                <i class="fas fa-chart-bar"></i>
                <span>Rapports</span>
            </a>

            <a href="listedirect.php" class="sidebar-link active">
                <i class="fas fa-users"></i>
                <span>Gestion des Utilisateurs</span>
            </a>
        </div>
    </div>

    <style>
        /* Sidebar fixe */
        .fixed-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width, 280px);
            background: linear-gradient(180deg, var(--mas-primary, #005f87), #004a6a);
            color: white;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow-y: auto;
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

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 4px solid var(--mas-secondary, #e67e22);
        }

        .sidebar-link i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        /* Ajustement du contenu principal */
        .main-content {
            margin-left: var(--sidebar-width, 280px);
            padding: 2rem;
        }

        /* Responsiveness pour petits écrans */
        @media (max-width: 992px) {
            .fixed-sidebar {
                position: absolute;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .fixed-sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    <!-- Main Content -->
    <div class="main-content">
        <div class="mas-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-users me-2"></i> Liste des agents</h1>
                <button class="btn btn-outline-primary d-lg-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <div class="report-card bg-white p-4 rounded shadow-sm mb-4">
            <div class="table-responsive">
                <table class="mas-table">
                    <thead>
                        <tr>
                            <th>Identifiant</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $utilisateur): 
                            if (isset($utilisateur['poste_id'])) {
                                $stmt = $conn->prepare("SELECT poste_name FROM postes WHERE id = ?");
                                $stmt->execute([$utilisateur['poste_id']]);
                                $pst = $stmt->fetch(PDO::FETCH_ASSOC);
                            }
                        ?>
                            <tr>
                                <td data-label="Identifiant"><?php echo htmlspecialchars($utilisateur['user_id'] ?? ''); ?></td>
                                <td data-label="Nom"><?php echo htmlspecialchars($utilisateur['last_name'] ?? ''); ?></td>
                                <td data-label="Prénom"><?php echo htmlspecialchars($utilisateur['first_name'] ?? ''); ?></td>
                                <td data-label="Email"><?php echo htmlspecialchars($utilisateur['email'] ?? ''); ?></td>
                                <td data-label="Rôle"><?php echo htmlspecialchars($pst['poste_name'] ?? 'Non défini'); ?></td>
                                <td data-label="Actions">
                                    <?php if (isset($utilisateur['user_id'])): ?>
                                        <a href="voir.php?user_id=<?php echo urlencode($utilisateur['user_id']); ?>" class="btn btn-mas btn-dashboard">
                                            <i class="fas fa-eye me-1"></i> Tableau de Bord
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($utilisateurs)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">Aucun utilisateur trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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