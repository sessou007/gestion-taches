<?php
session_start();
require 'config.php';

// Vérifiez si l'utilisateur est connecté et récupérez son ID
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['user_id'])) {
    $userid = $_GET['user_id'];
}

$userId = $_SESSION['user_id'];

// Récupérer les événements non supprimés de la table 'evenement'
$sql = "SELECT * FROM evenement WHERE user_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $userid, PDO::PARAM_INT);
$stmt->execute();
$userevents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparation des données pour les statistiques
$total = count($userevents);
$effectue = 0;
$nonEffectue = 0;
$enCours = 0;

foreach ($userevents as $event) {
    if ($event['status'] == 'effectué') $effectue++;
    if ($event['status'] == 'non effectué') $nonEffectue++;
    if ($event['status'] == 'en cours') $enCours++;
}

// Vérifier l'état de l'utilisateur
$stmt = $pdo->prepare("SELECT active FROM Users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <title>Tâches - Ministère des Affaires Sociales</title>
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
    <div class="sidebar-mas" id="sidebar">
        <div class="sidebar-brand">
            <img src="images/logo_masm.png" alt="Logo Ministère">
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
                <span>Rapports </span>
            </a>
            
            <a href="profiles.php" class="sidebar-link mt-4">
                <i class="fas fa-cog"></i>
                <span>Profiles</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="mas-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-tasks me-2"></i> Liste des Tâches</h1>
                <button class="btn btn-outline-primary d-lg-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
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
        
        <!-- Tableau des tâches -->
        <div class="report-card">
            <div class="table-responsive">
                <table class="mas-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Date de Début</th>
                            <th>Date de Fin</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userevents as $userevent): 
                            $statusClass = '';
                            if ($userevent['status'] === 'effectué') $statusClass = 'badge-effectue';
                            elseif ($userevent['status'] === 'non effectué') $statusClass = 'badge-non-effectue';
                            elseif ($userevent['status'] === 'en cours') $statusClass = 'badge-en-cours';
                        ?>
                            <tr>
                                <td data-label="Titre"><?php echo htmlspecialchars($userevent['titre'] ?? ''); ?></td>
                                <td data-label="Description"><?php echo htmlspecialchars($userevent['description'] ?? ''); ?></td>
                                <td data-label="Date Début"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($userevent['debut']))); ?></td>
                                <td data-label="Date Fin"><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($userevent['fin']))); ?></td>
                                <td data-label="Statut">
                                    <span class="badge rounded-pill <?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($userevent['status'] ?? ''); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($userevents)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">Aucune tâche trouvée</td>
                            </tr>
                        <?php endif; ?>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            text: 'Répartition des Tâches',
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

        function updateStatus(taskId, status) {
            Swal.fire({
                title: 'Confirmer',
                text: 'Êtes-vous sûr de vouloir marquer cette tâche comme ' + (status === 'effectué' ? 'effectuée' : 'non effectuée') + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'update_status.php',
                        method: 'POST',
                        data: { id: taskId, status: status },
                        success: function(response) {
                            Swal.fire('Succès!', 'Le statut de la tâche a été mis à jour.', 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire('Erreur!', 'Une erreur s\'est produite lors de la mise à jour du statut.', 'error');
                        }
                    });
                }
            });
        }

        function requestReason(taskId) {
            Swal.fire({
                title: 'Raison non effectuée',
                input: 'textarea',
                inputPlaceholder: 'Entrez la raison...',
                showCancelButton: true,
                confirmButtonText: 'Soumettre',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('Veuillez entrer une raison');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'submit_reason.php',
                        method: 'POST',
                        data: { id: taskId, reason: result.value },
                        success: function(response) {
                            Swal.fire('Succès!', 'Votre raison a été soumise.', 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire('Erreur!', 'Une erreur s\'est produite lors de la soumission de la raison.', 'error');
                        }
                    });
                }
            });
        }
    </script>
</body>
</html> 