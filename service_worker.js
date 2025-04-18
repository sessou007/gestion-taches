// Nom du cache
const CACHE_NAME = 'v1';

// Fichiers à mettre en cache
const URLS_TO_CACHE = [
    '/',
    '/index.html',
    '/styles.css',
    '/script.js',
    '/t/ALRMClok_Reveil electronique sonnerie 1 (ID 0035)_LS.mp3',
];

// Installation du Service Worker
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Mise en cache des fichiers');
                return cache.addAll(URLS_TO_CACHE);
            })
    );
});

// Activation du Service Worker
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Suppression du cache obsolète:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Intercepter les requêtes
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
    );
});