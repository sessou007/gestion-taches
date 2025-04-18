<?php
// Appel au fichier connect
include('config.php');

// Récupérer l'ID du département à partir de l'URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($id === null) {
    echo "ID non spécifié.";
    exit;
}

// Préparer la requête pour récupérer le département spécifique
$requete = "SELECT poste_name FROM postes WHERE id = :id";
$stmt = $pdo->prepare($requete);
$stmt->execute(['id' => $id]);

// Récupérer les données de la requête
$postes = $stmt->fetch();

if (!$postes) {
    echo "Aucun poste trouvé pour cet ID.";
    exit;
}
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
    <h1>Modifier un poste</h1>
    <div class="cadre">
        <form action="validermodifeposte.php" method="post">
            <h2>Modification d'un poste</h2><br><br>
         
            <label for="">Nom</label>
            <input type="text" name="s_poste_name" value="<?= htmlspecialchars($postes['poste_name']) ?>"><br><br>
            
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            
            <p class="bloc">
                <button type="submit">Enregistrer</button>
                <a href="listposte.php" class="button">Annuler</a>
            </p>
        </form>
    </div>
</body>
</html>