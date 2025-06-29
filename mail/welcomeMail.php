<?php
session_start();
include('../database/bankdb.php');
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;


$user_id = $_SESSION['User_id'];
echo '<pre>';
print_r($user_id);
echo '</pre>';
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['FirstName'];
    $email = $row['Email'];
}
$stmt = $conn->prepare("SELECT * FROM security WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $date = $row['created_at'];
    $ipaddress = $row['ip_address'];
    $city = $row['ip_city'];
    $country = $row['ip_country'];
}
$sitename = 'Banking System';
$siteurl = 'http://192.168.94.142:8080/bank/view';
$year = date('Y');
$address = 'no 15 Mazi Street, Makinde Lagos';


$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->Username = 'ericanox@gmail.com';
$mail->Password = 'gkoicychtncvaxed'; // Use App Password for Gmail
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

$mail->setFrom('ericanox@gmail.com', 'Anox Tech');
$mail->addAddress($email);
$mail->Subject = "Welcome back, {$name}";

$mail->isHTML(true);

$placeholder = ['{{ name }}', '{{ date }}', '{{ sitename }}', '{{ ipaddress }}', '{{ city }}', '{{ country }}', '{{ siteurl }}', '{{ year }}', '{{ address }}'];
$replacement = [$name, $date, $sitename, $ipaddress, $city, $country, $siteurl, $year, $address];
$mail->Body = str_replace($placeholder, $replacement, file_get_contents('../mail/login.html'));

if ($mail->send()) {
    header('location: ../view/dashboard.php');
}
