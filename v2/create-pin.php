<?php require_once('header-log.php'); ?>

<div class="mt-5" data-aos="zoom-in" data-aos-duration="1000" style="z-index:1;">
    <div style="border: none; height:100vh;" class="card p-4 text-center mt-3">
        <h3 class="mb-3 fw-bold">Create PIN</h3>
        <input
            style="background-color:inherit !important; color:#000 !important; font-size:3rem; letter-spacing:0.5rem; text-align:center; font-weight:bold;"
            placeholder="••••"
            type="text" maxlength="4" id="pin"
            class="pin-input form-control-plaintext mb-4" readonly>

        <div class="keypad d-grid gap-2 mt-5" id="keypad">
            <div class="row">
                <div class="col-4"><button class="btn btn-light" data-num="1">1</button></div>
                <div class="col-4"><button class="btn btn-light" data-num="2">2</button></div>
                <div class="col-4"><button class="btn btn-light" data-num="3">3</button></div>
            </div>
            <div class="row">
                <div class="col-4"><button class="btn btn-light" data-num="4">4</button></div>
                <div class="col-4"><button class="btn btn-light" data-num="5">5</button></div>
                <div class="col-4"><button class="btn btn-light" data-num="6">6</button></div>
            </div>
            <div class="row">
                <div class="col-4"><button class="btn btn-light" data-num="7">7</button></div>
                <div class="col-4"><button class="btn btn-light" data-num="8">8</button></div>
                <div class="col-4"><button class="btn btn-light" data-num="9">9</button></div>
            </div>
            <div class="row">
                <div class="col-4"><button class="btn btn-clear" id="clearPin">C</button></div>
                <div class="col-4"><button class="btn btn-light" data-num="0">0</button></div>
                <div class="col-4"><button class="btn btn-login" id="savePin"><i class="bi bi-box-arrow-in-right"></i></button></div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Button pop animation */
    .btn-pop {
        transform: scale(1.2);
        transition: transform 0.15s ease;
    }
</style>

<script>
    const pinInput = document.getElementById("pin");
    const keypad = document.getElementById("keypad");
    const clearBtn = document.getElementById("clearPin");
    const saveBtn = document.getElementById("savePin");

    let actualPin = ""; // store actual digits

    // Faster button response + animation
    function fastClickHandler(el, callback) {
        el.addEventListener("touchstart", (e) => {
            e.preventDefault();
            animateButton(el);
            requestAnimationFrame(callback);
        }, {
            passive: false
        });

        el.addEventListener("mousedown", (e) => {
            e.preventDefault();
            animateButton(el);
            requestAnimationFrame(callback);
        });
    }

    // Button pop effect
    function animateButton(btn) {
        btn.classList.add("btn-pop");
        setTimeout(() => btn.classList.remove("btn-pop"), 150);
    }

    // Delegate number keys
    keypad.querySelectorAll("[data-num]").forEach(btn => {
        fastClickHandler(btn, () => pressNum(btn.getAttribute("data-num")));
    });

    fastClickHandler(clearBtn, clearPin);
    fastClickHandler(saveBtn, savePin);

    function pressNum(num) {
        if (actualPin.length < 4) {
            actualPin += num;

            // Show number briefly
            let visible = pinInput.value.split("");
            visible[actualPin.length - 1] = num;
            pinInput.value = visible.join("").padEnd(actualPin.length, num);

            setTimeout(() => {
                pinInput.value = "•".repeat(actualPin.length);
            }, 300);
        }
    }

    function clearPin() {
        actualPin = "";
        pinInput.value = "";
    }

    async function hashPin(pin) {
        const encoder = new TextEncoder();
        const data = encoder.encode(pin);
        const hashBuffer = await crypto.subtle.digest('SHA-256', data);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    async function savePin() {
        if (actualPin.length !== 4) {
            alert("Please enter a 4-digit PIN");
            return;
        }

        const encryptedPin = await hashPin(actualPin);
        const dbRequest = indexedDB.open("care_app", 1);

        dbRequest.onerror = () => alert("Failed to open IndexedDB");

        dbRequest.onsuccess = (event) => {
            const db = event.target.result;
            const transaction = db.transaction("tbl_goesoft_carers_account", "readwrite");
            const store = transaction.objectStore("tbl_goesoft_carers_account");
            const getAllRequest = store.getAll();

            getAllRequest.onsuccess = function() {
                const users = getAllRequest.result;
                if (users.length === 0) {
                    alert("No user found in IndexedDB");
                    return;
                }

                const user = users[0];
                user.user_password = encryptedPin;
                user.status2 = "active";

                const updateRequest = store.put(user);
                updateRequest.onsuccess = () => window.location.href = "synchronizer.php";
                updateRequest.onerror = () => alert("Failed to save PIN. Try again.");
            };

            getAllRequest.onerror = () => alert("Failed to read user data from IndexedDB");
        };
    }
</script>

<?php require_once('footer-log.php'); ?>