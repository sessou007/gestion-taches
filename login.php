<?php
session_start();
require 'config.php'; // Assurez-vous que ce fichier contient la définition de $pdo

// Afficher les erreurs pour le débogage (à retirer en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Adresse email invalide.";
        header('Location: login.php');
        exit();
    }

    try {
        // Préparer et exécuter la requête avec des paramètres liés
        $stmt = $pdo->prepare("SELECT u.*, d.department_name 
                               FROM Users u 
                               LEFT JOIN departments d ON u.department_id = d.department_id 
                               WHERE u.email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification des identifiants
        if ($user && password_verify($password, $user['password'])) {
            if ($user['active']) {
                // Stocker l'ID utilisateur, le rôle et la direction dans la session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['poste_id'] = $user['poste_id'];
                $_SESSION['departments'] = $user['department_name']; // Stocker la direction
                $_SESSION['department_id'] = $user['department_id']; // Ajouter cette ligne

                // Vérifier si l'utilisateur est un supra_admin
                $stmt = $pdo->prepare("SELECT id FROM postes WHERE poste_name = 'supra_admin'");
                $stmt->execute();
                $postv = $stmt->fetch(PDO::FETCH_ASSOC);

                // Redirection selon le rôle
                if ($postv['id'] === $user['poste_id']) {
                    header('Location: home.php');  
                    exit();
                } else {
                    header('Location: dashboard.php'); // Page pour les sous-chefs de service
                    exit();
                } 
            } else {
                $_SESSION['error_message'] = "Votre compte est désactivé. Veuillez contacter l'administrateur.";
            }
        } else {
            $_SESSION['error_message'] = "Identifiants invalides.";
        }
    } catch (PDOException $e) {
        // Gestion des erreurs PDO
        $_SESSION['error_message'] = "Une erreur s'est produite. Veuillez réessayer plus tard.";
        error_log("Erreur de base de données : " . $e->getMessage()); // Log de l'erreur
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            height: auto;
            background-image: url('images/cm001.jpg');
            background-size: cover;
            background-position: center;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
            margin: 20px auto;
            margin-top: 100px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .alert {
            display: none;
        }
        .btn-custom {
            font-weight: bold;
            transition: 0.3s ease-in-out;
        }
        .btn-custom:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="message" class="alert alert-danger"></div>
        <div class="text-center mb-3">
            <img src="images/logo_masm.png" alt="Illustration d'inscription" class="img-fluid">
        </div>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 btn-custom">Se connecter</button>
            <div class="text-center mt-3">
                <a href="register.php" class="btn btn-outline-secondary w-100 btn-custom">Créer un compte</a>
            </div>
        </form>
    </div>

    <script>
    // Afficher le message d'erreur si présent dans la session PHP
    <?php if (isset($_SESSION['error_message'])): ?>
    const message = document.getElementById('message');
    message.textContent = "<?php echo addslashes($_SESSION['error_message']); ?>";
    message.style.display = 'block';
    <?php unset($_SESSION['error_message']); ?> // Supprime le message après l'affichage
    <?php endif; ?>
    </script>
</body>
</html>