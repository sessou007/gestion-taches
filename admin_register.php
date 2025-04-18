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
    <title>Créer un compte Supra Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/font.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 50%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.9);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        .form-container {
            width: 60%;
            padding: 15px;
        }
        .image-container {
            width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
        }
        button {
            width: 100%;
            padding: 8px;
            background-color: blue;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
        }
        button:hover {
            background-color: darkblue;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }
        .action-buttons .btn {
            width: 48%;
            background-color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Créer un compte Supra Admin</h1>
            <form method="post">
                <input type="text" name="first_name" placeholder="Prénom" required>
                <input type="text" name="last_name" placeholder="Nom" required>
                <input type="email" name="email" placeholder="Email" required>
                
                <select name="department_id" required>
                    <option value="" disabled selected>Choisir une direction</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['department_id']; ?>">
                            <?php echo htmlspecialchars($department['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="password-container">
                    <input type="password" name="password" placeholder="Mot de passe" required>
                    <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                </div>
                <div class="password-container">
                    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                    <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
                </div>
                <div class="action-buttons">
                    <button type="submit">Créer un compte</button>
                    <a href="acceuil.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
        <div class="image-container">
            <img src="images/connexion.png" alt="Illustration d'inscription">
        </div>
    </div>

    <script>
        function togglePassword(inputName) {
            const input = document.querySelector(`input[name="${inputName}"]`);
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>
</body>
</html>
