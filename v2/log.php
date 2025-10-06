<script>
    //The code below check if any email exist in indexedDB and redirect to synchronizer or signup page
    //If email exist redirect to synchronizer.php else redirect to signup.php
    (function() {
        const dbName = "geosoft";
        const storeName = "tbl_goesoft_carers_account";
        const request = indexedDB.open(dbName, 1);

        request.onupgradeneeded = function(event) {
            const db = event.target.result;
            if (!db.objectStoreNames.contains(storeName)) {
                db.createObjectStore(storeName, {
                    keyPath: "userId"
                });
            }
        };

        request.onsuccess = function(event) {
            const db = event.target.result;

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
                    window.location.href = "synchronizer.php";
                } else {
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