<?php
session_start();
require 'config.php';

if (!isset($_SESSION['login_attempts']) || $_SESSION['login_attempts'] < 3) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newEmail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->execute([$_SESSION['attempted_email']]);
        $user = $stmt->fetch();

        if ($user) {
            $updates = [];
            $params = [];

            if ($newEmail && $newEmail !== $_SESSION['attempted_email']) {
                $updates[] = "email = ?";
                $params[] = $newEmail;
            }

            if (!empty($newPassword) && $newPassword === $confirmPassword) {
                $updates[] = "password = ?";
                $params[] = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            if (!empty($updates)) {
                $params[] = $user['user_id'];
                $sql = "UPDATE Users SET " . implode(', ', $updates) . " WHERE user_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
                $_SESSION['success_message'] = "Informations mises à jour avec succès !";
                unset($_SESSION['login_attempts']);
                header('Location: login.php');
                exit();
            } else {
                $error = "Aucune modification détectée.";
            }
        } else {
            $error = "Utilisateur introuvable.";
        }
    } catch (PDOException $e) {
        $error = "Erreur de base de données : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        .card-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #7f8c8d;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card border-0">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4">🔒 Mise à jour des identifiants</h3>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <!-- Email Field -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Nouvel email</label>
                        <div class="input-group">
                            <input type="email" 
                                   name="email" 
                                   class="form-control"
                                   placeholder="exemple@domain.com"
                                   value="<?= htmlspecialchars($_SESSION['attempted_email'] ?? '') ?>">
                            <span class="input-group-text bg-transparent">
                                <i class="bi bi-envelope-at"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4 position-relative">
                        <label class="form-label fw-medium">Nouveau mot de passe</label>
                        <div class="input-group">
                            <input type="password" 
                                   name="password" 
                                   id="password"
                                   class="form-control"
                                   placeholder="Nouveau mot de passe">
                            <span class="input-group-text bg-transparent">
                                
                            </span>
                        </div>
                        <i class="bi bi-eye password-toggle" id="togglePassword"></i>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-4 position-relative">
                        <label class="form-label fw-medium">Confirmation</label>
                        <div class="input-group">
                            <input type="password" 
                                   name="confirm_password" 
                                   id="confirmPassword"
                                   class="form-control"
                                   placeholder="Entrer à nouveau le mot de passe">
                            <span class="input-group-text bg-transparent">
                                
                            </span>
                        </div>
                        <i class="bi bi-eye password-toggle" id="toggleConfirmPassword"></i>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-check2-circle me-2"></i>Valider les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#confirmPassword');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('bi-eye-slash');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>