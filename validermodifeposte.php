<?php
include('config.php');

// Récupération des données
$poste_name = $_POST['s_poste_name'];
$id = $_POST['id']; // Récupérer l'ID du département

// Contrôle de la saisie
if (empty($poste_name)) {
    // Message d'erreur
    echo '<script>alert("Veuillez entrer les données")</script>';
    header('refresh:0.5; url=listpost.php');
} else {
    // Préparer la requête de modification
    $requete = "UPDATE postes SET poste_name = :poste_name WHERE id = :id";
    
    // Préparer et exécuter la requête
    $stmt = $pdo->prepare($requete);
    
    // Lier les paramètres
    $stmt->bindParam(':poste_name', $poste_name);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        // Message de succès
        echo '<script>alert("Modification effectuée avec succès")</script>';
        header('refresh:0.5; url=listposte.php');
    } else {
        // Message d'erreur si la mise à jour échoue
        echo '<script>alert("Erreur lors de la modification")</script>';
        header('refresh:0.5; url=listposte.php');
    }
}
?>