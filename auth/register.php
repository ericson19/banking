<?php
session_start();
include('../countries.php');
// $url = "https://restcountries.com/v3.1/all";
// $response = file_get_contents($url);
// $countries = json_decode($response, true);

// //sort countries alphabetically
// usort($countries, function ($a, $b) {
//     return strcmp($a['name']['common'], $b['name']['common']);
// });
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            height: auto;
            background: linear-gradient(rgba(255, 255, 255, 0.62),
                    rgba(255, 255, 255, 0.56)),
                url("../images/6379114.jpg");
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body>
    <?php include('../view/header.php') ?>
    <div class="maindiv">
        <div class="header">
            👤 Kindly provide the information requested below to enable us create an account for you.
        </div>
        <div class="container mt-4">
            <form method="POST" action="process.php" enctype="multipart/form-data">
                <h4>Personal Details</h4>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first-name" class="form-control" placeholder="First Name">
                        <?php
                        if (isset($_SESSION['firstName_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['firstName_error'] . "</div>";
                            unset($_SESSION['firstName_error']);
                        }
                        ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="mid-name" class="form-control" placeholder="Middle Name">
                        <?php
                        if (isset($_SESSION['midName_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['midName_error'] . "</div>";
                            unset($_SESSION['midName_error']);
                        }
                        ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last-name" class="form-control" placeholder="Last Name">
                        <?php
                        if (isset($_SESSION['lastName_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['lastName_error'] . "</div>";
                            unset($_SESSION['lastName_error']);
                        }
                        ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <?php
                        if (isset($_SESSION['email_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['email_error'] . "</div>";
                            unset($_SESSION['email_error']);
                        } elseif (isset($_SESSION['email_error2'])) {
                            echo "<div style='color:red'>" . $_SESSION['email_error2'] . "</div>";
                            unset($_SESSION['email_error2']);
                        }

                        ?>
                    </div>
                </div>

                <h4>Address</h4>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Country</label>
                        <?php
                        echo "<select name='country' class='form-select'>";
                        echo "<option value=''>Select countries</option>";
                        foreach ($countries as $country => $value) {

                            echo "<option value='$value'>$value</option>";
                        };
                        echo "</select>";
                        //   echo "<input type='hidden' name='currencyCode' id='currencyCode'>";
                        //  echo "<input type='hidden' name='currencySymbol' id='currencySymbol'>";

                        ?>
                        <?php
                        if (isset($_SESSION['country_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['country_error'] . "</div>";
                            unset($_SESSION['country_error']);
                        }
                        ?>


                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State</label>
                        <input type="text" name="state" id="" class="form-control">
                        <?php
                        if (isset($_SESSION['state_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['state_error'] . "</div>";
                            unset($_SESSION['state_error']);
                        }
                        ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" placeholder="City">
                        <?php
                        if (isset($_SESSION['city_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['city_error'] . "</div>";
                            unset($_SESSION['city_error']);
                        }
                        ?>
                    </div>
                </div>

                <h4>Employment Information</h4>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Occupation</label>
                        <select name="occupation" class="form-select">
                            <option value="employer">Employer </option>
                            <option value="self-employed">Self Employed</option>
                            <option value="not-employed">Not Employed</option>
                            <option value="graduate">Graduate</option>
                            <option value="under-graduate">Under graduate</option>
                        </select>
                        <?php
                        if (isset($_SESSION['occupation_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['occupation_error'] . "</div>";
                            unset($_SESSION['occupation_error']);
                        }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Annual Income Range</label>
                        <select name="salary" class="form-select">
                            <option value="">Select Salary Range</option>
                            <option value="0-1000">0 - 1000</option>
                            <option value="1001-9999">1001 - 9999</option>
                            <option value="1001-99999">10000 - 99999</option>

                        </select>
                        <?php
                        if (isset($_SESSION['salary_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['salary_error'] . "</div>";
                            unset($_SESSION['salary_error']);
                        }
                        ?>
                    </div>
                </div>

                <h4>Banking Details</h4>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Account Type</label>
                        <select name="type" class="form-select">
                            <option value="">Please select Account Type</option>
                            <option value="Savings">Savings Acount</option>
                            <option value="current">Current Account</option>
                            <option value="fixed">Fixed deposit</option>
                        </select>
                        <?php
                        if (isset($_SESSION['accType_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['accType_error'] . "</div>";
                            unset($_SESSION['accType_error']);
                        }
                        ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Account Currency</label>
                        <select name="currency" class="form-select">
                            <option value="">Select currency</option>
                            <option value="EUR">Euro (€)</option>
                            <option value="USD">US Dollar ($)</option>
                            <option value="GBP">British Pound (£)</option>
                            <option value="NGN">Nigerian Naira (₦)</option>
                            <option value="CNY">Chinese Yuan (¥)</option>
                        </select>
                        <?php
                        if (isset($_SESSION['currency_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['currency_error'] . "</div>";
                            unset($_SESSION['currency_error']);
                        }
                        ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">2FA PIN</label>
                        <input type="password" name="2fa" class="form-control">
                        <?php
                        if (isset($_SESSION['twofa_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['twofa_error'] . "</div>";
                            unset($_SESSION['twofa_error']);
                        }
                        ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" id="password" name="Password" class="form-control"><i id="show" class="fa-solid fa-eye-slash"></i>
                        <?php
                        if (isset($_SESSION['password_error'])) {
                            echo "<div style='color:red'>" . $_SESSION['password_error'] . "</div>";
                            unset($_SESSION['password_error']);
                        }
                        if (isset($_SESSION['password_error2'])) {
                            echo "<div style='color:red'>" . $_SESSION['password_error2'] . "</div>";
                            unset($_SESSION['password_error2']);
                        }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="conPassword" class="form-control">
                        <?php
                        if (isset($_SESSION['conPassword_error2'])) {
                            echo "<div style='color:red'>" . $_SESSION['conPassword_error2'] . "</div>";
                            unset($_SESSION['conPassword_error2']);
                        }
                        ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Passport Photograph</label>
                    <input type="file" name="passport" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="container d-flex justify-content-center  bg-primary mt-5">
            <p>Do you Want To Create A new Account ? </p>
            <a class="nav-link text-danger " href="./register.php">Create Account Her</a>
        </div>
    </div>
    <script>
        //SHOW PASSWORD TOGGLE
        let password = document.querySelector("#password");
        let show = document.querySelector("#show");

        show.addEventListener("click", function() {
            if (password.type === "password") {
                password.type = "text";
                show.classList.replace("fa-eye-slash", "fa-eye");
            } else {
                password.type = "password";
                show.classList.replace("fa-eye", "fa-eye-slash");
            }
        });
    </script>
</body>

</html>