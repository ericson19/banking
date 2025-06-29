<?php
session_start();
include('../database/bankdb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hasError = false;


    if (empty($email)) {
        $email_error = 'Kindly Enter your email';
        $hasError = true;
    }
    if (empty($password)) {
        $password_error = 'Kindly Enter your password';
        $hasError = true;
    }
    if (!$hasError) {
        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['Password'])) {

                echo 'successfuly logged in';
                $_SESSION['id'] = $row['id'];
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
                header('Location: ../mail/welcomeMail.php');
            } else {
                $errorEmail = 'your email or password is not correct';
            }
        } else {
            $errorEmail = 'your email or password is not correct';
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>login </title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-size: 0.9rem;

        }

        .form-label {
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            font-size: 0.9rem;
        }

        .header {
            background: #3b49df;
            color: white;
            padding: 1rem;
            text-align: center;
            font-size: 0.8rem;
        }

        .maindiv {
            height: 100vh;
            animation: img 5s infinite;
        }

        /* @keyframes img {

            0% {
                background: linear-gradient(rgba(105, 188, 236, 0.432), rgba(105, 188, 236, 0.432)), url(../images/96a924af-0fcd-4414-a378-55416e7fb2a6.jpg);
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                opacity: 1;

            }

            50% {
                background: linear-gradient(rgba(105, 188, 236, 0.432), rgba(105, 188, 236, 0.432)), url(../images/6379114.jpg);
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                opacity: 0.5;
            }

            75% {
                background: linear-gradient(rgba(105, 188, 236, 0.432), rgba(105, 188, 236, 0.432)), url(../images/022811dd-28ec-433a-a00b-085bd30b4882.jpg);
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                opacity: 0.8;
            }

            100% {
                background: linear-gradient(rgba(105, 188, 236, 0.432), rgba(105, 188, 236, 0.432)), url(../images/hand-pointing-currency-blockchain-technology-background.jpg);
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                opacity: 1;
            }
        } */
    </style>
</head>

<body>
    <div class="maindiv">
        <div class="header">
            👤 Kindly provide the information requested below to enable us create an account for you.
        </div>
        <form action="" method="post">

            <div class="container w-75 mt-5">
                <?php
                if (isset($errorEmail)) {
                    echo "<p style='color:red;'>{$errorEmail}</p>";
                }
                ?>
                <label class="form-label" for="">Enter your Email</label>
                <input class="form-control" name="email" type="email">
                <?php
                if (isset($email_error)) {
                    echo "<p style='color:red;'>{$email_error}</p>";
                }
                ?>
            </div>

            <div class="container w-75">
                <label class="form-label" for="">Enter your password</label>
                <input class="form-control" name="password" type="password">
                <?php
                if (isset($password_error)) {
                    echo "<p style='color:red;'>{$password_error}</p>";
                }
                ?>
            </div>
            <div class="container text-center mt-2">
                <button class="btn btn-primary w-75" type="submit">Submit</button>
            </div>

        </form>
        <div class="container d-flex justify-content-center  bg-primary mt-5">
            <p>Do you Want To Create A new Account ? </p>
            <a class="nav-link text-danger " href="./register.php">Create Account Her</a>
        </div>
    </div>

</body>

</html>