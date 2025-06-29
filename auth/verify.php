<?php

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = $_POST['otp'];
    if (!isset($_SESSION['otp']) || $otp != $_SESSION['otp']) {
        echo 'OTP not found';
        exit();
    } else if (time() > $_SESSION['otp_expiry']) {
        echo 'OTP expired';
        unset($_SESSION['otp'], $_SESSION['otp_expiry']);
        exit();
    }
    $_SESSION['verified'] = true;
    header('Location: process.php');
    exit();
}
// Get remaining time
$remainingTime = $_SESSION['otp_expiry'] - time();



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let countdownElement = document.getElementById("countdown");
            let resendButton = document.getElementById("resendBtn");
            let remainingTime = <?php echo $remainingTime; ?>; // PHP value to JS

            function updateTimer() {
                if (remainingTime > 0) {
                    let minutes = Math.floor(remainingTime / 60);
                    let seconds = remainingTime % 60;
                    countdownElement.innerHTML = `OTP expires in: <b>${minutes}:${seconds < 10 ? '0' : ''}${seconds}</b>`;
                    remainingTime--;
                } else {
                    countdownElement.innerHTML = `<span class="text-danger">OTP Expired! Please resend.</span>`;
                    resendButton.disabled = false; // Enable Resend Button
                    clearInterval(timer);
                }

            }

            // Run every second
            let timer = setInterval(updateTimer, 1000);
            updateTimer(); // Call once immediately
        });
    </script>
    <title>Verify OTP</title>
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-light">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <p id="countdown" class="text-center text-warning"></p>
                <div class="card shadow-lg p-4">
                    <h3 class="text-center mb-3">Enter OTP</h3>
                    <form method="post">
                        <div class="mb-3">
                            <label for="otp" class="form-label">OTP Code:</label>
                            <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
                        </div>
                        <button class="btn btn-success w-100" type="submit">Verify</button>
                    </form>
                    <form action="../mail/resendotpmail.php" method="post">
                        <input name="resend" type="hidden" value="resend">
                        <button id="resendBtn" class="btn btn-danger w-100 mt-2" type="submit" disabled>Resend OTP</button>
                    </form>
                </div>
                <p class="mt-3"><strong>This Code exires in the next 5 minutes, click on resend if expired</strong></p>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>