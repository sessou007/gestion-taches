<?php
session_start(); // Démarrer la session pour accéder aux variables de session

// Connexion à la base de données
$host = 'localhost';
$dbname = 'gestion_tache';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit();
}

// Vérifiez si l'utilisateur est connecté et récupérez son ID
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté.']);
    exit();
}

$userId = $_SESSION['user_id']; // ID de l'utilisateur connecté

// Vérifier si les données POST sont présentes
if (isset($_POST['id']) && isset($_POST['titre']) && isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['description'])) {
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $description = $_POST['description'];

    // Vérifier si la tâche est assignée (existe dans task_assignments)
    $checkAssignmentQuery = "SELECT COUNT(*) FROM task_assignments WHERE task_id = :task_id";
    $checkAssignmentStmt = $conn->prepare($checkAssignmentQuery);
    $checkAssignmentStmt->bindParam(':task_id', $id);
    $checkAssignmentStmt->execute();
    $isAssigned = $checkAssignmentStmt->fetchColumn();

    if ($isAssigned > 0) {
        // La tâche est assignée, empêcher la modification
        echo json_encode(['status' => 'error', 'message' => 'Vous ne pouvez pas modifier une tâche assignée.']);
        exit();
    }

    // Si la tâche n'est pas assignée, permettre la modification
    $sql_evenement = "UPDATE evenement SET titre = :titre, debut = :debut, fin = :fin, description = :description WHERE id = :id";
    $stmt_evenement = $conn->prepare($sql_evenement);

    // Liaison des paramètres
    $stmt_evenement->bindParam(':id', $id);
    $stmt_evenement->bindParam(':titre', $titre);
    $stmt_evenement->bindParam(':debut', $debut);
    $stmt_evenement->bindParam(':fin', $fin);
    $stmt_evenement->bindParam(':description', $description);

    try {
        // Exécution de la requête pour 'evenement'
        $stmt_evenement->execute();
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour de l\'événement : ' . $e->getMessage()]);
        exit();
    }

    // Vérification du succès de la mise à jour
    if ($stmt_evenement->rowCount() > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status'  => 'Aucune modification effectuée.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes.']);
}
?>