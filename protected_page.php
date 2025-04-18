<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Contenu de la page protégée
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Protégée</title>
</head>
<body>
    <h1>Bienvenue sur la page protégée</h1>
    <a href="logout.php">Se déconnecter</a>
</body>
</html>