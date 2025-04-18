<?php
session_start();
require 'config.php';

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérification si l'utilisateur est supra admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Utilisateur non trouvé.";
    exit;
}

if ($user['role'] !== 'supra_admin') {
    echo "Rôle de l'utilisateur : " . htmlspecialchars($user['role']);
    header('Location: home.php');
    exit;
}

// Gestion des actions
$notification = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['activate_user_id'])) {
        $activate_user_id = $_POST['activate_user_id'];
        $stmt = $pdo->prepare("UPDATE Users SET active = 1 WHERE user_id = ?");
        $stmt->execute([$activate_user_id]);

        // Récupérer les informations de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = ?");
        $stmt->execute([$activate_user_id]);
        $activated_user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Mettre à jour la session
        $_SESSION['user_id'] = $activated_user['user_id'];
        $_SESSION['role'] = $activated_user['role'];

        // Redirection vers la page d'accueil de l'utilisateur
        header('Location: acceuil.php');
        exit;
    }

    if (isset($_POST['modify_user_id']) && isset($_POST['new_role'])) {
        $modify_user_id = $_POST['modify_user_id'];
        $new_role = $_POST['new_role'];
        $stmt = $pdo->prepare("UPDATE Users SET role = ? WHERE user_id = ?");
        $stmt->execute([$new_role, $modify_user_id]);
        $notification = "Rôle modifié avec succès.";
    }

    if (isset($_POST['delete_user_id'])) {
        $delete_user_id = $_POST['delete_user_id'];
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->execute([$delete_user_id]);
        $notification = "Compte supprimé avec succès.";
    }
}

// Récupération des utilisateurs
$users = $pdo->query("SELECT * FROM Users")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #5cb85c;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        form {
            display: inline;
        }
        button {
            padding: 5px 10px;
            background-color: #5bc0de;
            border: none;
            border-radius: 3px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #31b0d5;
        }
        select {
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            margin-right: 5px;
        }
        .notification {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Gestion des utilisateurs</h1>
    <?php if ($notification): ?>
        <p class="notification"><?php echo htmlspecialchars($notification); ?></p>
    <?php endif; ?>
    <table>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actif</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="modify_user_id" value="<?php echo $user['user_id']; ?>">
                    <select name="new_role">
                        <option value="Sous_Chef_Service" <?php echo $user['role'] == 'Sous_Chef_Service' ? 'selected' : ''; ?>>Sous Chef Service</option>
                        <option value="Chef_Service" <?php echo $user['role'] == 'Chef_Service' ? 'selected' : ''; ?>>Chef Service</option>
                        <option value="directeur" <?php echo $user['role'] == 'directeur' ? 'selected' : ''; ?>>Directeur</option>
                        <option value="supra_admin" <?php echo $user['role'] == 'supra_admin' ? 'selected' : ''; ?>>Supra Admin</option>
                    </select>
                    <button type="submit">Modifier</button>
                </form>
            </td>
            <td><?php echo $user['active'] ? 'Oui' : 'Non'; ?></td>
            <td>
                <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir activer ce compte ?');">
                    <input type="hidden" name="activate_user_id" value="<?php echo $user['user_id']; ?>">
                    <button type="submit">Activer</button>
                </form>
                <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ?');">
                    <input type="hidden" name="delete_user_id" value="<?php echo $user['user_id']; ?>">
                    <button type="submit">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>