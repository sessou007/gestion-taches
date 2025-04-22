<?php
session_start();
include 'config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fonction pour obtenir les rapports basés sur la période choisie
function getRapportPeriode($periodeType) {
    global $pdo;
    $userId = $_SESSION['user_id'];

    $query = "";
    switch ($periodeType) {
        case 'journalier':
            $query = "SELECT DISTINCT e.titre, e.description, e.debut, e.fin, e.status, e.raison
                      FROM evenement e
                      WHERE e.DELETED = 0 AND e.user_id = :user_id AND DATE(e.debut) = CURDATE()";
            break;
        case 'mensuel':
            $query = "SELECT DISTINCT e.titre, e.description, e.debut, e.fin, e.status, e.raison
                      FROM evenement e
                      WHERE e.DELETED = 0 AND e.user_id = :user_id 
                      AND YEAR(e.debut) = YEAR(CURDATE()) AND MONTH(e.debut) = MONTH(CURDATE())";
            break;
        case 'annuel':
            $query = "SELECT DISTINCT e.titre, e.description, e.debut, e.fin, e.status, e.raison
                      FROM evenement e
                      WHERE e.DELETED = 0 AND e.user_id = :user_id 
                      AND YEAR(e.debut) = YEAR(CURDATE())";
            break;
    }

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour obtenir les données statistiques
function getRapportData($periodeType) {
    global $pdo;
    $userId = $_SESSION['user_id'];
    
    $query = "";
    switch ($periodeType) {
        case 'journalier':
            $query = "SELECT status, COUNT(*) as count 
                      FROM evenement 
                      WHERE DELETED = 0 AND user_id = :user_id AND DATE(debut) = CURDATE()
                      GROUP BY status";
            break;
        case 'mensuel':
            $query = "SELECT status, COUNT(*) as count 
                      FROM evenement 
                      WHERE DELETED = 0 AND user_id = :user_id 
                      AND YEAR(debut) = YEAR(CURDATE()) AND MONTH(debut) = MONTH(CURDATE())
                      GROUP BY status";
            break;
        case 'annuel':
            $query = "SELECT status, COUNT(*) as count 
                      FROM evenement 
                      WHERE DELETED = 0 AND user_id = :user_id 
                      AND YEAR(debut) = YEAR(CURDATE())
                      GROUP BY status";
            break;
    }

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Vérification compte utilisateur
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT active FROM Users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) { 
    session_destroy();
    header("Location: login.php?error=compte_desactive");
    exit;
}

$periodeType = $_POST['periode_type'] ?? 'mensuel';
$rapportData = getRapportData($periodeType);
$tasks = getRapportPeriode($periodeType);

// Préparation des données pour le graphique
$total = 0;
$effectue = 0;
$nonEffectue = 0;
$enCours = 0;

foreach ($rapportData as $data) {
    $total += $data['count'];
    if ($data['status'] == 'effectué') $effectue = $data['count'];
    if ($data['status'] == 'non effectué') $nonEffectue = $data['count'];
    if ($data['status'] == 'en cours') $enCours = $data['count'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports - Ministère des Affaires Sociales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css" rel="stylesheet">
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
        
        .sidebar-link i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
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
        
        .mas-header h1 {
            color: var(--mas-primary);
            font-weight: 700;
        }
        
        .report-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
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
        
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .stats-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--mas-primary);
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
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            .mas-table, .mas-table * {
                visibility: visible;
            }
            .mas-table {
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
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

            <a href="rapport.php" class="sidebar-link active">
                <i class="fas fa-chart-bar"></i>
                <span>Rapports</span>
            </a>

            <a href="profiles.php" class="sidebar-link mt-4">
                <i class="fas fa-user"></i>
                <span>Profil</span>
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
                <h1><i class="fas fa-chart-pie me-2"></i> Rapports d'Activités</h1>
                <button class="btn btn-outline-primary d-lg-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <!-- Formulaire de sélection -->
        <div class="report-card">
            <form method="POST" action="">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <label for="periode_type" class="form-label">Période du rapport</label>
                        <select name="periode_type" id="periode_type" class="form-select">
                            <option value="journalier" <?= $periodeType == 'journalier' ? 'selected' : '' ?>>Journalier</option>
                            <option value="mensuel" <?= $periodeType == 'mensuel' ? 'selected' : '' ?>>Mensuel</option>
                            <option value="annuel" <?= $periodeType == 'annuel' ? 'selected' : '' ?>>Annuel</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-mas w-100">
                            <i class="fas fa-sync-alt me-2"></i>Générer
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Statistiques -->
        <div class="row">
            <?php
            $pourcentageEffectue = $total > 0 ? round(($effectue / $total) * 100) : 0;
            $pourcentageNonEffectue = $total > 0 ? round(($nonEffectue / $total) * 100) : 0;
            $pourcentageEnCours = $total > 0 ? round(($enCours / $total) * 100) : 0;
            ?>
            
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <h5>Tâches Effectuées</h5>
                    <div class="stats-value"><?= $pourcentageEffectue ?>%</div>
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: <?= $pourcentageEffectue ?>%"></div>
                    </div>
                    <p class="mt-2 mb-0"><?= $effectue ?> tâches sur <?= $total ?></p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <h5>Tâches Non Effectuées</h5>
                    <div class="stats-value"><?= $pourcentageNonEffectue ?>%</div>
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar bg-danger" style="width: <?= $pourcentageNonEffectue ?>%"></div>
                    </div>
                    <p class="mt-2 mb-0"><?= $nonEffectue ?> tâches sur <?= $total ?></p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="stats-card text-center">
                    <h5>Tâches En Cours</h5>
                    <div class="stats-value"><?= $pourcentageEnCours ?>%</div>
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar bg-warning" style="width: <?= $pourcentageEnCours ?>%"></div>
                    </div>
                    <p class="mt-2 mb-0"><?= $enCours ?> tâches sur <?= $total ?></p>
                </div>
            </div>
        </div>
        
        <!-- Graphique en courbe -->
        <div class="report-card">
            <h4 class="mb-4"><i class="fas fa-chart-line me-2"></i> Évolution des Tâches</h4>
            <div class="chart-container">
                <canvas id="taskChart"></canvas>
            </div>
        </div>
        
        <!-- Tableau détaillé -->
        <div class="report-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-table me-2"></i> Détail des Tâches</h4>
            </div>
            
            <div class="table-responsive">
                <table class="mas-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Statut</th>
                            <th>Raison</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($tasks)) {
                            echo '<tr><td colspan="6" class="text-center py-4">Aucune tâche trouvée pour cette période</td></tr>';
                        } else {
                            foreach ($tasks as $task) {
                                $statusClass = '';
                                if ($task['status'] == 'effectué') $statusClass = 'badge-effectue';
                                if ($task['status'] == 'non effectué') $statusClass = 'badge-non-effectue';
                                if ($task['status'] == 'en cours') $statusClass = 'badge-en-cours';
                                
                                echo '<tr>
                                        <td>'.htmlspecialchars($task['titre']).'</td>
                                        <td>'.htmlspecialchars($task['description']).'</td>
                                        <td>'.date('d/m/Y H:i', strtotime($task['debut'])).'</td>
                                        <td>'.($task['fin'] ? date('d/m/Y H:i', strtotime($task['fin'])) : '-').'</td>
                                        <td><span class="badge rounded-pill '.$statusClass.'">'.$task['status'].'</span></td>
                                        <td>'.($task['status'] == 'non effectué' ? htmlspecialchars($task['raison']) : '-').'</td>
                                      </tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <button class="btn btn-mas" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Imprimer le tableau
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script>
        // Toggle sidebar mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
        
        // Graphique en courbe
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('taskChart').getContext('2d');
            const taskChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Effectuées', 'Non Effectuées', 'En Cours'],
                    datasets: [{
                        label: 'Répartition des Tâches',
                        data: [<?= $effectue ?>, <?= $nonEffectue ?>, <?= $enCours ?>],
                        backgroundColor: [
                            'rgba(39, 174, 96, 0.2)',
                            'rgba(231, 76, 60, 0.2)',
                            'rgba(230, 126, 34, 0.2)'
                        ],
                        borderColor: [
                            'rgba(39, 174, 96, 1)',
                            'rgba(231, 76, 60, 1)',
                            'rgba(230, 126, 34, 1)'
                        ],
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: 'white',
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Répartition des Tâches (<?= ucfirst($periodeType) ?>)',
                            font: {
                                size: 16
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>