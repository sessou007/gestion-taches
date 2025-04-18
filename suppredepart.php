<?php
// Démarrer la session
session_start();
require 'config.php'; // Inclure la configuration de la base de données

// Vérifier si l'utilisateur est connecté (optionnel)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si l'ID du département est passé en paramètre
if (isset($_GET['id'])) {
    $department_id = $_GET['id'];

    // Préparer la requête de suppression
    $requete = "DELETE FROM departments WHERE department_id = :id";
    $stmt = $pdo->prepare($requete);
    
    // Exécuter la requête
    if ($stmt->execute([':id' => $department_id])) {
        // Rediriger vers la liste des départements avec un message de succès
        header("Location: listdepart.php?message=Department deleted successfully");
    } else {
        // Rediriger en cas d'erreur
        header("Location: listdepart.php?error=Error deleting department");
    }
} else {
    // Rediriger en cas d'ID non fourni
    header("Location: listdepart.php?error=No department ID provided");
}
exit();
?>