<?php
// Configuration du fuseau horaire
date_default_timezone_set('Africa/Porto-Novo');

// Connexion à la base de données
$host = 'localhost';
$dbname = 'dbtaches';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit();
}

// Récupération des événements
$sql = "SELECT id, titre AS title, debut AS start, description, alarme 
        FROM evenement 
        WHERE DELETED = 0";
$stmt = $conn->prepare($sql);

try {
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les événements sous forme de JSON
    echo json_encode($events);
    exit(); // Terminer le script ici pour ne pas afficher le HTML après
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la récupération des événements : ' . $e->getMessage()]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Alarme Agenda</title>
    <style>
        .alert {
            animation: blink 1s infinite;
            color: red;
        }

        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <h1>Liste des Événements avec Alarmes</h1>
    <table border="1" id="eventTable">
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Date et Heure de début</th>
            <th>Alarme (minutes avant)</th>
        </tr>
    </table>

    <audio id="alarmSound" src="taches/ALRMClok_Reveil_electronique_sonnerie_1.wav" preload="auto"></audio>

    <script>
        const alarmSound = document.getElementById('alarmSound');
        let notifiedEvents = {};

        // Enregistrement du Service Worker
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.register('service-worker.js')
            .then(function(registration) {
                console.log('Service Worker registered with scope:', registration.scope);
                return Notification.requestPermission();
            })
            .then(function(permission) {
                if (permission === 'granted') {
                    subscribeUserToPush(registration);
                }
            });
        }

        function subscribeUserToPush(registration) {
            const applicationServerKey = urlB64ToUint8Array('<YOUR_PUBLIC_VAPID_KEY>');
            registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: applicationServerKey
            }).then(function(subscription) {
                console.log('User is subscribed:', subscription);
                // Envoyer l'abonnement au serveur pour notifications futures
            }).catch(function(error) {
                console.error('Failed to subscribe the user: ', error);
            });
        }

        // Fonction pour vérifier les alarmes via AJAX
        function checkAlarms() {
            $.ajax({
                url: 'getevents.php',
                method: 'GET',
                dataType: 'json',
                success: function(events) {
                    const currentTime = new Date();
                    let tableContent = '';

                    events.forEach(event => {
                        tableContent += `<tr>
                            <td>${event.title}</td>
                            <td>${event.description}</td>
                            <td>${new Date(event.start).toLocaleString()}</td>
                            <td>${event.alarme}</td>
                        </tr>`;

                        const eventStartTime = new Date(event.start);
                        const alarmTime = new Date(eventStartTime - event.alarme * 60 * 1000);

                        if (currentTime >= alarmTime && currentTime < eventStartTime) {
                            if (!notifiedEvents[event.id]) {
                                // Créer une notification
                                if (Notification.permission === "granted") {
                                    new Notification('Rappel : ' + event.title, {
                                        body: 'L\'événement commence dans ' + event.alarme + ' minutes.',
                                        icon: 'taches/icon.png'
                                    });
                                }
                                alarmSound.play();
                                document.body.classList.add('alert');
                                notifiedEvents[event.id] = true;
                            }
                        }
                    });
                    $('#eventTable').html(`<tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Date et Heure de début</th>
                        <th>Alarme (minutes avant)</th>
                    </tr>` + tableContent);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Erreur lors de la récupération des événements : ', textStatus, errorThrown);
                }
            });
        }

        // Vérifier les alarmes toutes les minutes
        setInterval(checkAlarms, 60000);
        checkAlarms();
    </script>
</body>
</html>