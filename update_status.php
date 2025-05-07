<?php
session_start();

$host = 'localhost';
$dbname = 'gestion_tache';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';
$reason = isset($_POST['reason']) ? htmlspecialchars($_POST['reason']) : null;
$actions = isset($_POST['actions']) ? htmlspecialchars($_POST['actions']) : null;

try {
    if ($status === 'effectue') {
        $query = "UPDATE evenement 
                  SET status = 'effectué', 
                      button_disabled = 1, 
                      termine = 1, 
                      actions_menes = :actions 
                  WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':actions', $actions);
        
    } elseif ($status === 'non effectué') {
        $query = "UPDATE evenement 
                  SET status = 'non effectué', 
                      raison = :raison, 
                      button_disabled = 1, 
                      termine = 1 
                  WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':raison', $reason);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Statut mis à jour avec succès']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur base de données : ' . $e->getMessage()]);
}