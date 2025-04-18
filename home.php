<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$notification = '';

// Vérification de l'état du compte
$stmt = $pdo->prepare("SELECT active FROM Users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupération des départements et postes
$departments = $pdo->query("SELECT department_id, department_name FROM departments")->fetchAll(PDO::FETCH_ASSOC);
$postes = $pdo->query("SELECT id, poste_name FROM postes")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gestion des utilisateurs
    if (isset($_POST['activate_user_id'])) {
        $stmt = $pdo->prepare("UPDATE Users SET active = 1 WHERE user_id = ?");
        $stmt->execute([$_POST['activate_user_id']]);
        $notification = "Compte activé avec succès.";
    }

    if (isset($_POST['deactivate_user_id'])) {
        $stmt = $pdo->prepare("UPDATE Users SET active = 0 WHERE user_id = ?");
        $stmt->execute([$_POST['deactivate_user_id']]);
        $notification = "Compte désactivé avec succès.";
    }

    if (isset($_POST['delete_user_id'])) {
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->execute([$_POST['delete_user_id']]);
        $notification = "Compte supprimé avec succès.";
    }

    // Gestion des départements et postes
    if (isset($_POST['department_name']) && !empty($_POST['department_name'])) {
        $stmt = $pdo->prepare("INSERT INTO departments (department_name) VALUES (?)");
        $stmt->execute([trim($_POST['department_name'])]);
        $notification = "Nouveau département ajouté avec succès.";
    }
    
    if (isset($_POST['poste_name']) && !empty($_POST['poste_name'])) {
        $stmt = $pdo->prepare("INSERT INTO postes (poste_name) VALUES (?)");
        $stmt->execute([trim($_POST['poste_name'])]);
        $notification = "Nouveau poste ajouté avec succès.";
    }

    // Modification des postes et départements
    if (isset($_POST['modify_poste_id']) && isset($_POST['new_poste_id'])) {
        $stmt = $pdo->prepare("UPDATE users SET poste_id = :poste_id WHERE user_id = :user_id");
        $stmt->execute([
            ':poste_id' => intval($_POST['new_poste_id']),
            ':user_id' => intval($_POST['modify_poste_id'])
        ]);
    }

    if (isset($_POST['modify_department_id']) && isset($_POST['new_department_id'])) {
        $stmt = $pdo->prepare("UPDATE users SET department_id = :department_id WHERE user_id = :user_id");
        $stmt->execute([
            ':department_id' => intval($_POST['new_department_id']),
            ':user_id' => intval($_POST['modify_department_id'])
        ]);
    }
}

// Récupération des utilisateurs
$users = $pdo->query("
    SELECT u.*, d.department_name, p.poste_name 
    FROM Users u 
    LEFT JOIN departments d ON u.department_id = d.department_id
    LEFT JOIN postes p ON u.poste_id = p.id
")->fetchAll(PDO::FETCH_ASSOC);

if (!$user || $user['active'] == 0) { 
    session_destroy();
    header("Location: login.php?error=compte_desactive");
    exit;
}
?>

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
            background-color: var(--secondary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1.4rem;
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
        
        /* Contenu principal */
        .container {
            max-width: 1200px;
            margin-top: 30px;
            padding-bottom: 50px;
        }
        
        /* Cartes de statistiques */
        .stats-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
            height: 100%;
            border-left: 5px solid;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card .card-body {
            padding: 20px;
        }
        
        .stats-card .icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }
        
        .stats-card .count {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-card .label {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .card-directions {
            border-left-color: var(--primary-color);
        }
        
        .card-postes {
            border-left-color: var(--success-color);
        }
        
        .card-agents {
            border-left-color: var(--warning-color);
        }
        
        .card-admins {
            border-left-color: var(--danger-color);
        }
        
        /* Tableau des utilisateurs */
        .users-table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .users-table th {
            background-color: var(--secondary-color);
            color: var(--text-light);
            font-weight: 500;
            position: sticky;
            top: 0;
        }
        
        .users-table td, .users-table th {
            vertical-align: middle;
            padding: 15px;
        }
        
        .users-table tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .users-table tr:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        /* Badges */
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .badge-active {
            background-color: rgba(39, 174, 96, 0.2);
            color: var(--success-color);
        }
        
        .badge-inactive {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--danger-color);
        }
        
        /* ID utilisateur */
        .user-id {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: var(--secondary-color);
            background-color: rgba(44, 62, 80, 0.1);
            padding: 3px 8px;
            border-radius: 4px;
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
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
                <img src="images/armo.png" alt="Logo" />
                <span>Tableau de Bord</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="listdepart.php">
                            <i class="fas fa-building me-1"></i> Directions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listposte.php">
                            <i class="fas fa-user-tie me-1"></i> Postes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-users-cog me-1"></i> Utilisateurs
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php if ($notification): ?>
        <div class="notification">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($notification); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <!-- Titre -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="text-center mb-4">
                    <i class="fas fa-tachometer-alt me-2"></i> Tableau de Bord Administratif
                </h1>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <?php
            // Récupération des statistiques
            $count_directions = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();
            $count_postes = $pdo->query("SELECT COUNT(*) FROM postes")->fetchColumn();
            $count_agents = $pdo->query("SELECT COUNT(*) FROM Users")->fetchColumn();
            $count_admins = $pdo->query("SELECT COUNT(*) FROM Users WHERE poste_id IN (SELECT id FROM postes WHERE poste_name LIKE '%admin%')")->fetchColumn();
            ?>
            
            <!-- Carte Directions -->
            <div class="col-md-6 col-lg-3">
                <div class="card stats-card card-directions">
                    <div class="card-body text-center">
                        <div class="icon text-primary">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="count"><?php echo $count_directions; ?></div>
                        <div class="label">Directions</div>
                    </div>
                </div>
            </div>
            
            <!-- Carte Postes -->
            <div class="col-md-6 col-lg-3">
                <div class="card stats-card card-postes">
                    <div class="card-body text-center">
                        <div class="icon text-success">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="count"><?php echo $count_postes; ?></div>
                        <div class="label">Postes</div>
                    </div>
                </div>
            </div>
            
            <!-- Carte Agents -->
            <div class="col-md-6 col-lg-3">
                <div class="card stats-card card-agents">
                    <div class="card-body text-center">
                        <div class="icon text-warning">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="count"><?php echo $count_agents; ?></div>
                        <div class="label">Agents</div>
                    </div>
                </div>
            </div>
            
            <!-- Carte Admins -->
            <div class="col-md-6 col-lg-3">
                <div class="card stats-card card-admins">
                    <div class="card-body text-center">
                        <div class="icon text-danger">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="count"><?php echo $count_admins; ?></div>
                        <div class="label">Administrateurs</div>
                    </div>
                </div>
            </div>
        </div>

        
        <!-- Tableau des utilisateurs -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-users me-2"></i> Gestion des Utilisateurs
                <span class="badge bg-light text-dark float-end"><?php echo count($users); ?> utilisateurs</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table users-table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom Complet</th>
                                <th>Email</th>
                                <th>Poste</th>
                                <th>Direction</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><span class="user-id"><?php echo $user['user_id']; ?></span></td>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <form method="post" class="d-flex">
                                        <input type="hidden" name="modify_poste_id" value="<?php echo $user['user_id']; ?>">
                                        <select name="new_poste_id" class="form-select form-select-sm me-2" required>
                                            <?php foreach ($postes as $poste): ?>
                                                <option value="<?php echo $poste['id']; ?>" <?php echo ($user['poste_id'] == $poste['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($poste['poste_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form method="post" class="d-flex">
                                        <input type="hidden" name="modify_department_id" value="<?php echo $user['user_id']; ?>">
                                        <select name="new_department_id" class="form-select form-select-sm me-2" required>
                                            <?php foreach ($departments as $department): ?>
                                                <option value="<?php echo $department['department_id']; ?>" <?php echo ($user['department_id'] == $department['department_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($department['department_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="badge-status <?php echo ($user['active']) ? 'badge-active' : 'badge-inactive'; ?>">
                                        <?php echo ($user['active']) ? 'Actif' : 'Inactif'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <?php if ($user['active']): ?>
                                                <li>
                                                    <form method="post" onsubmit="return confirm('Désactiver cet utilisateur ?');">
                                                        <input type="hidden" name="deactivate_user_id" value="<?php echo $user['user_id']; ?>">
                                                        <button type="submit" class="dropdown-item text-warning">
                                                            <i class="fas fa-toggle-off me-2"></i> Désactiver
                                                        </button>
                                                    </form>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <form method="post" onsubmit="return confirm('Activer cet utilisateur ?');">
                                                        <input type="hidden" name="activate_user_id" value="<?php echo $user['user_id']; ?>">
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="fas fa-toggle-on me-2"></i> Activer
                                                        </button>
                                                    </form>
                                                </li>
                                            <?php endif; ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="post" onsubmit="return confirm('Supprimer définitivement cet utilisateur ?');">
                                                    <input type="hidden" name="delete_user_id" value="<?php echo $user['user_id']; ?>">
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash-alt me-2"></i> Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        // Fermer automatiquement les notifications après 5 secondes
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    <?php include 'footer.php'; ?>
</body>
</html>