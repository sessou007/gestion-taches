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
    $id = $_GET['id'];

    // Préparer la requête de suppression
    $requete = "DELETE FROM postes WHERE id = :id";
    $stmt = $pdo->prepare($requete);
    
    // Exécuter la requête
    if ($stmt->execute([':id' => $id])) {
        // Rediriger vers la liste des départements avec un message de succès
        header("Location: listposte.php?message=Department deleted successfully");
    } else {
        // Rediriger en cas d'erreur
        header("Location: listposte.php?error=Error deleting department");
    }
} else {
    // Rediriger en cas d'ID non fourni
    header("Location: listposte.php?error=No department ID provided");
}
exit();
?>