<?php require_once('header-log.php'); ?>

<div class="mt-5" data-aos="zoom-in" data-aos-duration="1000" style="z-index:1;">
    <div style="border: none; height:100vh;" class="card text-center" id="pinCard">
        <h3 class="mb-3 fw-bold">Signin</h3>
        <div class="container">
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
                    <div class="col-4"><button class="btn btn-login" id="loginBtn"><i class="bi bi-box-arrow-in-right"></i></button></div>
                </div>
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

    /* Shake animation for wrong PIN */
    @keyframes shake {
        0% {
            transform: translateX(0);
        }

        20% {
            transform: translateX(-10px);
        }

        40% {
            transform: translateX(10px);
        }

        60% {
            transform: translateX(-10px);
        }

        80% {
            transform: translateX(10px);
        }

        100% {
            transform: translateX(0);
        }
    }

    .shake {
        animation: shake 0.4s;
    }
</style>

<script>
    const pinInput = document.getElementById("pin");
    const keypad = document.getElementById("keypad");
    const clearBtn = document.getElementById("clearPin");
    const loginBtn = document.getElementById("loginBtn");
    const pinCard = document.getElementById("pinCard");

    let actualPin = "";

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

    function animateButton(btn) {
        btn.classList.add("btn-pop");
        setTimeout(() => btn.classList.remove("btn-pop"), 150);
    }

    keypad.querySelectorAll("[data-num]").forEach(btn => {
        fastClickHandler(btn, () => pressNum(btn.getAttribute("data-num")));
    });

    fastClickHandler(clearBtn, clearPin);
    fastClickHandler(loginBtn, login);

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

    function shakeCard() {
        pinCard.classList.add("shake");
        setTimeout(() => pinCard.classList.remove("shake"), 400);
    }

    async function login() {
        if (actualPin.length !== 4) {
            alert("Please enter a 4-digit PIN");
            return;
        }

        const enteredHash = await hashPin(actualPin);
        const dbRequest = indexedDB.open("care_app");

        dbRequest.onerror = (e) => {
            console.error("IndexedDB error:", e.target.error);
            alert("Failed to open IndexedDB");
        };

        dbRequest.onsuccess = (event) => {
            const db = event.target.result;

            if (!db.objectStoreNames.contains("tbl_goesoft_carers_account")) {
                alert("No user data found. Please create a PIN first.");
                window.location.href = "create-pin.php";
                return;
            }

            const transaction = db.transaction("tbl_goesoft_carers_account", "readonly");
            const store = transaction.objectStore("tbl_goesoft_carers_account");
            const getAllRequest = store.getAll();

            getAllRequest.onerror = () => alert("Failed to read user data from IndexedDB");

            getAllRequest.onsuccess = function() {
                const users = getAllRequest.result;
                if (users.length === 0) {
                    alert("No user found. Please create a PIN first.");
                    window.location.href = "create-pin.php";
                    return;
                }

                const user = users[0];

                if (!user.user_password) {
                    alert("No PIN set. Please create a PIN first.");
                    window.location.href = "create-pin.php";
                    return;
                }

                if (user.user_password === enteredHash) {
                    window.location.href = "home.php";
                } else {
                    shakeCard(); // ❌ Shake for wrong PIN
                    clearPin();
                }
            };
        };
    }
</script>

<?php require_once('footer-log.php'); ?>