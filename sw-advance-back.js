const CACHE_NAME = 'offline-recursive-v5';
const OFFLINE_FALLBACK = 'index.php';
const MAX_CONCURRENT_FETCHES = 3;

const cachedURLs = new Set();
const queue = [];
let activeFetches = 0;

// Install: cache offline fallback
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
              .then(cache => cache.add(OFFLINE_FALLBACK))
              .then(() => self.skipWaiting())
    );
});

// Activate: take control
self.addEventListener('activate', event => {
    event.waitUntil(self.clients.claim());
});

// Message listener for manual link caching
self.addEventListener('message', event => {
    if (event.data.action === 'cacheLinks') {
        event.data.links.forEach(link => enqueue(link));
        processQueue();
    }
});

// Enqueue URL if not already cached
function enqueue(url) {
    if (!cachedURLs.has(url)) {
        cachedURLs.add(url);
        queue.push(url);
    }
}

// Process the queue with concurrency limit
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

// Cache a page and find links recursively
async function cachePage(url) {
    try {
        const cache = await caches.open(CACHE_NAME);

        const response = await fetch(url);
        if (!response.ok) return;

        const responseClone = response.clone();
        await cache.put(url, responseClone);

        const text = await response.text();

        // HTML links: <a href="...">
        const htmlLinks = Array.from(text.matchAll(/href\s*=\s*["'](.*?)["']/gi))
            .map(m => new URL(m[1], url).href);

        // Form actions: <form action="...">
        const formLinks = Array.from(text.matchAll(/<form[^>]*action\s*=\s*["'](.*?)["']/gi))
            .map(m => new URL(m[1], url).href);

        // JS redirects: window.location or window.location.href
        const jsLinks = Array.from(text.matchAll(/window\.location(?:\.href)?\s*=\s*["'](.*?)["']/gi))
            .map(m => new URL(m[1], url).href);

        // PHP header redirects: header("Location: ...")
        const phpLinks = Array.from(text.matchAll(/header\s*\(\s*['"]\s*Location\s*:\s*(.*?)['"]\s*\)/gi))
            .map(m => {
                try { return new URL(m[1], url).href; } 
                catch { return null; }
            })
            .filter(Boolean);

        // Combine all links
        const allLinks = [...htmlLinks, ...formLinks, ...jsLinks, ...phpLinks];

        // Enqueue only internal PHP pages
        allLinks
            .filter(link => link.startsWith(location.origin) && link.includes('.php'))
            .forEach(link => enqueue(link));

    } catch (err) {
        console.warn('Failed to cache', url, err);
    }
}

// Offline-first fetch handler
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(cachedResp => {
            return cachedResp || fetch(event.request).then(netResp => {
                if (event.request.method === 'GET' && netResp.ok) {
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, netResp.clone()));
                }
                return netResp;
            }).catch(() => {
                if (event.request.headers.get('accept')?.includes('text/html')) {
                    return caches.match(OFFLINE_FALLBACK);
                }
            });
        })
    );
});
