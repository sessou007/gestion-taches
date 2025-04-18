<?php
include('config.php');

// Récupération des données
$department_name = $_POST['s_department_name'];
$department_id = $_POST['department_id']; // Récupérer l'ID du département

// Contrôle de la saisie
if (empty($department_name)) {
    // Message d'erreur
    echo '<script>alert("Veuillez entrer les données")</script>';
    header('refresh:0.5; url=listdepart.php');
} else {
    // Préparer la requête de modification
    $requete = "UPDATE departments SET department_name = :department_name WHERE department_id = :department_id";
    
    // Préparer et exécuter la requête
    $stmt = $pdo->prepare($requete);
    
    // Lier les paramètres
    $stmt->bindParam(':department_name', $department_name);
    $stmt->bindParam(':department_id', $department_id);
    
    if ($stmt->execute()) {
        // Message de succès
        echo '<script>alert("Modification effectuée avec succès")</script>';
        header('refresh:0.5; url=listdepart.php');
    } else {
        // Message d'erreur si la mise à jour échoue
        echo '<script>alert("Erreur lors de la modification")</script>';
        header('refresh:0.5; url=listdepart.php');
    }
}
?>