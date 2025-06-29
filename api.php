<?php
   $url = "https://v6.exchangerate-api.com/v6/fc7ee5f5dc0eba691823676e/latest/USD "; 

   $response = file_get_contents($url);
   $data = json_decode($response, true);


?>
<?php
// require 'vendor/autoload.php';

// use PHPMailer\PHPMailer\PHPMailer;

// $mail = new PHPMailer();

// $mail->isSTMP();
// $mail->Host = 'host.gmail.com';
// $mail->STMPAuth = true;
// $mail->username = ' sender mail email';
// $mail->password = '';
// $mail-> SMTPSecure = 'ssl';
// $mail->port = 465;

// $mail->setFrom('yourmail', 'your name');
// $mail->Address('recieversmail', 'recievers name');

// $mail->Subject = 'notification alart';
// $mail->body = 'this is a notification from us';

// $mail->send();