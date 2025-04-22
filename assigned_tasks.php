<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$dbname = 'gestion_tache';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Récupérer les tâches assignées par l'utilisateur connecté
$sql = "
    SELECT e.id, e.titre, e.description, e.debut, e.fin, e.status, u.first_name, u.last_name 
    FROM task_assignments ta
    JOIN evenement e ON ta.task_id = e.id
    JOIN users u ON ta.user_id = u.user_id
    WHERE e.assigned_by = :user_id
";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tâches Assignées - Ministère</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --mas-primary: #005f87;
            --mas-secondary: #e67e22;
            --mas-success: #27ae60;
            --mas-danger: #e74c3c;
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
        
        .badge-effectue {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--mas-success);
        }
        
        .badge-non-effectue {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--mas-danger);
        }
        
        .badge-en-cours {
            background-color: rgba(230, 126, 34, 0.1);
            color: var(--mas-secondary);
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
                <span>Tâches</span>
            </a>

            <a href="assigned_tasks.php" class="sidebar-link active">
                <i class="fas fa-user-check"></i>
                <span>Tâches Assignées</span>
            </a>

            <a href="rapport.php" class="sidebar-link">
                <i class="fas fa-chart-bar"></i>
                <span>Rapports</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="mas-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-user-check me-2"></i> Tâches Assignées</h1>
                <button class="btn btn-outline-primary d-lg-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <div class="report-card bg-white p-4 rounded shadow-sm">
            <div class="table-responsive">
                <table class="mas-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Statut</th>
                            <th>Assigné à</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): 
                            $statusClass = '';
                            if ($task['status'] == 'effectué') $statusClass = 'badge-effectue';
                            if ($task['status'] == 'non effectué') $statusClass = 'badge-non-effectue';
                            if ($task['status'] == 'en cours') $statusClass = 'badge-en-cours';
                        ?>
                            <tr>
                                <td data-label="Titre"><?php echo htmlspecialchars($task['titre']); ?></td>
                                <td data-label="Description"><?php echo htmlspecialchars($task['description']); ?></td>
                                <td data-label="Date début"><?php echo htmlspecialchars($task['debut']); ?></td>
                                <td data-label="Date fin"><?php echo htmlspecialchars($task['fin']); ?></td>
                                <td data-label="Statut">
                                    <span class="badge rounded-pill <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($task['status'] ?? ''); ?>
                                    </span>
                                </td>
                                <td data-label="Assigné à"><?php echo htmlspecialchars($task['first_name'] . ' ' . $task['last_name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($tasks)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">Aucune tâche assignée trouvée</td>
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