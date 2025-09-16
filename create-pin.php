<?php require_once('header2.php'); ?>
<section style="background-image:url(./images/23-bgs.jpg); background-size:cover; position: fixed; height: 350px; top: 0; left: 0; width: 100%; z-index: 0;">
    <div class="d-flex justify-content-center text-white align-items-center h-75">
        <h3>Create Pin</h3>
    </div>
</section>
<div data-aos="zoom-in" data-aos-duration="1000" style="margin-top: 220px; z-index:1;">
    <div style="border: none; height:100vh;" class="card p-4 text-center mt-3">
        <h3 class="mb-3 fw-bold">New PIN</h3>
        <input style="background-color:inherit !important;" placeholder="" type="password" maxlength="4" id="pin" class="pin-input form-control-plaintext mb-4" readonly>

        <div class="keypad d-grid gap-2">
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
            window.location.href = "dashboard.php"; // Change destination after creating PIN
        } else {
            alert("❌ Incorrect PIN");
            clearPin();
        }
    }
</script>

<?php require_once('footer2.php'); ?>