// notifications.js

$(document).ready(function() {
    // Fonction pour récupérer les notifications
    function fetchNotifications() {
        $.ajax({
            url: 'fetch_notifications.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                updateNotificationCount(data.count);
                updateNotificationList(data.notifications);
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors de la récupération des notifications: ", error);
            }
        });
    }

    // Fonction pour mettre à jour le compteur de notifications
    function updateNotificationCount(count) {
        $('#notification-count').text(count);
    }

    // Fonction pour mettre à jour la liste des notifications
    function updateNotificationList(notifications) {
        const notificationList = $('#notification-list');
        notificationList.empty();

        notifications.forEach(notification => {
            const notificationItem = $('<div>').addClass('notification-item')
                .text(notification.message)
                .click(function() {
                    markNotificationAsRead(notification.id);
                });

            if (!notification.read) {
                notificationItem.addClass('unread');
            }

            notificationList.append(notificationItem);
        });
    }

    // Fonction pour marquer une notification comme lue
    function markNotificationAsRead(notificationId) {
        $.ajax({
            url: 'mark_notification_as_read.php',
            method: 'POST',
            data: { id: notificationId },
            success: function() {
                fetchNotifications(); // Recharge les notifications après marquage
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors du marquage de la notification comme lue: ", error);
            }
        });
    }

    // Fonction pour afficher ou masquer la liste des notifications
    function toggleNotifications() {
        const dropdown = $('#notification-dropdown');
        dropdown.toggle();
        if (dropdown.is(':visible')) {
            fetchNotifications(); // Recharge les notifications lorsque la liste est affichée
        }
    }

    // Écouteur d'événement pour le clic sur la cloche
    $('#notification-bell').click(toggleNotifications);

    // Recharge les notifications toutes les 30 secondes
    setInterval(fetchNotifications, 30000);

    // Charge les notifications au chargement de la page
    fetchNotifications();
});