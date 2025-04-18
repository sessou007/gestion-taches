"use strict";

// Service Worker - note.js
self.addEventListener('install', function (event) {
  console.log('Service Worker installé'); // Force l'activation immédiate du Service Worker

  self.skipWaiting();
});
self.addEventListener('activate', function (event) {
  console.log('Service Worker activé'); // Prend le contrôle de toutes les pages dès l'activation

  event.waitUntil(clients.claim());
});
self.addEventListener('push', function (event) {
  console.log('Notification push reçue'); // Récupère les données de la notification

  var data = event.data ? event.data.json() : {};
  var title = data.title || 'Notification';
  var options = {
    body: data.body || 'Vous avez une nouvelle notification.',
    icon: data.icon || '/t/images/icon.png',
    // Chemin par défaut de l'icône
    badge: data.badge || '/t/images/badge.png' // Chemin par défaut du badge

  }; // Affiche la notification

  event.waitUntil(self.registration.showNotification(title, options)["catch"](function (error) {
    console.error('Erreur lors de l\'affichage de la notification :', error);
  }));
});
self.addEventListener('notificationclick', function (event) {
  console.log('Notification cliquée'); // Ferme la notification

  event.notification.close(); // Ouvre une page ou effectue une action lorsque la notification est cliquée

  event.waitUntil(clients.openWindow('/t/') // Remplacez par l'URL de votre choix
  );
});
//# sourceMappingURL=note.dev.js.map
