<?php
session_start();

if (isset($_SESSION['captcha_blocked_until']) && time() < $_SESSION['captcha_blocked_until']) {
    $remaining = ceil(($_SESSION['captcha_blocked_until'] - time()) / 60);
    die("Trop de tentatives. Revenez dans $remaining minutes.");
}

if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = rand(1000, 9999);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answer = (int)$_POST['captcha'];
    
    if ($answer === $_SESSION['captcha']) {
        unset($_SESSION['captcha_attempts']);
        header('Location: reset-credentials.php');
        exit();
    } else {
        $_SESSION['captcha_attempts'] = ($_SESSION['captcha_attempts'] ?? 0) + 1;
        
        if ($_SESSION['captcha_attempts'] >= 3) {
            $_SESSION['captcha_blocked_until'] = time() + 1800;
            unset($_SESSION['captcha']);
            header('Location: login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de sécurité</title>
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
            text-align: center;
        }

        .captcha-code {
            font-size: 2rem;
            letter-spacing: 5px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            border: 2px dashed #3498db;
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

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 8px;
            position: relative;
            padding-right: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card border-0">
            <div class="card-body p-4">
                <h3 class="card-title mb-4">🔐 Vérification de sécurité</h3>

                <?php if (isset($_SESSION['captcha_attempts'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Tentatives échouées : <?= $_SESSION['captcha_attempts'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="text-center mb-4">
                    <div class="captcha-code">
                        <i class="bi bi-shield-lock me-2"></i><?= $_SESSION['captcha'] ?>
                    </div>
                    <p class="text-muted">Veuillez saisir le code de sécurité ci-dessus</p>
                </div>

                <form method="POST">
                    <div class="mb-4 position-relative">
                        <label class="form-label fw-medium">Code de vérification</label>
                        <div class="input-group">
                            <input type="number" 
                                   name="captcha" 
                                   class="form-control ps-5"
                                   placeholder="Entrez les 4 chiffres"
                                   required>
                            <i class="bi bi-patch-exclamation input-icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg mt-4">
                        <i class="bi bi-shield-check me-2"></i>Vérifier le code
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>