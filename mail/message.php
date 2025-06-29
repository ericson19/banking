<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

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
} else {
    echo   "Email sending failed: " . $mail->ErrorInfo;
}
