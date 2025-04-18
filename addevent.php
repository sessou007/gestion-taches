<?php
session_start();

// Connexion à la base de données
$host = 'localhost'; 
$dbname = 'gestion_tache'; 
$username = 'root'; 
$password = ''; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit();
}

// Vérifiez si l'utilisateur est connecté et que user_id est présent dans la session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté.']);
    exit();
}

$userId = $_SESSION['user_id']; // Récupérer l'ID utilisateur depuis la session

// Vérifiez si les données POST sont présentes
if (isset($_POST['titre'], $_POST['description'], $_POST['debut'], $_POST['fin'])) {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $debut = $_POST['debut'];
    $alarme = isset($_POST['alarme']) ? $_POST['alarme'] : null; // Définit alarme à null si non fourni
    $fin = $_POST['fin'];
    $employees = isset($_POST['employees']) ? $_POST['employees'] : []; // Liste des employés sélectionnés (peut être vide)

    // Requête pour insérer un événement dans la table evenement
    $sqlEvenement = "INSERT INTO evenement (titre, description, debut, alarme, fin, user_id, assigned_by) VALUES (:titre, :description, :debut, :alarme, :fin, :user_id, :assigned_by)";
    $stmtEvenement = $conn->prepare($sqlEvenement);

    try {
        if (empty($employees)) {
            // Assigner la tâche à l'utilisateur connecté
            $stmtEvenement->bindParam(':titre', $titre);
            $stmtEvenement->bindParam(':description', $description);
            $stmtEvenement->bindParam(':debut', $debut);
            $stmtEvenement->bindParam(':alarme', $alarme);
            $stmtEvenement->bindParam(':fin', $fin);
            $stmtEvenement->bindParam(':user_id', $userId); // Lier l'ID de l'utilisateur connecté
            $stmtEvenement->bindParam(':assigned_by', $userId); // Lier l'ID de l'utilisateur qui assigne la tâche

            $stmtEvenement->execute(); // Exécuter la requête pour la table evenement
        } else {
            // Si des employés sont sélectionnés, assigner la tâche à chaque employé
            foreach ($employees as $employeeId) {
                // Insérer l'événement dans la table evenement
                $stmtEvenement->bindParam(':titre', $titre);
                $stmtEvenement->bindParam(':description', $description);
                $stmtEvenement->bindParam(':debut', $debut);
                $stmtEvenement->bindParam(':alarme', $alarme);
                $stmtEvenement->bindParam(':fin', $fin);
                $stmtEvenement->bindParam(':user_id', $employeeId); // Lier l'ID de l'employé
                $stmtEvenement->bindParam(':assigned_by', $userId); // Lier l'ID de l'utilisateur qui assigne la tâche

                $stmtEvenement->execute(); // Exécuter la requête pour la table evenement

                // Récupérer l'ID de l'événement inséré
                $eventId = $conn->lastInsertId();

                // Insérer l'assignation dans la table task_assignments
                $sqlAssignment = "
                    INSERT INTO task_assignments (
                        task_id, user_id, titre, description, debut, fin, status, assigned_by, alarme, url, DELETED, raison, button_disabled, event_name, termine
                    ) VALUES (
                        :task_id, :user_id, :titre, :description, :debut, :fin, :status, :assigned_by, :alarme, :url, :DELETED, :raison, :button_disabled, :event_name, :termine
                    )
                ";
                $stmtAssignment = $conn->prepare($sqlAssignment);
                $stmtAssignment->bindParam(':task_id', $eventId);
                $stmtAssignment->bindParam(':user_id', $employeeId);
                $stmtAssignment->bindParam(':titre', $titre);
                $stmtAssignment->bindParam(':description', $description);
                $stmtAssignment->bindParam(':debut', $debut);
                $stmtAssignment->bindParam(':fin', $fin);
                $stmtAssignment->bindValue(':status', 'en cours'); // Statut par défaut
                $stmtAssignment->bindParam(':assigned_by', $userId);
                $stmtAssignment->bindParam(':alarme', $alarme);
                $stmtAssignment->bindValue(':url', ''); // URL par défaut
                $stmtAssignment->bindValue(':DELETED', 0); // DELETED par défaut
                $stmtAssignment->bindValue(':raison', ''); // Raison par défaut
                $stmtAssignment->bindValue(':button_disabled', 0); // button_disabled par défaut
                $stmtAssignment->bindValue(':event_name', ''); // event_name par défaut
                $stmtAssignment->bindValue(':termine', 0); // termine par défaut

                $stmtAssignment->execute();
            }
        }

        // Après avoir inséré la tâche dans la table evenement et task_assignments
        if (!empty($employees)) {
            // Récupérer le nom de l'utilisateur qui a assigné la tâche
            $sqlAssigner = "SELECT first_name, last_name FROM users WHERE user_id = :user_id";
            $stmtAssigner = $conn->prepare($sqlAssigner);
            $stmtAssigner->bindParam(':user_id', $userId);
            $stmtAssigner->execute();
            $assigner = $stmtAssigner->fetch(PDO::FETCH_ASSOC);

            // Construire le nom complet de l'utilisateur
            $assignerName = $assigner['first_name'] . ' ' . $assigner['last_name'];

            foreach ($employees as $employeeId) {
                // Insérer une notification pour l'employé
                $sqlNotification = "INSERT INTO notifications (user_id, message, read_status) VALUES (:user_id, :message, 0)";
                $stmtNotification = $conn->prepare($sqlNotification);

                // Construire le message de notification
                $message = "Une nouvelle tâche vous a été assignée : $titre par $assignerName. Date de début : $debut";
                $stmtNotification->bindParam(':user_id', $employeeId);
                $stmtNotification->bindParam(':message', $message);
                $stmtNotification->execute();
            }
        }

        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout de l\'événement : ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes.']);
}
?>