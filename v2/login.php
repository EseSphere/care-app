<?php require_once('header-log.php'); ?>

<div class="mt-5" data-aos="zoom-in" data-aos-duration="1000" style="z-index:1;">
    <div style="border: none; height:100vh;" class="card text-center">
        <h3 class="mb-3 fw-bold">Signin</h3>
        <div class="container">
            <input style="background-color:inherit !important; color:#000 !important;"
                placeholder="&#8226&#8226&#8226&#8226"
                type="password" maxlength="4" id="pin"
                class="pin-input form-control-plaintext mb-4" readonly>
            <div class="keypad d-grid gap-2 mt-5">
                <div class="row">
                    <div class="col-4"><button class="btn btn-light" onclick="pressNum(1)">1</button></div>
                    <div class="col-4"><button class="btn btn-light" onclick="pressNum(2)">2</button></div>
                    <div class="col-4"><button class="btn btn-light" onclick="pressNum(3)">3</button></div>
                </div>
                <div class="row">
                    <div class="col-4"><button class="btn btn-light" onclick="pressNum(4)">4</button></div>
                    <div class="col-4"><button class="btn btn-light" onclick="pressNum(5)">5</button></div>
                    <div class="col-4"><button class="btn btn-light" onclick="pressNum(6)">6</button></div>
                </div>
                <div class="row">
                    <div class="col-4"><button class="btn btn-light" onclick="pressNum(7)">7</button></div>
                    <div class="col-4"><button class="btn btn-light" onclick="pressNum(8)">8</button></div>
                    <div class="col-4"><button class="btn btn-light" onclick="pressNum(9)">9</button></div>
                </div>
                <div class="row">
                    <div class="col-4"><button class="btn btn-clear" onclick="clearPin()">C</button></div>
                    <div class="col-4"><button class="btn btn-light" onclick="pressNum(0)">0</button></div>
                    <div class="col-4"><button class="btn btn-login" onclick="login()"><i class="bi bi-box-arrow-in-right"></i></button></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let pinInput = document.getElementById("pin");

    function pressNum(num) {
        if (pinInput.value.length < 4) {
            pinInput.value += num;
        }
    }

    function clearPin() {
        pinInput.value = "";
    }

    // SHA-256 hash function for PIN
    async function hashPin(pin) {
        const encoder = new TextEncoder();
        const data = encoder.encode(pin);
        const hashBuffer = await crypto.subtle.digest('SHA-256', data);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    // Login function
    async function login() {
        if (pinInput.value.length !== 4) {
            alert("Please enter a 4-digit PIN");
            return;
        }

        const enteredHash = await hashPin(pinInput.value);

        // Open IndexedDB
        const dbRequest = indexedDB.open("geosoft", 1);

        dbRequest.onerror = () => alert("Failed to open IndexedDB");

        dbRequest.onsuccess = (event) => {
            const db = event.target.result;
            const transaction = db.transaction("tbl_goesoft_carers_account", "readonly");
            const store = transaction.objectStore("tbl_goesoft_carers_account");

            const getAllRequest = store.getAll();

            getAllRequest.onsuccess = function() {
                const users = getAllRequest.result;
                if (users.length === 0) {
                    alert("No user found. Please signup first.");
                    return;
                }

                const user = users[0]; // Assuming single user
                if (user.user_password === enteredHash) {
                    // Correct PIN, redirect to home
                    window.location.href = "syncing.php";
                } else {
                    alert("❌ Incorrect PIN");
                    clearPin();
                }
            };

            getAllRequest.onerror = () => alert("Failed to read user data from IndexedDB");
        };
    }
</script>

<?php require_once('footer-log.php'); ?>