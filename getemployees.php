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
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

$userId = $_SESSION['user_id'];

// Récupérer la direction de l'utilisateur connecté
$sql = "SELECT department_id FROM users WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non trouvé']);
    exit();
}

$departmentId = $user['department_id'];

// Récupérer les employés de la même direction
$sql = "SELECT user_id, first_name, last_name FROM users WHERE department_id = :department_id AND user_id != :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($employees);
?>