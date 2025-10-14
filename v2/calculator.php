<?php include_once 'header.php'; ?>

<div class="main-wrapper container mt-3">

    <!-- Advanced Pay Rate Calculator -->
    <div class="card p-3 timesheet-card">
        <div class="timesheet-header mb-3">
            <h5>Advanced Pay Calculator</h5>
            <hr>
        </div>
        <div class="table-responsive">
            <div class="calculator">
                <h4>Calculate staff pay</h4>
                <p>Use this calculator to estimate pay per shift, weekly or monthly. You can also include overtime, tax deductions, and multiple staff members.</p>

                <!-- Hourly rate and minutes -->
                <input class="form-control mb-3 mt-2" type="number" id="rate" placeholder="Enter hourly pay (£)" value="20">
                <input class="form-control mb-3" type="number" id="minutes" placeholder="Enter minutes worked">

                <!-- Quick buttons -->
                <div class="quick-buttons mt-3 mb-3">
                    <button class="btn btn-info" onclick="setMinutes(15)">15 min</button>
                    <button class="btn btn-primary" onclick="setMinutes(30)">30 min</button>
                    <button class="btn btn-success" onclick="setMinutes(45)">45 min</button>
                    <button class="btn btn-warning" onclick="setMinutes(60)">1 hour</button>
                </div>

                <!-- Overtime and shifts -->
                <div class="form-group mb-3">
                    <label for="overtime">Overtime minutes (optional)</label>
                    <input class="form-control" type="number" id="overtime" placeholder="Enter overtime minutes">
                </div>

                <div class="form-group mb-3">
                    <label for="days">Number of shifts</label>
                    <input class="form-control" type="number" id="days" placeholder="Enter number of shifts" value="1">
                </div>

                <!-- Tax deduction -->
                <div class="form-group mb-3">
                    <label for="tax">Tax/Deductions (%) (optional)</label>
                    <input class="form-control" type="number" id="tax" placeholder="Enter tax percentage" value="0">
                </div>

                <!-- Multiple staff -->
                <div class="form-group mb-3">
                    <label for="staff">Number of staff members</label>
                    <input class="form-control" type="number" id="staff" placeholder="Enter number of staff" value="1">
                </div>

                <div class="result" id="result">Total Pay is £0.00</div>
            </div>
        </div>
    </div>

</div>

<script>
    const rateInput = document.getElementById('rate');
    const minutesInput = document.getElementById('minutes');
    const overtimeInput = document.getElementById('overtime');
    const daysInput = document.getElementById('days');
    const taxInput = document.getElementById('tax');
    const staffInput = document.getElementById('staff');
    const resultDiv = document.getElementById('result');

    let currentPay = 0;

    function animatePay(targetPay) {
        const step = (targetPay - currentPay) / 20;
        let count = 0;
        const interval = setInterval(() => {
            currentPay += step;
            count++;
            resultDiv.innerHTML = formatResult(currentPay);
            setColor(currentPay);
            if (count >= 20) {
                currentPay = targetPay;
                resultDiv.innerHTML = formatResult(currentPay);
                setColor(currentPay);
                clearInterval(interval);
            }
        }, 20);
    }

    function setColor(pay) {
        if (pay >= 100) {
            resultDiv.style.color = 'green';
        } else if (pay >= 50) {
            resultDiv.style.color = 'orange';
        } else {
            resultDiv.style.color = 'red';
        }
    }

    function formatResult(pay) {
        const weekly = pay * 5; // 5 shifts/week
        const monthly = weekly * 4; // approx 4 weeks/month
        return `
            <strong>Total Pay:</strong> £${pay.toFixed(2)}<br>
            <strong>Weekly Pay:</strong> £${weekly.toFixed(2)}<br>
            <strong>Monthly Pay:</strong> £${monthly.toFixed(2)}
        `;
    }

    function calculatePay() {
        const rate = parseFloat(rateInput.value);
        const minutes = parseFloat(minutesInput.value);
        const overtime = parseFloat(overtimeInput.value) || 0;
        const shifts = parseInt(daysInput.value) || 1;
        const taxPercent = parseFloat(taxInput.value) || 0;
        const staff = parseInt(staffInput.value) || 1;

        if (isNaN(rate) || rate <= 0) {
            resultDiv.textContent = 'Please enter a valid hourly rate.';
            resultDiv.style.color = 'red';
            return;
        }

        if (isNaN(minutes) || minutes < 0) {
            resultDiv.textContent = 'Please enter valid minutes worked.';
            resultDiv.style.color = 'red';
            return;
        }

        const hoursWorked = (minutes + overtime) / 60;
        let pay = hoursWorked * rate * shifts * staff;

        if (taxPercent > 0) {
            pay -= (pay * taxPercent / 100);
        }

        animatePay(pay);
    }

    function setMinutes(mins) {
        minutesInput.value = mins;
        calculatePay();
    }

    [rateInput, minutesInput, overtimeInput, daysInput, taxInput, staffInput].forEach(el => {
        el.addEventListener('input', calculatePay);
    });

    // Initial calculation
    calculatePay();
</script>

<?php include_once 'footer.php'; ?>