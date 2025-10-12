<script>
    (function() {
        const dbName = "care_app";
        const storeName = "tbl_goesoft_carers_account";
        console.log(`Opening IndexedDB: ${dbName}`);

        const request = indexedDB.open(dbName, 1);

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
                console.warn("Object store not found. Redirecting to signup...");
                window.location.href = "signup.php";
                return;
            }

            const transaction = db.transaction(storeName, "readonly");
            const store = transaction.objectStore(storeName);
            const getAllRequest = store.getAll();
            console.log(`Fetching all records from ${storeName}`);

            getAllRequest.onsuccess = function() {
                const carers = getAllRequest.result;
                console.log("Data fetched from IndexedDB:", carers);

                if (!carers || carers.length === 0) {
                    console.log("No carers found. Redirecting to signup...");
                    window.location.href = "signup.php";
                    return;
                }

                // Log each record
                carers.forEach((u, index) => {
                    console.log(`Record ${index}: userId=${u.userId}, email=${u.user_email_address || 'N/A'}, status2=${u.status2 || 'N/A'}`);
                });

                // Normalize status to lowercase for comparison
                const activeUser = carers.find(u => (u.status2 || "").trim().toLowerCase() === "active");
                const disabledUser = carers.find(u => (u.status2 || "").trim().toLowerCase() === "disabled");

                if (activeUser) {
                    console.log(`Active user found (userId=${activeUser.userId}). Redirecting to login...`);
                    window.location.href = "login.php";
                    return;
                }

                if (disabledUser) {
                    const email = encodeURIComponent(disabledUser.user_email_address || "");
                    console.log(`Disabled user found (userId=${disabledUser.userId}). Redirecting to create-pin.php with email: ${email}`);
                    window.location.href = `create-pin.php?email=${email}`;
                    return;
                }

                console.log("No matching users found. Redirecting to signup...");
                window.location.href = "signup.php";
            };

            getAllRequest.onerror = function(event) {
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