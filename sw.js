const CACHE_NAME = 'offline-recursive-v3';
const OFFLINE_FALLBACK = 'index.php';
const MAX_CONCURRENT_FETCHES = 3;

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
              .then(cache => cache.add(OFFLINE_FALLBACK))
              .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(self.clients.claim());
});

const cachedURLs = new Set();
const queue = [];
let activeFetches = 0;

// Listen for messages from script.js
self.addEventListener('message', event => {
    if (event.data.action === 'cacheLinks') {
        event.data.links.forEach(link => enqueue(link));
        processQueue();
    }
});

function enqueue(url) {
    if (!cachedURLs.has(url)) {
        cachedURLs.add(url);
        queue.push(url);
    }
}

function processQueue() {
    while (activeFetches < MAX_CONCURRENT_FETCHES && queue.length > 0) {
        const url = queue.shift();
        activeFetches++;
        cachePage(url).finally(() => {
            activeFetches--;
            processQueue();
        });
    }
}

async function cachePage(url) {
    try {
        const cache = await caches.open(CACHE_NAME);
        const response = await fetch(url);
        if (!response.ok || response.type !== 'basic') return;
        await cache.put(url, response.clone());

        // Parse HTML for internal PHP links and enqueue them recursively
        const text = await response.text();
        const links = Array.from(text.matchAll(/href=["'](.*?)["']/g))
                           .map(match => new URL(match[1], url).href)
                           .filter(href => href.startsWith(location.origin) && href.includes('.php'));

        links.forEach(link => enqueue(link));
        processQueue();
    } catch (err) {
        console.warn('Failed to cache', url, err);
    }
}

// Fetch handler (offline-first)
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request)
                    .then(netResp => {
                        if (event.request.method === 'GET' && netResp.ok) {
                            caches.open(CACHE_NAME).then(cache => cache.put(event.request, netResp.clone()));
                        }
                        return netResp;
                    })
                    .catch(() => {
                        if (event.request.headers.get('accept')?.includes('text/html')) {
                            return caches.match(OFFLINE_FALLBACK);
                        }
                    });
            })
    );
});
