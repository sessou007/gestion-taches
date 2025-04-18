<?php
session_start();




// Inclure la connexion à la base de données
$host = 'localhost';
$dbname = 'gestion_tache';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}
include 'config.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

// Récupérer les données de la requête POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';
$reason = isset($_POST['reason']) ? $_POST['reason'] : null; // Récupérer la raison si fournie

// Préparer la requête de mise à jour
if ($status === 'effectue') {
    // Mettre à jour le statut à 'effectué', désactiver le bouton et mettre termine à 1
    $query = "UPDATE evenement SET status = 'effectué', button_disabled = 1, termine = 1 WHERE id = :id";
} elseif ($status === 'non effectué') {
    // Mettre à jour le statut à 'non effectué', mettre termine à 1, et mettre la raison
    $query = "UPDATE evenement SET status = 'non effectué', raison = :raison, button_disabled = 1, termine = 1 WHERE id = :id";
}

// Préparer et exécuter la mise à jour
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $id);

// Lier la raison uniquement si le statut est 'non effectué'
if ($status === 'non effectué') {
    $stmt->bindParam(':raison', $reason);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Statut mis à jour avec succès']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour du statut']);
}


?>