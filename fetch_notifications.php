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

$userId = $_SESSION['user_id'];

// Récupérer les notifications non lues
$sql = "SELECT id, message, read_status FROM notifications WHERE user_id = :user_id AND read_status = 0 ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Renvoyer les données au format JSON
header('Content-Type: application/json');
echo json_encode([
    'count' => count($notifications),
    'notifications' => $notifications
]);