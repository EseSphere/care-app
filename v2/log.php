<script>
    (function() {
        const dbName = "care_app";
        const storeName = "tbl_goesoft_carers_account";

        console.log(`Opening IndexedDB: ${dbName}`);

        // Open the DB without specifying version → avoids VersionError
        const request = indexedDB.open(dbName);

        request.onupgradeneeded = function(event) {
            const db = event.target.result;
            console.log("onupgradeneeded triggered");

            if (!db.objectStoreNames.contains(storeName)) {
                console.log(`Creating object store: ${storeName}`);
                db.createObjectStore(storeName, {
                    keyPath: "userId"
                });
            } else {
                console.log(`Object store already exists: ${storeName}`);
            }
        };

        request.onsuccess = function(event) {
            const db = event.target.result;
            console.log("IndexedDB opened successfully");

            if (!db.objectStoreNames.contains(storeName)) {
                console.warn("Object store not found. Redirecting to signup.php");
                window.location.href = "signup.php";
                return;
            }

            const transaction = db.transaction(storeName, "readonly");
            const store = transaction.objectStore(storeName);
            const cursorRequest = store.openCursor();

            let hasAnyRow = false;
            let firstUserEmail = "";

            console.log(`Checking records in ${storeName}...`);

            cursorRequest.onsuccess = function(event) {
                const cursor = event.target.result;

                if (cursor) {
                    hasAnyRow = true;
                    const user = cursor.value;
                    const email = user.user_email_address || "N/A";
                    const status = (user.status2 || "").toString().trim().toLowerCase();

                    console.log(`Found record: userId=${user.userId}, email=${email}, status2=${status}`);

                    // Save first user's email for fallback
                    if (!firstUserEmail && user.user_email_address) {
                        firstUserEmail = encodeURIComponent(user.user_email_address);
                    }

                    // Check if Active
                    if (status === "active") {
                        console.log(`✅ Active user found (${email}). Redirecting to login.php`);
                        window.location.href = "login.php";
                        return; // Stop checking further
                    }

                    cursor.continue();
                } else {
                    // Cursor finished scanning
                    if (!hasAnyRow) {
                        console.warn("❌ No records found. Redirecting to signup.php");
                        window.location.href = "signup.php";
                    } else {
                        console.log(`ℹ️ Records exist but none Active. Redirecting to create-pin.php?email=${firstUserEmail}`);
                        window.location.href = `create-pin.php?email=${firstUserEmail}`;
                    }
                }
            };

            cursorRequest.onerror = function(event) {
                console.error("Error reading IndexedDB data:", event.target.error);
                window.location.href = "signup.php";
            };
        };

        request.onerror = function(event) {
            console.error("Error opening IndexedDB:", event.target.error);
            window.location.href = "signup.php";
        };
    })();
</script>