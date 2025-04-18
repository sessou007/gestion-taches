<?php
session_start();
require 'config.php'; // Inclusion de la configuration de la base de données

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

// Vérifiez que department_id est défini
if (!isset($_SESSION['department_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID de département non défini']);
    exit();
}

$department_id = $_SESSION['department_id']; // Récupérer l'ID de département
$searchTerm = '%' . $_GET['search'] . '%'; // Ajout des caractères de pourcentage pour la recherche partielle

// Préparer la requête de recherche
$stmt = $pdo->prepare("
    SELECT u.user_id, u.first_name, u.last_name, u.email, p.poste_name
    FROM users u
    JOIN postes p ON u.poste_id = p.id
    WHERE (u.first_name LIKE :search OR u.last_name LIKE :search OR p.poste_name LIKE :search)
    AND u.department_id = :department_id
");

$stmt->execute([':search' => $searchTerm, ':department_id' => $department_id]);

// Récupérer les résultats
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Afficher les résultats
if ($utilisateurs) {
    foreach ($utilisateurs as $utilisateur) {
        echo '<div class="result-item" data-user-id="' . htmlspecialchars($utilisateur['user_id']) . '">'
            . '<a href="voir.php?user_id=' . urlencode($utilisateur['user_id']) . '" class="btn btn-dashboard">'
            . htmlspecialchars($utilisateur['first_name']) . ' ' . htmlspecialchars($utilisateur['last_name'])
            . '</a> (' . htmlspecialchars($utilisateur['poste_name']) . ')
            </div>';
    }
} else {
    echo '<div class="result-item">Aucun utilisateur trouvé.</div>';
}
?>

<!-- Style CSS -->
<style>
.btn {
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    background-color: #007bff; /* Couleur de base */
    margin-right: 10px; /* Espace entre les boutons */
}

.btn-dashboard {
    background-color: #17a2b8; /* Couleur différente pour le tableau de bord */
}

.results-container {
    border: 1px solid #ddd;
    border-radius: 5px;
    max-height: 200px;
    overflow-y: auto;
    position: absolute;
    background-color: white;
    z-index: 1000; /* Pour superposer le conteneur de résultats */
    display: none; /* Masquer par défaut */
}

.result-item {
    padding: 10px;
    cursor: pointer;
}

.result-item:hover {
    background-color: #f2f2f2;
}
</style>