<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Pending</title>
<style>
    body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #ece9e6, #ffffff);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.container {
    text-align: center;
    padding: 40px;
    background-color: #fff;
    border-radius: 16px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    max-width: 500px;
    width: 100%;
}

.message-box h2 {
    color: #f39c12;
    margin-bottom: 10px;
}

.message-box p {
    font-size: 16px;
    color: #333;
    margin-bottom: 20px;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    background-color: #3498db;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #2980b9;
}

</style>
</head>
<body>
    <div class="container">
        <div class="message-box">
            <h2>⏳ Payment is Pending</h2>
            <p>Thank you for your payment. We are currently verifying your transaction.</p>
            <p>You will gain access to the course once your payment is confirmed.</p>
            <a href="courses.php" class="btn">Return to Homepage</a>
        </div>
    </div>
</body>
</html>
