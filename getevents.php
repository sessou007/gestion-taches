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
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

$userId = $_SESSION['user_id']; // Remplacez ceci par la façon dont vous stockez l'ID de l'utilisateur

$sql = "SELECT id, titre AS title, debut AS start, fin AS end, description, alarme, url, status, raison, button_disabled, event_name 
        FROM evenement 
        WHERE DELETED = 0 AND user_id = :user_id"; // Ajoutez un filtre pour l'ID de l'utilisateur
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT); // Bind l'ID de l'utilisateur

try {
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérification et formatage des événements
    foreach ($events as &$event) {
        $event['allDay'] = false; // Indique que l'événement n'est pas toute la journée
    }

    // Retourner les événements sous forme de JSON
    header('Content-Type: application/json');
    echo json_encode($events);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la récupération des événements : ' . $e->getMessage()]);
    exit();
}


?>