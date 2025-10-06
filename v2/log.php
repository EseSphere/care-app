<script>
    (function() {
        const dbName = "geosoft";
        const storeName = "tbl_goesoft_carers_account";

        // Open (or create) the database
        const request = indexedDB.open(dbName, 1);

        // If the DB doesn't exist or needs an upgrade, create the store
        request.onupgradeneeded = function(event) {
            const db = event.target.result;
            if (!db.objectStoreNames.contains(storeName)) {
                db.createObjectStore(storeName, {
                    keyPath: "userId"
                });
            }
        };

        // When DB opens successfully
        request.onsuccess = function(event) {
            const db = event.target.result;

            // Check if the store exists (avoid NotFoundError)
            if (!db.objectStoreNames.contains(storeName)) {
                console.warn("Object store not found. Redirecting to signup...");
                window.location.href = "signup.php";
                return;
            }

            const transaction = db.transaction(storeName, "readonly");
            const store = transaction.objectStore(storeName);
            const getAllRequest = store.getAll();

            getAllRequest.onsuccess = function() {
                const carers = getAllRequest.result;

                if (carers.length > 0 && carers[0].user_email_address) {
                    // ✅ Email found — redirect to synchronizer.php
                    window.location.href = "synchronizer.php";
                } else {
                    // 🚫 No email found — redirect to signup.php
                    window.location.href = "signup.php";
                }
            };

            getAllRequest.onerror = function() {
                console.error("Error reading IndexedDB data.");
                window.location.href = "signup.php";
            };
        };

        request.onerror = function() {
            console.error("Error opening IndexedDB.");
            window.location.href = "signup.php";
        };
    })();
</script>