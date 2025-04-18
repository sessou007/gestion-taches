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
    echo json_encode(['error' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]);
    exit();
}

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté.']);
    exit();
}

// Récupérer l'ID de la notification à marquer comme lue
if (!isset($_POST['id'])) {
    echo json_encode(['error' => 'ID de notification manquant.']);
    exit();
}

$notificationId = $_POST['id'];

// Mettre à jour la notification
$sql = "UPDATE notifications SET read_status = 1 WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $notificationId, PDO::PARAM_INT);
$stmt->execute();

// Renvoyer une réponse JSON
header('Content-Type: application/json');
echo json_encode(['success' => true]);