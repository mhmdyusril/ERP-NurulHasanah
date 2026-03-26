// ERP RA Nurul Hasanah — Service Worker v1.0
const CACHE_NAME = 'erp-nurul-hasanah-v1';
const OFFLINE_URL = '/dashboard';

// Assets to pre-cache on install
const STATIC_ASSETS = [
    '/',
    '/dashboard',
    '/manifest.json',
    'https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap',
    'https://unpkg.com/lucide@latest',
];

// ─── Install ────────────────────────────────────────────────────────────────
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS).catch(() => {
                // Silently fail on individual asset failures
            });
        })
    );
    self.skipWaiting();
});

// ─── Activate ────────────────────────────────────────────────────────────────
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        })
    );
    self.clients.claim();
});

// ─── Fetch Strategy ──────────────────────────────────────────────────────────
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET and cross-origin (except fonts/CDN)
    if (request.method !== 'GET') return;
    if (url.origin !== location.origin && !url.hostname.includes('bunny.net') && !url.hostname.includes('unpkg.com') && !url.hostname.includes('cdn.jsdelivr.net')) {
        return;
    }

    // API / Gemini / POST-like endpoints — always network
    if (url.pathname.startsWith('/gemini') || url.pathname.startsWith('/api')) {
        return;
    }

    // Static assets (CSS, JS, fonts, images) — Cache First
    if (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'font' ||
        request.destination === 'image' ||
        url.pathname.startsWith('/build/')
    ) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;
                return fetch(request).then((response) => {
                    if (!response || response.status !== 200) return response;
                    const cloned = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, cloned));
                    return response;
                });
            })
        );
        return;
    }

    // HTML pages — Network First, fallback to cache / offline
    event.respondWith(
        fetch(request)
            .then((response) => {
                if (!response || response.status !== 200 || response.type === 'opaque') {
                    return response;
                }
                const cloned = response.clone();
                caches.open(CACHE_NAME).then((cache) => cache.put(request, cloned));
                return response;
            })
            .catch(() => {
                // Offline fallback
                return caches.match(request).then((cached) => {
                    return cached || caches.match(OFFLINE_URL);
                });
            })
    );
});
