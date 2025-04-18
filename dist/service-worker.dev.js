"use strict";

// Nom du cache
var CACHE_NAME = 'v1'; // Fichiers à mettre en cache

var URLS_TO_CACHE = ['/', '/index.html', '/styles.css', '/script.js', '/t/ALRMClok_Reveil electronique sonnerie 1 (ID 0035)_LS.mp3']; // Installation du Service Worker

self.addEventListener('install', function (event) {
  event.waitUntil(caches.open(CACHE_NAME).then(function (cache) {
    console.log('Mise en cache des fichiers');
    return cache.addAll(URLS_TO_CACHE);
  }));
}); // Activation du Service Worker

self.addEventListener('activate', function (event) {
  event.waitUntil(caches.keys().then(function (cacheNames) {
    return Promise.all(cacheNames.map(function (cacheName) {
      if (cacheName !== CACHE_NAME) {
        console.log('Suppression du cache obsolète:', cacheName);
        return caches["delete"](cacheName);
      }
    }));
  }));
}); // Intercepter les requêtes

self.addEventListener('fetch', function (event) {
  event.respondWith(caches.match(event.request).then(function (response) {
    return response || fetch(event.request);
  }));
});
//# sourceMappingURL=service-worker.dev.js.map
