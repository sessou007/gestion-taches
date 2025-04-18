<?php
session_start(); // Démarrer la session pour accéder aux variables de session

// Inclure la connexion à la base de données
$host = 'localhost';
$dbname = 'gestion_tache';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}

if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas connecté, redirection vers la page de connexion
    header("Location: login.php");
    exit; // Terminez le script après la redirection
}

// Vérifiez si l'utilisateur est connecté et récupérez son ID
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

include 'config.php';
$userId = $_SESSION['user_id']; // Récupérer l'ID utilisateur depuis la session

// Récupérer les événements non supprimés de la table 'evenement'
$query = "SELECT id, titre, description, debut, fin, status, button_disabled 
          FROM evenement 
          WHERE DELETED = 0 AND user_id = :user_id"; // Filtrage des événements non supprimés et appartenant à l'utilisateur
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les résultats

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT active FROM Users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) { 
    // Si le compte est désactivé ou introuvable
    session_destroy(); // Détruire la session
    header("Location: login.php?error=compte_desactive");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Gestion des Tâches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css" rel="stylesheet">
    
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
        
        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            border: none;
            font-size: 0.9rem;
        }
        
        .btn-action i {
            margin-right: 6px;
        }
        
        .btn-validate {
            background-color: var(--mas-success);
            color: white;
        }
        
        .btn-validate:hover {
            background-color: #219653;
            transform: translateY(-2px);
        }
        
        .btn-reject {
            background-color: var(--mas-danger);
            color: white;
        }
        
        .btn-reject:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        .btn-details {
            background-color: var(--mas-primary);
            color: white;
        }
        
        .btn-details:hover {
            background-color: #004a6a;
            transform: translateY(-2px);
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
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
                <a href="tac.php" class="sidebar-link active">
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
            
            <div class="sidebar-item mt-4">
                <a href="profiles.php" class="sidebar-link">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="mas-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1><i class="fas fa-tasks me-2"></i> Gestion des Tâches</h1>
                <button class="btn btn-outline-light d-lg-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        
        <!-- Tableau des tâches -->
        <div class="report-card bg-white p-4 rounded shadow-sm">
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
                        <?php foreach ($result as $row): ?>
                        <tr>
                            <td data-label="Titre"><?= htmlspecialchars($row['titre'] ?? '') ?></td>
                            <td data-label="Description"><?= htmlspecialchars($row['description'] ?? '') ?></td>
                            <td data-label="Date de Début"><?= date('d/m/Y H:i', strtotime($row['debut'])) ?></td>
                            <td data-label="Date de Fin"><?= date('d/m/Y H:i', strtotime($row['fin'])) ?></td>
                            <td data-label="Statut">
                                <?php if (isset($row['status'])): ?>
                                    <?php if ($row['status'] === 'effectué'): ?>
                                        <span class="badge rounded-pill badge-effectue">
                                            <i class="fas fa-check-circle me-1"></i> Effectué
                                        </span>
                                    <?php elseif ($row['status'] === 'non effectué'): ?>
                                        <span class="badge rounded-pill badge-non-effectue">
                                            <i class="fas fa-times-circle me-1"></i> Non Effectué
                                        </span>
                                    <?php else: ?>
                                        <div class="action-buttons">
                                            <button class="btn btn-validate btn-action" 
                                                    id="effectueButton_<?= $row['id'] ?>" 
                                                    onclick="updateStatus(<?= $row['id'] ?>, 'effectue')" 
                                                    <?= $row['button_disabled'] ? 'disabled' : '' ?>>
                                                <i class="fas fa-check"></i> Effectué
                                            </button>
                                            <button class="btn btn-reject btn-action" 
                                                    id="nonEffectueButton_<?= $row['id'] ?>" 
                                                    onclick="requestReason(<?= $row['id'] ?>)" 
                                                    <?= $row['button_disabled'] ? 'disabled' : '' ?>>
                                                <i class="fas fa-times"></i> Non effectué
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Toggle sidebar sur mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
        
        function updateStatus(id, status, reason = null) {
            Swal.fire({
                title: 'Confirmation',
                text: `Voulez-vous vraiment marquer cette tâche comme ${status === 'effectue' ? 'effectuée' : 'non effectuée'} ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#2ec4b6',
                cancelButtonColor: '#f72585',
                backdrop: 'rgba(0,0,0,0.4)'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'update_status.php',
                        type: 'POST',
                        data: { id: id, status: status, reason: reason },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Traitement en cours',
                                html: 'Veuillez patienter...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: function(response) {
                            Swal.close();
                            const data = JSON.parse(response);
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Succès !',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#2ec4b6'
                                }).then(() => {
                                    updateButtonDisplay(id, status);
                                });
                            } else {
                                Swal.fire('Erreur', data.message || 'Erreur lors de la mise à jour', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Erreur', 'Une erreur est survenue lors de la communication avec le serveur', 'error');
                        }
                    });
                }
            });
        }

        function updateButtonDisplay(id, status) {
            $('#effectueButton_' + id).hide();
            $('#nonEffectueButton_' + id).hide();
            if (status === 'effectue') {
                $('#effectueButton_' + id).parent().html(
                    '<span class="badge rounded-pill badge-effectue">' +
                    '<i class="fas fa-check-circle me-1"></i> Effectué</span>'
                );
            } else {
                $('#nonEffectueButton_' + id).parent().html(
                    '<span class="badge rounded-pill badge-non-effectue">' +
                    '<i class="fas fa-times-circle me-1"></i> Non Effectué</span>'
                );
            }
        }

        function requestReason(id) {
            Swal.fire({
                title: 'Raison de non-effectuation',
                input: 'textarea',
                inputPlaceholder: 'Veuillez expliquer pourquoi cette tâche n\'a pas pu être effectuée...',
                inputAttributes: {
                    'aria-label': 'Entrez la raison ici'
                },
                showCancelButton: true,
                confirmButtonText: 'Confirmer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#f72585',
                cancelButtonColor: '#4361ee',
                inputValidator: (value) => {
                    if (!value) return 'Vous devez fournir une raison!';
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStatus(id, 'non effectué', result.value);
                }
            });
        }
    </script>
</body>
</html>