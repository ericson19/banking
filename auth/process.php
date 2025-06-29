<?php
session_start();

include('../database/bankdb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first-name'];
    $midName = $_POST['mid-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $occupation = $_POST['occupation'];
    $salary = $_POST['salary'];
    $accType = $_POST['type'];
    $currency = $_POST['currency'];
    $twofa = $_POST['2fa'];
    $password = $_POST['Password'];
    $conPassword = $_POST['conPassword'];
    $passport = $_FILES['passport'];

    $hasError = false;

    function validate($data)
    {
        $data = trim($data); // Remove unnecessary spaces
        $data = stripslashes($data); // Remove backslashes
        $data = htmlspecialchars($data); // Convert special characters to HTML entities
        return $data;
    };
    $firstName = validate($firstName);
    $midName = validate($midName);
    $lastName = validate($lastName);
    $email = validate($email);
    $country = validate($country);
    $state = validate($state);
    $city = validate($city);
    $occupation = validate($occupation);
    $salary = validate($salary);
    $accType = validate($accType);
    $currency = validate($currency);
    $twofa = validate($twofa);
    $password = validate($password);
    $conPassword = validate($conPassword);

    if (empty($firstName)) {
        $_SESSION['firstName_error'] = 'First Name is requird';
        $hasError = true;
    }
    if (empty($lastName)) {
        $_SESSION['lastName_error'] = 'last Name is requird';
        $hasError = true;
    }
    if (empty($midName)) {
        $_SESSION['midName_error'] = 'middle Name is requird';
        $hasError = true;
    }
    if (empty($email)) {
        $_SESSION['email_error'] = 'email is requird';
        $hasError = true;
    }
    if (empty($country)) {
        $_SESSION['country_error'] = 'kindly select a country';
        $hasError = true;
    }
    if (empty($state)) {
        $_SESSION['state_error'] = 'Your State is requird';
        $hasError = true;
    }
    if (empty($city)) {
        $_SESSION['city_error'] = 'Your City is requird';
        $hasError = true;
    }
    if (empty($occupation)) {
        $_SESSION['occupation_error'] = 'Please select and occupation';
        $hasError = true;
    }
    if (empty($salary)) {
        $_SESSION['salary_error'] = 'Kindly Choose a salary range';
        $hasError = true;
    }
    if (empty($accType)) {
        $_SESSION['accType_error'] = 'Kindly Select account type';
        $hasError = true;
    }
    if (empty($currency)) {
        $_SESSION['currency_error'] = 'currency is requird';
        $hasError = true;
    }
    if (empty($twofa)) {
        $_SESSION['twofa_error'] = 'Kindly choose a secured pin';
        $hasError = true;
    }
    if (empty($password)) {
        $_SESSION['password_error'] = 'password is requird';
        $hasError = true;
    } elseif (!preg_match("/^(?=.*[a-zA-Z])(?=.*\d).{6,}$/", $password)) {
        $_SESSION['password_error2'] = "password must be at least 6 characters long, at least 1 number and one letter";
        $hasError = true;
    }
    if (empty($conPassword)) {
        $_SESSION['conPassword_error'] = 'Kindly enter your password again';
        $hasError = true;
    }
    if ($password !== $conPassword) {
        $_SESSION['conPassword_error2'] = 'Passwords does not match';
        $hasError = true;
    }
    if ($hasError) {
        header('location: register.php');
        exit;
    }

    $qquery = "SELECT id FROM user WHERE email =  ?";
    $sstmt = $conn->prepare($qquery);
    $sstmt->bind_param("s", $email);
    $sstmt->execute();
    $sstmt->store_result();
    if ($sstmt->num_rows > 0) {
        $_SESSION['email_error2'] = 'This email has already been used';
        $sstmt->close();
        header('Location: register.php');
        exit();
    }
    $sstmt->close();


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $hashedtwofa = password_hash($twofa, PASSWORD_DEFAULT);
    // Generate a 10-digit account number
    $account_number = "201" . mt_rand(1000000, 9999999);



    //passport upload
    $uploadDir = "../upload/";
    $fileName = basename($passport["name"]);
    $targetFile = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));


    if ($passport['error'] === UPLOAD_ERR_OK) {
        $allowedMimeTypes = array('jpg', 'png', 'jpeg');
        if (!in_array($fileType, $allowedMimeTypes)) {
            echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
            exit;
        }
        //if directory does not exit, create new directory
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        if (move_uploaded_file($passport['tmp_name'], $targetFile)) {
            echo "The file " . basename($passport['name']) . " has been uploaded.";
            $otp = rand(100000, 999999);
            $otp_expiry = time() + (5 * 60);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = $otp_expiry;
            // Store user registration details in session before OTP verification
            $_SESSION['firstName'] = $firstName;
            $_SESSION['lastName'] = $lastName;
            $_SESSION['midName'] = $midName;
            $_SESSION['email'] = $email;
            $_SESSION['country'] = $country;
            $_SESSION['state'] = $state;
            $_SESSION['city'] = $city;
            $_SESSION['occupation'] = $occupation;
            $_SESSION['salary'] = $salary;
            $_SESSION['accType'] = $accType;
            $_SESSION['currency'] = $currency;
            $_SESSION['hashedtwofa'] = $hashedtwofa;
            $_SESSION['targetFile'] = $targetFile;
            $_SESSION['hashedPassword'] = $hashedPassword;
            $_SESSION['account_number'] = $account_number;


            include('../mail/message.php');
            header('location: verify.php');
            exit();
        }
    }
}
if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
    echo 'access denied otp not available';
    exit();
}
//retrieve the current session
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$midName = $_SESSION['midName'];
$email = $_SESSION['email'];
$country = $_SESSION['country'];
$state = $_SESSION['state'];
$city = $_SESSION['city'];
$occupation = $_SESSION['occupation'];
$salary = $_SESSION['salary'];
$accType = $_SESSION['accType'];
$currency = $_SESSION['currency'];
$hashedtwofa = $_SESSION['hashedtwofa'];
$targetFile = $_SESSION['targetFile'];
$hashedPassword = $_SESSION['hashedPassword'];
$account_number = $_SESSION['account_number'];

$query = "INSERT INTO user (FirstName, LastName, MidName, Email, Country, State, City, 
 Occupation, Salary, AccountType, currency, 2fa, img, password, account_number) VALUES 
 (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?) ON DUPLICATE KEY UPDATE account_number = VALUES(account_number)";
$stmt = $conn->prepare($query);
$stmt->bind_param(
    "sssssssssssssss",
    $firstName,
    $lastName,
    $midName,
    $email,
    $country,
    $state,
    $city,
    $occupation,
    $salary,
    $accType,
    $currency,
    $hashedtwofa,
    $targetFile,
    $hashedPassword,
    $account_number
);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    echo "registration successful";
    $_SESSION['firstName'] = $firstName;
    $_SESSION['lastName'] = $lastName;
    $_SESSION['midName'] = $midName;
    $_SESSION['email'] = $email;
    $_SESSION['state'] = $state;
    $_SESSION['city'] = $city;
    $_SESSION['occupation'] = $occupation;
    $_SESSION['salary'] = $salary;
    $_SESSION['accType'] = $accType;
    $_SESSION['currency'] = $currency;
    $_SESSION['targetFile'] = $targetFile;

    // include('../mail/message.php');

}

$stmt->close();

$query = "SELECT * FROM user WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['User_id'] = $row['id'];
    $_SESSION['firstName'] = $row['FirstName'];
    $_SESSION['lastName'] = $row['LastName'];
    $_SESSION['midName'] = $row['MidName'];
    $_SESSION['email'] = $row['Email'];
    $_SESSION['country'] = $row['Country'];
    $_SESSION['currency'] = $row['currency'];
    $_SESSION['balance'] = $row['balance'];
    $_SESSION['account_number'] = $row['account_number'];
    $_SESSION['accType'] = $row['AccountType'];
    $_SESSION['occupation'] = $row['Occupation'];
    $_SESSION['salary'] = $row['Salary'];
    $_SESSION['state'] = $row['State'];
    $_SESSION['city'] = $row['City'];
    $_SESSION['img'] = $row['img'];
    //IP Address, Country and city
    $ip = "8.8.8.8";
    $location = file_get_contents("http://ip-api.com/json/{$ip}");
    $data = json_decode($location, true);
    $state = $data['[regionName]'];
    $country = $data['country'];
    $city = $data['city'];
    //device and OS
    $device = $_SERVER['HTTP_USER_AGENT'];
    $sql = "INSERT INTO security (ip_address, ip_country, ip_city, os, user_id) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $ip, $country, $city, $device, $row['id']);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $_SESSION['ip'] = $ip;
        $_SESSION['country'] = $country;
        $_SESSION['city'] = $city;
    }
    header('Location: ../view/dashboard.php');
}
