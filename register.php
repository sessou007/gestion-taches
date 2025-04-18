<?php
session_start();
require 'config.php';

// Récupération des départements
$departments = [];
$stmt = $pdo->query("SELECT department_id, department_name FROM departments");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $departments[] = $row;
}

$posts = [];
$stmt1 = $pdo->query("SELECT id, poste_name FROM postes where poste_name !='supra_admin'");
while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
    $posts[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']); // Enlever les espaces
    $last_name = trim($_POST['last_name']); // Enlever les espaces
    $email = trim($_POST['email']); // Enlever les espaces
    $position = $_POST['position']; // Récupérer le poste
    $department_id = $_POST['department_id']; // Récupérer l'ID du département
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation des champs
    if (empty($first_name) || empty($last_name)) {
         // Message de succès
        echo '<script>alert("Le prénom et le nom ne peuvent pas être vides.")</script>';
    } elseif (preg_match('/^\s*$/', $first_name) || preg_match('/^\s*$/', $last_name)) {
         // Message de succès
         echo '<script>alert("Le prénom et le nom ne peuvent pas contenir uniquement des espaces.")</script>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@gmail\.com$/', $email)) {
          // Message de succès
          echo '<script>alert("Veuillez entrer un email valide au format Gmail (ex: utilisateur@gmail.com).")</script>';
    } elseif ($password !== $confirm_password) {
        // Message de succès
        echo '<script>alert("Les mots de passe ne correspondent pas.")</script>';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        // Message de succès
        echo '<script>alert("Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.")</script>';
         
    } else {
        // Vérification de l'unicité de l'email
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $email_count = $stmt->fetchColumn();

        if ($email_count > 0) {
             // Message de succès
        echo '<script>alert("Ce compte existe déjà avec cet email.")</script>';
        } else {
            // Hachage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertion dans la base de données
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, poste_id, active, department_id) VALUES (?, ?, ?, ?, ?, 0, ?)");
            $stmt->execute([$first_name, $last_name, $email, $hashed_password, $position, $department_id]);
            // Message de succès
            echo '<script>
            alert("Compte créé avec succès. Veuillez attendre l\'activation par le supra admin.");
            window.location.href = "acceuil.php";
          </script>';
    exit; // Arrête l'exécution du script après l'affichage du message
    
 
        }
    }
}
   

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #005f87;
            --secondary-color: #e67e22;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('images/font.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .register-container {
            width: 85%;
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.95);
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
            background: linear-gradient(135deg, var(--primary-color), #004a6a);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .image-section img {
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.2));
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
            gap: 15px;
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
        <h1 class="form-title">Créer votre compte</h1>
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
            
            <div class="row">
                <div class="col-md-6">
                    <select class="form-select" name="department_id" required>
                        <option value="" disabled selected>Choisir une direction</option>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?php echo htmlspecialchars($department['department_id']); ?>">
                                <?php echo htmlspecialchars($department['department_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <select class="form-select" name="position" required>
                        <option value="" disabled selected>Choisir un poste</option>
                        <?php foreach ($posts as $post): ?>
                            <option value="<?php echo htmlspecialchars($post['id']); ?>">
                                <?php echo htmlspecialchars($post['poste_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
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
        <img src="images/connexion.png" alt="Illustration d'inscription">
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