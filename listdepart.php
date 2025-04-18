<?php
// Appel au fichier connect
include('config.php');

// Initialiser la variable de message
$message = '';

// Vérifier si un formulaire d'ajout a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['department_name'])) {
    $nomDirection = trim($_POST['department_name']);
    
    if (!empty($nomDirection)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO departments (department_name) VALUES (?)");
            $stmt->execute([$nomDirection]);
            $message = '<div class="alert alert-success">Direction enregistrée avec succès</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert alert-danger">Erreur lors de l\'enregistrement : ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        $message = '<div class="alert alert-warning">Veuillez saisir un nom de direction</div>';
    }
}

// Récupérer les directions
$donnees = [];
try {
    $requete = "SELECT * FROM departments ORDER BY department_id";
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
    <title>Gestion des directions</title>
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
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
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
    <?php include('response.php'); ?>
    
    <!-- En-tête amélioré -->
    <header class="page-header">
        <div class="container text-center">
            <h1><i class="fas fa-building me-2"></i>Gestion des Directions</h1>
            <p class="lead mb-0">Administration des différentes directions de l'organisation</p>
        </div>
    </header>
    
    <div class="container">
        <!-- Afficher le message de succès/erreur -->
        <?php if (!empty($message)) echo $message; ?>
        
        <div class="card-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <!-- Formulaire d'ajout -->
                <form method="post" class="d-flex align-items-center" style="gap: 10px;">
                    <input type="text" name="department_name" class="form-control" placeholder="Nom de la direction" required>
                    <button type="submit" class="btn btn-add">
                        <i class="fas fa-plus-circle"></i> Ajouter
                    </button>
                </form>
                <span class="badge bg-secondary">
                    <?php echo !empty($donnees) ? count($donnees) : '0'; ?> direction(s) enregistrée(s)
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
                            <td><span class="id-badge"><?php echo htmlspecialchars($ligne['department_id']); ?></span></td>
                            <td><?php echo htmlspecialchars($ligne['department_name']); ?></td>
                            <td>
                                <div class="action-group">
                                    <a href="modifedepart.php?id=<?php echo $ligne['department_id']; ?>" 
                                       class="action-btn edit-btn" 
                                       title="Modifier">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="suppredepart.php?id=<?php echo $ligne['department_id']; ?>" 
                                       class="action-btn delete-btn" 
                                       title="Supprimer"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette direction ? Cette action est irréversible.');">
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
                <i class="fas fa-info-circle me-2"></i>Aucune direction enregistrée pour le moment.
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>