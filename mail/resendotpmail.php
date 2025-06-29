<?php
session_start();
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['resend'])) {
    $email = $_SESSION['email'];
    $otp = rand( 100000, 999999);
    $otp_expiry = time() + (5 * 60);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = $otp_expiry;
    //set up smtp settitnh
$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'ericanox@gmail.com';
$mail->Password = 'gkoicychtncvaxed'; // Use App Password for Gmail
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

//set up sender
$mail->setFrom('ericanox@gmail.com', 'anox tech');
$mail->addAddress($email);
$mail->Subject = 'Notification Alert';
// Enable HTML format
$mail->isHTML(true);

// HTML Email Body with Styling
$mail->Body = str_replace("{{OTP}}", $otp, file_get_contents("../mail/otpmail.html"));
if (!file_exists("../mail/otpmail.html")) {
    die("Error: otp template file not found!");
}

// send mail
if ($mail->send()) {
   echo   "Email sent successfully.";
   header('location: ../auth/verify.php');
   exit();
} else {
    echo   "Email sending failed: " . $mail->ErrorInfo;
}

}


