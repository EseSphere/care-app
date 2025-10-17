<?php require_once('header-log.php'); ?>

<div class="mt-5" data-aos="zoom-in" data-aos-duration="1000" style="z-index:1;">
    <div style="border: none; height:100vh;" class="card text-center">
        <h3 class="mb-3 fw-bold">Signin</h3>
        <div class="container">
            <input style="background-color:inherit !important; color:#000 !important;" placeholder="&#8226&#8226&#8226&#8226" type="password" maxlength="4" id="pin" class="pin-input form-control-plaintext mb-4" readonly>
            <div class="keypad d-grid gap-2 mt-5">
                <div class="row">
                    <div class="col-4">
                        <button class="btn btn-light" onclick="pressNum(1)">1</button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-light" onclick="pressNum(2)">2</button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-light" onclick="pressNum(3)">3</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <button class="btn btn-light" onclick="pressNum(4)">4</button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-light" onclick="pressNum(5)">5</button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-light" onclick="pressNum(6)">6</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <button class="btn btn-light" onclick="pressNum(7)">7</button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-light" onclick="pressNum(8)">8</button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-light" onclick="pressNum(9)">9</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <button class="btn btn-clear" onclick="clearPin()">C</button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-light" onclick="pressNum(0)">0</button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-login" onclick="login()"><i class="bi bi-box-arrow-in-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let pinInput = document.getElementById("pin");
    let correctPin = "1234"; // Example PIN - replace with backend check

    function pressNum(num) {
        if (pinInput.value.length < 4) {
            pinInput.value += num;
        }
    }

    function clearPin() {
        pinInput.value = "";
    }

    function login() {
        if (pinInput.value === correctPin) {
            window.location.href = "home.php";
        } else {
            alert("❌ Incorrect PIN");
            clearPin();
        }
    }
</script>

<?php require_once('footer-log.php'); ?>