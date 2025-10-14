<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Staff Pay Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .calculator {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 320px;
        }

        input,
        button {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .quick-buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 10px 0;
        }

        .quick-buttons button {
            flex: 1;
            margin: 0;
        }

        .result {
            margin-top: 20px;
            font-size: 22px;
            font-weight: bold;
            transition: color 0.5s;
        }
    </style>
</head>

<body>

    <div class="calculator">
        <h2>Staff Pay Calculator</h2>
        <input type="number" id="rate" placeholder="Enter hourly pay (£)" value="20">
        <input type="number" id="minutes" placeholder="Enter minutes worked">
        <div class="quick-buttons">
            <button onclick="setMinutes(15)">15 min</button>
            <button onclick="setMinutes(30)">30 min</button>
            <button onclick="setMinutes(45)">45 min</button>
        </div>
        <div class="result" id="result">Staff will be paid £0.00</div>
    </div>

    <script>
        const rateInput = document.getElementById('rate');
        const minutesInput = document.getElementById('minutes');
        const resultDiv = document.getElementById('result');

        let currentPay = 0;

        function animatePay(targetPay) {
            const step = (targetPay - currentPay) / 20; // smooth animation
            let count = 0;
            const interval = setInterval(() => {
                currentPay += step;
                count++;
                resultDiv.textContent = `Staff will be paid £${currentPay.toFixed(2)}`;
                setColor(currentPay);
                if (count >= 20) {
                    currentPay = targetPay;
                    resultDiv.textContent = `Staff will be paid £${currentPay.toFixed(2)}`;
                    setColor(currentPay);
                    clearInterval(interval);
                }
            }, 20);
        }

        function setColor(pay) {
            if (pay >= 20) {
                resultDiv.style.color = 'green';
            } else if (pay >= 10) {
                resultDiv.style.color = 'orange';
            } else {
                resultDiv.style.color = 'red';
            }
        }

        function calculatePay() {
            const ratePerHour = parseFloat(rateInput.value);
            const minutesWorked = parseFloat(minutesInput.value);

            if (isNaN(ratePerHour) || ratePerHour <= 0) {
                resultDiv.textContent = 'Please enter a valid hourly rate.';
                resultDiv.style.color = 'red';
                return;
            }

            if (isNaN(minutesWorked) || minutesWorked < 0) {
                resultDiv.textContent = 'Please enter a valid number of minutes.';
                resultDiv.style.color = 'red';
                return;
            }

            const hoursWorked = minutesWorked / 60;
            const pay = hoursWorked * ratePerHour;

            animatePay(pay);
        }

        function setMinutes(mins) {
            minutesInput.value = mins;
            calculatePay();
        }

        // Update result instantly as user types
        rateInput.addEventListener('input', calculatePay);
        minutesInput.addEventListener('input', calculatePay);

        // Initial calculation
        calculatePay();
    </script>

</body>

</html>