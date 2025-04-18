<?php
// Appel au fichier connect
include('config.php');

// Récupérer l'ID du département à partir de l'URL
$department_id = $_GET['id'];

// Préparer la requête pour récupérer le département spécifique
$requete = "SELECT department_name FROM departments WHERE department_id = :id";
$stmt = $pdo->prepare($requete);
$stmt->execute(['id' => $department_id]);

// Récupérer les données de la requête
$departments = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleform.css">
    <title>MODIFIER-EXAMEN</title>
</head>
<?php include('response.php'); ?>
<body>
    <h1>Modifier examen</h1>
    <div class="cadre">
        <form action="validermodifedepart.php" method="post">
            <h2>Modification d'un département</h2><br><br>
         
            <label for="">Nom</label>
            <input type="text" name="s_department_name" value="<?= htmlspecialchars($departments['department_name']) ?>"><br><br>
            
            <input type="hidden" name="department_id" value="<?= htmlspecialchars($department_id) ?>">
            
            <p class="bloc">
                <button type="submit">Enregistrer</button>
                <a href="listdepart.php" class="button">Annuler</a>
            </p>
        </form>
    </div>
</body>
</html>