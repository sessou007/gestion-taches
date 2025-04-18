"use strict";

// notifications.js
$(document).ready(function () {
  // Fonction pour récupérer les notifications
  function fetchNotifications() {
    $.ajax({
      url: 'fetch_notifications.php',
      method: 'GET',
      dataType: 'json',
      success: function success(data) {
        updateNotificationCount(data.count);
        updateNotificationList(data.notifications);
      },
      error: function error(xhr, status, _error) {
        console.error("Erreur lors de la récupération des notifications: ", _error);
      }
    });
  } // Fonction pour mettre à jour le compteur de notifications


  function updateNotificationCount(count) {
    $('#notification-count').text(count);
  } // Fonction pour mettre à jour la liste des notifications


  function updateNotificationList(notifications) {
    var notificationList = $('#notification-list');
    notificationList.empty();
    notifications.forEach(function (notification) {
      var notificationItem = $('<div>').addClass('notification-item').text(notification.message).click(function () {
        markNotificationAsRead(notification.id);
      });

      if (!notification.read) {
        notificationItem.addClass('unread');
      }

      notificationList.append(notificationItem);
    });
  } // Fonction pour marquer une notification comme lue


  function markNotificationAsRead(notificationId) {
    $.ajax({
      url: 'mark_notification_as_read.php',
      method: 'POST',
      data: {
        id: notificationId
      },
      success: function success() {
        fetchNotifications(); // Recharge les notifications après marquage
      },
      error: function error(xhr, status, _error2) {
        console.error("Erreur lors du marquage de la notification comme lue: ", _error2);
      }
    });
  } // Fonction pour afficher ou masquer la liste des notifications


  function toggleNotifications() {
    var dropdown = $('#notification-dropdown');
    dropdown.toggle();

    if (dropdown.is(':visible')) {
      fetchNotifications(); // Recharge les notifications lorsque la liste est affichée
    }
  } // Écouteur d'événement pour le clic sur la cloche


  $('#notification-bell').click(toggleNotifications); // Recharge les notifications toutes les 30 secondes

  setInterval(fetchNotifications, 30000); // Charge les notifications au chargement de la page

  fetchNotifications();
});
//# sourceMappingURL=notifications.dev.js.map
