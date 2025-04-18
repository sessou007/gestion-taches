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
    echo json_encode(['status' => 'error', 'message' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit();
}

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté.']);
    exit();
}

$userId = $_SESSION['user_id'];

// Récupérer les notifications
$sql = "SELECT id, message, is_read FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

try {
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ajouter une clé pour indiquer si chaque notification est lue ou non
    foreach ($notifications as &$notification) {
        $notification['unread'] = $notification['is_read'] == 0; // 0 = non lu, 1 = lu
    }

    echo json_encode($notifications);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la récupération des notifications : ' . $e->getMessage()]);
}
?>