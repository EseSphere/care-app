<?php require_once('header-log.php'); ?>

<div class="mt-5" data-aos="zoom-in" data-aos-duration="1000" style="z-index:1000;">
    <div style="border: none; z-index:1000;" class="p-4 text-center">
        <h3 class="mb-3 fw-bold">Account Setup</h3>
        <div class="alert alert-info" style="border-radius: 12px; border-left:7px solid rgba(64, 115, 158,1.0); padding:8px; margin-bottom:40px; text-align:left;">
            Signing in on a new device? Please use the form below to set up your account.
        </div>
        <form id="emailForm" class="w-100 bg-light p-3 rounded">
            <h6 class="mb-3 fw-semibold w-100 justify-start items-start text-start">Enter Email</h6>
            <input style="height: 50px;" type="email" id="email" class="form-control mb-2" placeholder="Enter your email" required>
            <div class="form-group w-100 mt-2 justify-start items-start text-start">
                <button type="submit" id="submitBtn" style="height: 45px;" class="btn btn-primary">Continue</button>
            </div>
            <div id="loader" class="mt-2" style="display:none; color: #007bff;">Checking account...</div>
        </form>
    </div>
</div>

<script>
    const dbName = "care_app";
    const storeName = "tbl_goesoft_carers_account";

    // Open or create IndexedDB
    function openIndexedDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(dbName, 1);

            request.onupgradeneeded = function(event) {
                const db = event.target.result;
                if (!db.objectStoreNames.contains(storeName)) {
                    const store = db.createObjectStore(storeName, {
                        keyPath: "userId"
                    });
                    store.createIndex("user_email_address", "user_email_address", {
                        unique: false
                    });
                }
            };

            request.onsuccess = function(event) {
                resolve(event.target.result);
            };

            request.onerror = function() {
                reject("Failed to open IndexedDB");
            };
        });
    }

    // Save or replace user in IndexedDB
    function saveOrUpdateUserInIndexedDB(user) {
        return new Promise(async (resolve, reject) => {
            try {
                const db = await openIndexedDB();
                const transaction = db.transaction(storeName, "readwrite");
                const store = transaction.objectStore(storeName);
                const request = store.put(user); // put = add or replace
                request.onsuccess = () => resolve();
                request.onerror = () => reject("Failed to save/update user in IndexedDB");
            } catch (err) {
                reject(err);
            }
        });
    }

    // Handle form submission
    document.getElementById("emailForm").addEventListener("submit", async function(e) {
        e.preventDefault();
        const email = document.getElementById("email").value.trim();
        if (!email) return;

        const submitBtn = document.getElementById("submitBtn");
        const loader = document.getElementById("loader");

        loader.style.display = "block";
        submitBtn.disabled = true;

        try {
            const response = await fetch("signup_backend.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    email: email
                })
            });

            const text = await response.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (err) {
                console.error("Invalid JSON response:", text);
                throw new Error("Server returned invalid response.");
            }

            if (data.exists && data.user) {
                await saveOrUpdateUserInIndexedDB(data.user); // Save or replace in IndexedDB
                window.location.href = "create-pin.php?email=" + encodeURIComponent(email);
            } else {
                alert(data.message || "Email not found. Please sign up first.");
            }
        } catch (err) {
            console.error(err);
            alert("Something went wrong. Try again.");
        } finally {
            loader.style.display = "none";
            submitBtn.disabled = false;
        }
    });
</script>

<?php require_once('footer-log.php'); ?>