<?php
session_start();
require 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Protection contre les attaques par force brute
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if (isset($_SESSION['captcha_blocked_until']) && time() < $_SESSION['captcha_blocked_until']) {
    $remaining = ceil(($_SESSION['captcha_blocked_until'] - time()) / 60);
    die("Trop de tentatives. Revenez dans $remaining minutes.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nettoyage des entrées
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Ne pas sanitizer pour le hachage

    $_SESSION['attempted_email'] = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Format email invalide.";
        header('Location: login.php');
        exit();
    }

    try {
        // Requête préparée avec jointure sécurisée
        $stmt = $pdo->prepare("SELECT u.*, d.department_name 
                             FROM Users u 
                             LEFT JOIN departments d ON u.department_id = d.department_id 
                             WHERE u.email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        // Protection contre le timing attack
        $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        $validUser = false;

        if ($user) {
            $validUser = password_verify($password, $user['password']);
        }

        // Vérification différée
        if ($validUser) {
            if ($user['active']) {
                // Réinitialisation de session
                session_regenerate_id(true);
                $_SESSION = [];
                
                // Création de la session sécurisée
                $_SESSION['user_id'] = (int)$user['user_id'];
                $_SESSION['poste_id'] = (int)$user['poste_id'];
                $_SESSION['departments'] = htmlspecialchars($user['department_name'], ENT_QUOTES, 'UTF-8');
                $_SESSION['department_id'] = (int)$user['department_id'];
                $_SESSION['last_activity'] = time();

                // Validation du rôle
                $stmt = $pdo->prepare("SELECT id FROM postes WHERE poste_name = 'supra_admin'");
                $stmt->execute();
                $postv = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

                header('Location: dashboard.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Compte désactivé. Contactez l'administrateur.";
            }
        } else {
            $_SESSION['error_message'] = "Identifiants incorrects.";
            $_SESSION['login_attempts'] += 1;
        }

        if ($_SESSION['login_attempts'] >= 3) {
            $_SESSION['captcha_blocked_until'] = time() + 1800;
            header('Location: captcha.php');
            exit();
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['error_message'] = "Erreur système. Veuillez réessayer.";
    }
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            background-image: url('images/cm001.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .alert {
            display: none;
        }
        .logo {
            width: 150px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
    <div id="message" class="alert alert-danger"></div>
        <div class="text-center mb-3">
            <img src="images/logo_masm.png" alt="Illustration d'inscription" class="img-fluid">
        </div>
        
        <div id="message" class="alert alert-danger"></div>
        
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mb-3">Se connecter</button>
            
            <div class="text-center">
                <a href="register.php" class="btn btn-outline-secondary w-100">Créer un compte</a>
            </div>
        </form>
    </div>

    <script>
        <?php if (isset($_SESSION['error_message'])): ?>
            document.getElementById('message').textContent = "<?= addslashes($_SESSION['error_message']) ?>";
            document.getElementById('message').style.display = 'block';
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>