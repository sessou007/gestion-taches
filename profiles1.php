<?php     
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirection vers la page de connexion
    exit();
}

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
include('config.php'); 
// Récupération des informations utilisateur depuis la base de données
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérification si l'utilisateur existe
if (!$user) {
    header('Location: login.php'); // Redirection si l'utilisateur n'existe pas
    exit();
}

// Récupération des informations utilisateur
$prenom = $user['first_name'];
$nom = $user['last_name'];
$email = $user['email'];
$active = $user['active'];

$poste_name = '';
if (!empty($user['poste_id'])) {
    $stmt = $pdo->prepare("SELECT poste_name FROM postes WHERE id = ?");
    $stmt->execute([$user['poste_id']]);
    $poste = $stmt->fetch(PDO::FETCH_ASSOC);
    $poste_name = $poste ? $poste['poste_name'] : 'Non défini';
} else {
    $poste_name = 'Non défini';
}

// Récupérer le nom du département si le department_id est défini
$department_name = '';
if (!empty($user['department_id'])) {
    $stmt = $pdo->prepare("SELECT department_name FROM departments WHERE department_id = ?");
    $stmt->execute([$user['department_id']]);
    $department = $stmt->fetch(PDO::FETCH_ASSOC);
    $department_name = $department ? $department['department_name'] : 'Non défini';
} else {
    $department_name = 'Non défini';
}

// Message pour l'affichage d'une confirmation ou d'une erreur
$message = "";

// Gestion de la mise à jour des informations
if (isset($_POST['update'])) {
    $nouveau_email = trim($_POST['email']);
    $nouveau_prenom = trim($_POST['prenom']);
    $nouveau_nom = trim($_POST['nom']);

    // Mettre à jour les informations dans la base de données
    $stmt = $pdo->prepare("UPDATE users SET email = :email, first_name = :prenom, last_name = :nom WHERE user_id = :user_id");
    $stmt->bindParam(':email', $nouveau_email);
    $stmt->bindParam(':prenom', $nouveau_prenom);
    $stmt->bindParam(':nom', $nouveau_nom);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
        // Mettre à jour les informations dans la session
        $_SESSION['email'] = $nouveau_email;
        $_SESSION['prenom'] = $nouveau_prenom;
        $_SESSION['nom'] = $nouveau_nom;

        $message = "Informations mises à jour avec succès.";
    } else {
        $message = "Une erreur est survenue lors de la mise à jour.";
    }
}

// Vérifiez si l'utilisateur est toujours actif
$stmt = $pdo->prepare("SELECT active FROM users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['active'] == 0) {
    session_destroy(); // Détruire la session
    header("Location: login.php?error=compte_desactive");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Gestion des Tâches</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #e4e4e4;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .profile-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .info h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-size: 14px;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
            font-size: 14px;
            margin-top: 5px;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .back-btn {
            width: 100%;
            padding: 10px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Profil de <?php echo htmlspecialchars($nom . ' ' . $prenom); ?></h1>
</div>

<div class="container">
    <div class="profile-header">
        <img src="https://www.w3schools.com/w3images/avatar2.png" alt="Photo de profil">
        <div class="info">
            <h2><?php echo htmlspecialchars($nom . ' ' . $prenom); ?></h2>
            <p>Identifiant : <?php echo htmlspecialchars($user_id); ?></p>
            <p>Rôle : <?php echo htmlspecialchars($poste_name); ?></p>
            <p>Direction : <?php echo htmlspecialchars($department_name); ?></p>
            <p>Email : <?php echo htmlspecialchars($email); ?></p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" required>
        </div>
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <button class="btn btn-primary" type="submit" name="update">Mettre à jour</button>
    </form>

    <form action="home.php" method="get">
        <button class="back-btn" type="submit">Retour à l'accueil</button>
    </form>
</div>

</body>
</html>