if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('js/sw.js')
        .then(() => console.log('Service Worker Registered'))
        .catch(err => console.error('SW registration failed:', err));
}

// Send all internal PHP links to service worker
function sendLinksToSW() {
    const links = Array.from(document.querySelectorAll('a'))
                       .map(a => a.href)
                       .filter(href => href.startsWith(location.origin) && href.includes('.php'));

    if (navigator.serviceWorker.controller) {
        navigator.serviceWorker.controller.postMessage({
            action: 'cacheLinks',
            links: links
        });
    }
}

// Initial caching after page load
window.addEventListener('load', () => sendLinksToSW());

// Observe DOM mutations for dynamic links (e.g., from IndexedDB)
const observer = new MutationObserver(() => {
    sendLinksToSW();
});
observer.observe(document.body, { childList: true, subtree: true });
