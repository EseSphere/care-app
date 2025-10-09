const CACHE_NAME = 'geosoft-offline-v4';
const OFFLINE_FALLBACK = 'index.php';
const MAX_CONCURRENT_FETCHES = 3;

const cachedURLs = new Set();
const queue = [];
let activeFetches = 0;

// Install: cache fallback
self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.add(OFFLINE_FALLBACK))
            .then(() => self.skipWaiting())
    );
});

// Activate
self.addEventListener('activate', e => e.waitUntil(self.clients.claim()));

// Listen for links from pages
self.addEventListener('message', e => {
    if (e.data.action === 'cacheLinks' && Array.isArray(e.data.links)) {
        e.data.links.forEach(link => enqueue(link));
        processQueue();
    }
});

// Enqueue a URL if not cached
function enqueue(url) {
    if (!cachedURLs.has(url)) {
        cachedURLs.add(url);
        queue.push(url);
    }
}

// Process queue with concurrency limit
function processQueue() {
    while (activeFetches < MAX_CONCURRENT_FETCHES && queue.length > 0) {
        const url = queue.shift();
        activeFetches++;
        cachePage(url).finally(() => {
            activeFetches--;
            if (queue.length > 0) processQueue();
        });
    }
}

// Cache a page and extract further links recursively
async function cachePage(url) {
    try {
        const cache = await caches.open(CACHE_NAME);
        const response = await fetch(url);
        if (!response.ok) return;

        const cacheResponse = response.clone();
        const textResponse = response.clone();
        await cache.put(url, cacheResponse);

        const text = await textResponse.text();

        // Extract <a> links
        const htmlLinks = Array.from(text.matchAll(/href\s*=\s*["'](.*?)["']/gi))
                               .map(m => new URL(m[1], url).href);

        // Extract <form> action links
        const formLinks = Array.from(text.matchAll(/<form[^>]*action\s*=\s*["'](.*?)["']/gi))
                               .map(m => new URL(m[1], url).href);

        // Extract JS window.location links
        const jsLinks = Array.from(text.matchAll(/window\.location(?:\.href)?\s*=\s*['"`]([^'"`]*\.php[^'"`]*)['"`]/gi))
                             .map(m => new URL(m[1], url).href);

        // Extract PHP redirects
        const phpLinks = Array.from(text.matchAll(/header\s*\(\s*['"]\s*Location\s*:\s*(.*?)['"]\s*\)/gi))
                              .map(m => { try { return new URL(m[1], url).href; } catch { return null; } })
                              .filter(Boolean);

        // Include any query-string links
        const queryLinks = Array.from(text.matchAll(/\.php\?[^"' >]+/gi))
                                .map(m => new URL(m[0], url).href);

        // Merge all links
        const allLinks = [...htmlLinks, ...formLinks, ...jsLinks, ...phpLinks, ...queryLinks];

        // Enqueue internal PHP links only
        allLinks.filter(l => l.startsWith(location.origin) && l.includes('.php'))
                .forEach(l => enqueue(l));

    } catch (err) {
        console.warn('Failed to cache', url, err);
    }
}

// Offline-first fetch
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(cached => {
            return cached || fetch(event.request).then(netResp => {
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
