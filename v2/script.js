// 1️⃣ Register service worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('./sw.js')
        .then(() => console.log('Service Worker Registered'))
        .catch(err => console.error('SW registration failed:', err));
}

// 2️⃣ Optional: define possible values for dynamic URL variables
const dynamicValues = {
    userId: [1, 2, 3],   // Example: cache userId=1,2,3
    token: ['abc', 'xyz'] // Example: cache token=abc,xyz
};

// 3️⃣ Expand URLs with variable placeholders
function expandVariables(base, vars) {
    let results = [base];
    Object.keys(vars).forEach(key => {
        const newResults = [];
        results.forEach(r => {
            vars[key].forEach(val => {
                newResults.push(r.replace(`\${${key}}`, val).replace(`+${key}`, val));
            });
        });
        results = newResults;
    });
    return results;
}

// 4️⃣ Collect all links on page
function collectAllLinks() {
    const urls = new Set();

    // <a href="...">
    document.querySelectorAll('a[href]').forEach(a => {
        const href = a.getAttribute('href');
        if (href && href.includes('.php')) urls.add(new URL(href, location.href).href);
    });

    // <form action="...">
    document.querySelectorAll('form[action]').forEach(f => {
        const action = f.getAttribute('action');
        if (action && action.includes('.php')) urls.add(new URL(action, location.href).href);
    });

    // Inline JS: window.location / href
    document.querySelectorAll('script').forEach(script => {
        const text = script.textContent;
        if (!text) return;

        // Static URLs
        const matches = [...text.matchAll(/window\.location(?:\.href)?\s*=\s*['"`]([^'"`]*\.php[^'"`]*)['"`]/gi)];
        matches.forEach(m => urls.add(new URL(m[1], location.href).href));

        // Concatenated variables: "./page.php?id=" + userId
        const varMatches = [...text.matchAll(/window\.location(?:\.href)?\s*=\s*['"`]([^'"`]*\.php[^\n'"`]*)['"`]\s*\+\s*(\w+)/gi)];
        varMatches.forEach(m => {
            const base = m[1];
            const varName = m[2];
            if (dynamicValues[varName]) {
                expandVariables(base, { [varName]: dynamicValues[varName] }).forEach(url => urls.add(new URL(url, location.href).href));
            } else urls.add(new URL(base, location.href).href);
        });

        // Template literals: `./page.php?id=${userId}`
        const tplMatches = [...text.matchAll(/window\.location(?:\.href)?\s*=\s*`([^`]*\.php[^`]*\${(\w+)}[^`]*)`/gi)];
        tplMatches.forEach(m => {
            const base = m[1];
            const varName = m[2];
            if (dynamicValues[varName]) {
                expandVariables(base, { [varName]: dynamicValues[varName] }).forEach(url => urls.add(new URL(url, location.href).href));
            } else urls.add(new URL(base.replace(/\${\w+}/g, ''), location.href).href);
        });
    });

    return Array.from(urls);
}

// 5️⃣ Send links to service worker immediately
function sendLinksToSW() {
    if (navigator.serviceWorker.controller) {
        const links = collectAllLinks();
        if (links.length > 0) {
            navigator.serviceWorker.controller.postMessage({
                action: 'cacheLinks',
                links
            });
        }
    }
}

// 6️⃣ Initial caching after page load
window.addEventListener('load', sendLinksToSW);

// 7️⃣ Observe DOM changes dynamically
const observer = new MutationObserver(sendLinksToSW);
observer.observe(document.body, { childList: true, subtree: true });
