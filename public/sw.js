const CACHE_NAME = 'eslab-lims-cache-v2';
const OFFLINE_URLS = [
    '/offline.html',
    '/limsenviro/offline.html',
    '/logo_eslab.jpg',
    '/favicon.ico',
    '/vendor/fontawesome/css/all.min.css',
    '/vendor/fonts/fonts.css'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return Promise.allSettled(
                OFFLINE_URLS.map(url => cache.add(url).catch(err => console.warn('Cache item skipped:', url)))
            );
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', event => {
    const request = event.request;

    // 1. Navigation Requests (User clicks a link or reloads an HTML page)
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => {
                return caches.match('/limsenviro/offline.html')
                    .then(res => res || caches.match('/offline.html'));
            })
        );
        return;
    }

    // 2. Static Vendor Assets (Cache first, then network fallback)
    if (request.url.includes('/vendor/') || request.url.includes('/webfonts/')) {
        event.respondWith(
            caches.match(request).then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }
                return fetch(request).then(networkResponse => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then(cache => cache.put(request, responseClone));
                    }
                    return networkResponse;
                });
            })
        );
        return;
    }

    // 3. All other requests: Network first with fallback to cache
    event.respondWith(
        fetch(request).catch(() => caches.match(request))
    );
});
