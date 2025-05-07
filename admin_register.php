<?php
session_start();
require 'config.php';

// Récupérer les départements de la base de données
$stmt = $pdo->prepare("SELECT department_id, department_name FROM departments");
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $department_id = $_POST['department_id'];
    $poste_id = 3; // ID de poste fixe pour le supra admin

    if ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $active = 1; // Actif par défaut pour le supra admin
        
        // Insérer dans la base de données
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, active, department_id, poste_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $email, $hashed_password, $active, $department_id, $poste_id]); 
        
        header('Location: home.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Créer un compte Supra Admin</title>
    <style>
        :root {
            --primary-color: #005f87;
            --secondary-color: #e67e22;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('images/pot.jpg');
            background-size: 1300px 600px;
           
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
           
        }

        .register-container {
            width: 55%;
            max-width: 1000px;
            background: rgba(241, 244, 247, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            display: flex;
        }

        .form-section {
            width: 60%;
            padding: 40px;
        }

        .image-section {
            width: 40%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .image-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent 0%, rgba(255, 255, 255, 0.1) 100%);
            transform: rotate(30deg);
        }

        .image-section img {
            width: 350px;
            height: auto;
            filter: drop-shadow(0 0.5rem 1rem rgba(0, 0, 0, 0.2));
            position: relative;
            z-index: 1;
        }

        .form-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
            position: relative;
            padding-bottom: 10px;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--secondary-color);
        }

        .form-control {
            border: 2px solid #e1e5ee;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 95, 135, 0.1);
        }

        .form-select {
            border: 2px solid #e1e5ee;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 15px;
            height: auto;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
            color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #004a6a;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .action-buttons {
            display: flex;
            gap: 25px;
            margin-top: 20px;
        }

        .action-buttons .btn {
            flex: 1;
        }

        @media (max-width: 992px) {
            .register-container {
                flex-direction: column;
                width: 90%;
            }

            .form-section, .image-section {
                width: 100%;
            }

            .image-section {
                padding: 30px;
                order: -1;
            }
        }

        @media (max-width: 576px) {
            .form-section {
                padding: 25px;
            }

            .form-title {
                font-size: 24px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="form-section">
            <h1 class="form-title">Créer un compte Super Admin</h1>
            <form method="post">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="last_name" placeholder="Nom" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="first_name" placeholder="Prénom" required>
                    </div>
                </div>
                
                <input type="email" class="form-control" name="email" placeholder="Email" required>
                
                <select class="form-select" name="department_id" required>
                    <option value="" disabled selected>Choisir une direction</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo htmlspecialchars($department['department_id']); ?>">
                            <?php echo htmlspecialchars($department['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="row">
                    <div class="col-md-6 password-container">
                        <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                    </div>
                    <div class="col-md-6 password-container">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirmer" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_password')"></i>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Créer un compte
                    </button>
                    <a href="acceuil.php" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>

        <div class="image-section">
            <img src="images/logo-masm.png" alt="Illustration d'inscription">
        </div>
    </div>

    <script>
        function togglePassword(inputName) {
            const input = document.querySelector(`input[name="${inputName}"]`);
            const icon = document.querySelector(`input[name="${inputName}"] + .toggle-password`);
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
