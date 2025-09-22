// Service Worker for PWA functionality
const CACHE_NAME = 'ali-portfolio-v1.0.0';
const STATIC_CACHE = 'static-v1.0.0';
const DYNAMIC_CACHE = 'dynamic-v1.0.0';

// Resources to cache immediately
const STATIC_ASSETS = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/favicon.ico',
    '/logo/logo.png',
    '/manifest.json',
    // Add other critical assets
];

// Resources to cache on request
const DYNAMIC_ASSETS = [
    '/portfolio',
    '/articles',
    '/contact',
    // API endpoints
    '/api/projects',
    '/api/articles',
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
    console.log('Service Worker installing...');

    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                console.log('Caching static assets...');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                console.log('Static assets cached successfully');
                return self.skipWaiting();
            })
            .catch((error) => {
                console.error('Failed to cache static assets:', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('Service Worker activating...');

    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                            console.log('Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('Service Worker activated');
                return self.clients.claim();
            })
    );
});

// Fetch event - serve cached content when offline
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip cross-origin requests
    if (url.origin !== location.origin) {
        return;
    }

    // Handle API requests differently
    if (url.pathname.startsWith('/api/')) {
        event.respondWith(handleApiRequest(request));
        return;
    }

    // Handle image requests
    if (request.destination === 'image') {
        event.respondWith(handleImageRequest(request));
        return;
    }

    // Handle navigation requests
    if (request.mode === 'navigate') {
        event.respondWith(handleNavigationRequest(request));
        return;
    }

    // Handle other requests
    event.respondWith(handleOtherRequests(request));
});

// Handle API requests with cache-first strategy for GET requests
async function handleApiRequest(request) {
    if (request.method === 'GET') {
        try {
            const cachedResponse = await caches.match(request);
            if (cachedResponse) {
                // Serve from cache and update in background
                updateCacheInBackground(request);
                return cachedResponse;
            }

            // Not in cache, fetch from network
            const networkResponse = await fetch(request);
            if (networkResponse.ok) {
                const cache = await caches.open(DYNAMIC_CACHE);
                cache.put(request, networkResponse.clone());
            }
            return networkResponse;
        } catch (error) {
            console.error('API request failed:', error);
            return new Response(
                JSON.stringify({ error: 'Network error', offline: true }),
                {
                    status: 503,
                    headers: { 'Content-Type': 'application/json' }
                }
            );
        }
    }

    // For non-GET requests, always try network first
    try {
        return await fetch(request);
    } catch (error) {
        return new Response(
            JSON.stringify({ error: 'Network error' }),
            {
                status: 503,
                headers: { 'Content-Type': 'application/json' }
            }
        );
    }
}

// Handle image requests with cache-first strategy
async function handleImageRequest(request) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.error('Image request failed:', error);
        // Return a fallback image or placeholder
        return caches.match('/images/placeholder.png') ||
               new Response('', { status: 404 });
    }
}

// Handle navigation requests with network-first strategy
async function handleNavigationRequest(request) {
    try {
        // Try network first for navigation
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.error('Navigation request failed:', error);

        // Fallback to cache
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        // Ultimate fallback to offline page
        const offlinePage = await caches.match('/offline.html');
        if (offlinePage) {
            return offlinePage;
        }

        // Minimal offline response
        return new Response(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Offline - Ali Sadikin Portfolio</title>
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
                <style>
                    body {
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                        text-align: center;
                        padding: 2rem;
                        background: #0f0f23;
                        color: white;
                    }
                    .container { max-width: 500px; margin: 0 auto; }
                    .icon { font-size: 4rem; margin-bottom: 1rem; }
                    h1 { color: #8b5cf6; margin-bottom: 1rem; }
                    p { color: #cbd5e1; line-height: 1.6; }
                    button {
                        background: #8b5cf6;
                        color: white;
                        border: none;
                        padding: 0.75rem 1.5rem;
                        border-radius: 0.5rem;
                        cursor: pointer;
                        margin-top: 1rem;
                    }
                </style>
            </head>
            <body>
                <div class=\"container\">
                    <div class=\"icon\">📱</div>
                    <h1>You're Offline</h1>
                    <p>It looks like you're not connected to the internet. You can still browse cached pages and content that was previously loaded.</p>
                    <button onclick=\"window.location.reload()\">Try Again</button>
                </div>
            </body>
            </html>
        `, {
            headers: { 'Content-Type': 'text/html' }
        });
    }
}

// Handle other requests with cache-first strategy
async function handleOtherRequests(request) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.error('Request failed:', error);
        return new Response('Network error', { status: 503 });
    }
}

// Background cache update
async function updateCacheInBackground(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
    } catch (error) {
        console.log('Background update failed:', error);
    }
}

// Handle messages from the main thread
self.addEventListener('message', (event) => {
    const { type, payload } = event.data;

    switch (type) {
        case 'CACHE_CRITICAL_RESOURCES':
            cacheCriticalResources(payload.urls);
            break;

        case 'CLEAR_CACHE':
            clearAllCaches();
            break;

        case 'GET_CACHE_SIZE':
            getCacheSize().then(size => {
                event.ports[0].postMessage({ type: 'CACHE_SIZE', size });
            });
            break;

        default:
            console.log('Unknown message type:', type);
    }
});

// Cache critical resources on demand
async function cacheCriticalResources(urls) {
    try {
        const cache = await caches.open(DYNAMIC_CACHE);
        await cache.addAll(urls);
        console.log('Critical resources cached:', urls);
    } catch (error) {
        console.error('Failed to cache critical resources:', error);
    }
}

// Clear all caches
async function clearAllCaches() {
    try {
        const cacheNames = await caches.keys();
        await Promise.all(cacheNames.map(name => caches.delete(name)));
        console.log('All caches cleared');
    } catch (error) {
        console.error('Failed to clear caches:', error);
    }
}

// Get total cache size
async function getCacheSize() {
    try {
        const cacheNames = await caches.keys();
        let totalSize = 0;

        for (const name of cacheNames) {
            const cache = await caches.open(name);
            const requests = await cache.keys();

            for (const request of requests) {
                const response = await cache.match(request);
                if (response) {
                    const blob = await response.blob();
                    totalSize += blob.size;
                }
            }
        }

        return totalSize;
    } catch (error) {
        console.error('Failed to calculate cache size:', error);
        return 0;
    }
}

// Periodic cache cleanup (runs every hour when SW is active)
setInterval(async () => {
    try {
        const cache = await caches.open(DYNAMIC_CACHE);
        const requests = await cache.keys();

        // Remove old entries (older than 7 days)
        const sevenDaysAgo = Date.now() - (7 * 24 * 60 * 60 * 1000);

        for (const request of requests) {
            const response = await cache.match(request);
            if (response) {
                const dateHeader = response.headers.get('date');
                if (dateHeader && new Date(dateHeader).getTime() < sevenDaysAgo) {
                    await cache.delete(request);
                    console.log('Removed old cache entry:', request.url);
                }
            }
        }
    } catch (error) {
        console.error('Cache cleanup failed:', error);
    }
}, 60 * 60 * 1000); // 1 hour