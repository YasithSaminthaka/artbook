<?php require 'URI.php';?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Choose Payment Method</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
            color: #333;
        }

        h2 {
            color: #2c3e50;
        }

        select {
            padding: 10px;
            font-size: 16px;
            width: 100%;
            max-width: 400px;
            margin-top: 10px;
        }

        .section {
            display: none;
            margin-top: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        .visible {
            display: block;
        }

        .button {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .field {
            margin-top: 10px;
        }

        input[type="text"] {
            padding: 10px;
            width: 100%;
            max-width: 300px;
            font-size: 16px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        p {
            margin: 5px 0;
        }
    </style>
</head>

<body>

    <h2>Select Payment Method</h2>
    <select id="paymentMethod">
        <option value="">-- Choose Payment Method --</option>
        <option value="card">Credit / Debit Card</option>
        <option value="bank">Online Bank Transfer</option>
        <option value="redeem">Redeem Code</option>
    </select>

    <!-- Card Payment Section -->
    <div id="cardSection" class="section">
        <p>You will be redirected to PayHere to complete your payment.</p>
        <button class="button" onclick="goToPayHere()">Pay with PayHere</button>
    </div>

    <!-- Bank Transfer Section -->
    <div id="bankSection" class="section">
        <h4>Bank Transfer Details</h4>
        <p><strong>Bank:</strong> Sample Bank</p>
        <p><strong>Account Name:</strong> ABC Courses</p>
        <p><strong>Account Number:</strong> 123456789</p>
        <p><strong>Branch:</strong> Colombo Main</p>
        <form id="slipForm" enctype="multipart/form-data">
            <label for="slip">Upload Payment Slip (PDF, JPG, PNG, max 1MB):</label><br>
            <input type="file" name="slip" accept=".pdf,.jpg,.jpeg,.png" required>
            <br><br>
            <input type="submit" value="Upload Slip">
        </form>

        <!-- Result Section -->
        <div id="uploadResult" style="margin-top: 15px; color: #007b5e; font-weight: bold;"></div>
    </div>

    <!-- Redeem Code Section -->
    <div id="redeemSection" class="section">
        <div class="field">
            <label for="redeemCode">Enter Redeem Code:</label><br>
            <input type="text" id="redeemCode" placeholder="XXXX-XXXX">
        </div>
        <button class="button" onclick="redeemCode()">Apply Code</button>
        <div id="result"></div>

    </div>

    <script>
        const methodSelect = document.getElementById('paymentMethod');
        const card = document.getElementById('cardSection');
        const bank = document.getElementById('bankSection');
        const redeem = document.getElementById('redeemSection');

        methodSelect.addEventListener('change', function () {
            card.classList.remove('visible');
            bank.classList.remove('visible');
            redeem.classList.remove('visible');

            if (this.value === 'card') card.classList.add('visible');
            else if (this.value === 'bank') bank.classList.add('visible');
            else if (this.value === 'redeem') redeem.classList.add('visible');
        });

        function goToPayHere() {
            window.location.href = 'https://payhere.lk/payment'; // Replace with real PayHere link
        }

        async function redeemCode() {
            const code = document.getElementById('redeemCode').value;
            const courseId = new URLSearchParams(window.location.search).get('id');


            try {
                const response = await fetch(`${SITE_URL}/api/check_redeem.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        code: code,
                        course_id: courseId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // alert(data.redeem_id);
                    AddPayment(data.redeem_id);

                    // Redirect or update UI
                } else {
                    //alert(`Error: ${data.message}`);
                    showResult(data.message, true);
                }
            } catch (error) {
                alert('Payment processing failed');
            }
        }
        async function AddPayment(value) {

            const courseId = new URLSearchParams(window.location.search).get('id');
            const code = document.getElementById('redeemCode').value;
            console.log(value);
            try {
                const response = await fetch(`${SITE_URL}/api/add_payment_redeem.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        "course_id": courseId,
                        "redeem_id": value,
                        "amount": 100,
                        "redeem_code": code
                    })
                });

                const data = await response.json();

                if (data.success) {
                    //     alert(`Payment successful! 
                    //   Price: $${data.final_price} 
                    //   ${data.used_redeem ? '(with redeem)' : ''}`);
                    // Redirect or update UI
                    //header("location: drive_access.php");
                    showResult("Redeem Code has been Successed. ", true);
                    ReloadHere();
                } else {
                    //alert(`Error: ${data.message}`);
                    showResult("Redeem code has been used ", true);
                }
            } catch (error) {
                //alert('Payment processing failed');
                showResult("Payment processing failed ", true);
            }
        }

        function showResult(message, isSuccess) {
            const resultDiv = document.getElementById('result');
            resultDiv.textContent = message;
            resultDiv.className = isSuccess ? 'success' : 'error';
            resultDiv.style.display = 'block';

            // Auto-hide after 5 seconds
            setTimeout(() => {
                resultDiv.style.display = 'none';
            }, 5000);
        }

        function ReloadHere() {
            setTimeout(() => {
                window.location.href = 'drive_access.php';
            }, 3000);
        }
    </script>
    <script>
        document.getElementById('slipForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = document.getElementById('slipForm');
            const formData = new FormData(form);

            fetch(`${SITE_URL}/api/upload_slip.php`, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json()) // Make sure your PHP returns JSON
                .then(data => {
                    if (data.status === "success") {
                        document.getElementById('uploadResult').innerText = "Please  wait...";
                        //console.log("Success");
                        // Now send this URL to AddBankPayment
                        AddBankPayment(data.receipt_url);
                    } else {
                        document.getElementById('uploadResult').innerText = data.message;
                        console.log("Success 2");
                    }
                })
        });
        async function AddBankPayment(value) {

            const courseId = new URLSearchParams(window.location.search).get('id');

            console.log(value);
            try {
                const response = await fetch(`${SITE_URL}/api/add_payment_banktransfer.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        "course_id": courseId,
                        "amount": 100,
                        "receipt_url": value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('uploadResult').innerText = data.message;
                    // Redirect or update UI
                    //header("location: drive_access.php");
                    //showResult("Redeem Code has been Successed. ", true);
                    //ReloadHere();
                } else {
                    alert(`Error: ${data.message}`);
                    document.getElementById('uploadResult').innerText = data.message;
                    //showResult("Redeem code has been used ", true);
                }
            } catch (error) {
                alert('Payment processing failed');
                //showResult("Payment processing failed ", true);
            }
        }
    </script>
</body>

</html>