<?php
include('connect.php');

// Récupération des données du formulaire
$identifiant = $_POST['identifiant'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$mot_de_passe = $_POST['mot_de_passe'];
$confirm = $_POST['confirm'];
$role = $_POST['role']; // Nouveau champ pour le rôle

// Validation de base des entrées
if (empty($nom) || empty($prenom) || empty($role)) {
    header("Location: modifierutilisateurs.php?identifiant=$identifiant&error=missing_fields");
    exit;
}

// Validation du mot de passe
if (!empty($mot_de_passe)) {
    if ($mot_de_passe !== $confirm) {
        header("Location: modifierutilisateurs.php?identifiant=$identifiant&error=password_mismatch");
        exit;
    }

    // Hashage du mot de passe avant de le stocker
    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    $requete = "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, mot_de_passe = :mot_de_passe, role = :role WHERE identifiant = :identifiant";
    $stmt = $bdd->prepare($requete);
    $stmt->bindParam(':mot_de_passe', $hashed_password, PDO::PARAM_STR);
} else {
    $requete = "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, role = :role WHERE identifiant = :identifiant";
    $stmt = $bdd->prepare($requete);
}

// Bind parameters
$stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
$stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
$stmt->bindParam(':role', $role, PDO::PARAM_STR); // Ajout du bind pour le rôle
$stmt->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);

// Exécution de la requête
if ($stmt->execute()) {
    header("Location: listeutili.php?success=modification_reussie");
    exit;
} else {
    header("Location: modifierutilisateurs.php?identifiant=$identifiant&error=database_error");
    exit;
}
?>