<?php
session_start();
require 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier le blocage CAPTCHA
if (isset($_SESSION['captcha_blocked_until']) && time() < $_SESSION['captcha_blocked_until']) {
    $remaining = ceil(($_SESSION['captcha_blocked_until'] - time()) / 60);
    die("Trop de tentatives. Revenez dans $remaining minutes.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $_SESSION['attempted_email'] = $email;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Format email invalide.";
        header('Location: login.php');
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT u.*, d.department_name 
                             FROM Users u 
                             LEFT JOIN departments d ON u.department_id = d.department_id 
                             WHERE u.email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                if ($user['active']) {
                    unset($_SESSION['login_attempts']);
                    
                    // Création de la session
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['poste_id'] = $user['poste_id'];
                    $_SESSION['departments'] = $user['department_name'];
                    $_SESSION['department_id'] = $user['department_id'];

                    // Vérification du rôle
                    $stmt = $pdo->prepare("SELECT id FROM postes WHERE poste_name = 'supra_admin'");
                    $stmt->execute();
                    $postv = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($postv['id'] === $user['poste_id']) {
                        header('Location: home.php');
                    } else {
                        header('Location: dashboard.php');
                    }
                    exit();
                } else {
                    $_SESSION['error_message'] = "Compte désactivé. Contactez l'administrateur.";
                }
            } else {
                $_SESSION['error_message'] = "Mot de passe incorrect.";
                $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            }
        } else {
            $_SESSION['error_message'] = "Email non enregistré.";
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        }

        if ($_SESSION['login_attempts'] >= 3) {
            header('Location: captcha.php');
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erreur système. Veuillez réessayer.";
        error_log("Database error: " . $e->getMessage());
    }
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