<?php
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

// Vérifiez si l'utilisateur est connecté
session_start(); // Démarrer la session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté.']);
    exit();
}

$userId = $_SESSION['user_id']; // Récupérer l'ID utilisateur depuis la session

// Vérifier si l'ID de l'événement est présent
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Vérifier si la tâche est assignée (existe dans task_assignments)
    $checkAssignmentQuery = "SELECT COUNT(*) FROM task_assignments WHERE task_id = :task_id";
    $checkAssignmentStmt = $conn->prepare($checkAssignmentQuery);
    $checkAssignmentStmt->bindParam(':task_id', $id);
    $checkAssignmentStmt->execute();
    $isAssigned = $checkAssignmentStmt->fetchColumn();

    if ($isAssigned > 0) {
        // La tâche est assignée, empêcher la suppression
        echo json_encode(['status' => 'error', 'message' => 'Vous ne pouvez pas supprimer une tâche assignée.']);
        exit();
    }

    // Si la tâche n'est pas assignée, permettre la suppression
    $sql = "DELETE FROM evenement WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    // Exécution de la requête
    try {
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Événement supprimé avec succès.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression de l\'événement.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID de l\'événement manquant.']);
}
?>