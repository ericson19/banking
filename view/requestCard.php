<?php
session_start();
include('../database/bankdb.php');
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $name = $_POST['fullName'];
    $email = $_POST['email'];
    $purpose = $_POST['purpose'];
    $card_type = $_POST['card_type'];
    $status = "activated";
    $userId = $_SESSION['User_id'];
    $currency = $_SESSION['currency'];
    $hasError = false;

    function validation($data)
    {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $name = validation($name);
    $email = validation($email);
    $purpose = validation($purpose);
    $card_type = validation($card_type);



    if (empty($name)) {
        $nameError = "Kind Enter Your Full Name";
        $hasError = true;
    }
    if (empty($email)) {
        $emailError = "Kind Enter Your Email Address";
        $hasError = true;
    } elseif ($email != $_SESSION['email']) {
        $emailError2 = "Your Email Address is wrong";
        $hasError = true;
    }
    if (empty($purpose)) {
        $purposeError = "choose a purpose for your card purchase";
        $hasError = true;
    }
    if (empty($card_type)) {
        $typeError = "select a card type";
        $hasError = true;
    }
    if (!$hasError) {
        if ($card_type == "mastercard") {
            $card_number = 5104 . mt_rand(100000000000, 999999999999);
            $fullyear = date("Y");
            $year = mt_rand($fullyear + 3, $fullyear + 5);
            $year = substr($year, 2);
            $month = mt_rand(1, 12);
            if (strlen($month) < 2) {
                $month = 0 . $month;
            }
            $expiry = $month . "/" . $year;
            // $month = strlen($month) < 2 ? "0" . $month : $month;
            $cvv = mt_rand(100, 999);
        } elseif ($card_type == "visa") {
            $card_number = 2105 . mt_rand(100000000000, 999999999999);
            $fullyear = date("Y");
            $year = mt_rand($fullyear + 3, $fullyear + 5);
            $year = substr($year, 2);
            $month = mt_rand(1, 12);
            if (strlen($month) < 2) {
                $month = 0 . $month;
            }
            $expiry = $month . "/" . $year;
            // $month = strlen($month) < 2 ? "0" . $month : $month;
            $cvv = mt_rand(100, 999);
        }
        $query = "INSERT INTO card (user_id, name, email, status, card_number, cvv, expiry, card_type, currency) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issssssss", $userId, $name, $email, $status, $card_number, $cvv, $expiry, $card_type, $currency);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "card purchased successfully";

            $query = "SELECT * FROM card_set";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $ngn_price = $row['ngn_price'];
                $usd_price = $row['usd_price'];
                $gbp_price = $row['gbp_price'];
                $eur_price = $row['eur_price'];
                $cny_price = $row['cny_price'];
            }
            if ($_SESSION['currency'] === "NGN") {
                $balance = $_SESSION['balance'] - $ngn_price;
            }
            if ($_SESSION['currency'] === "USD") {
                $balance = $_SESSION['balance'] - $usd_price;
            }
            if ($_SESSION['currency'] === "GBP") {
                $balance = $_SESSION['balance'] - $gbp_price;
            }
            if ($_SESSION['currency'] === "EUR") {
                $balance = $_SESSION['balance'] - $eur_price;
            }
            if ($_SESSION['currency'] === "CNY") {
                $balance = $_SESSION['balance'] - $cny_price;
            }

            $sql = "UPDATE user SET balance = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $balance, $userId);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $_SESSION['balance'] = $balance;
                // $_SESSION['card_created'] = true;
                $card_created = true;
                // header("location: card.php");

            }
            echo $name;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Request Virtual Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://kit.fontawesome.com/a2e4e6fd4b.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .card-options i {
            font-size: 1.5rem;
            margin-right: 8px;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow rounded-4">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4 text-center">Request a Virtual Card</h3>

                        <form method="post">
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter your full name" />
                                <?php
                                if (!empty($nameError)) {
                                    echo "<div style= 'color:red'>" . $nameError . "</div>";
                                }
                                ?>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="example@email.com" />
                                <?php
                                if (!empty($emailError)) {
                                    echo "<div style= 'color:red'>" . $emailError . "</div>";
                                } elseif (!empty($emailError2)) {

                                    echo "<div style= 'color:red'>" . $emailError2 . "</div>";
                                }
                                ?>
                            </div>

                            <div class="mb-3">
                                <label for="purpose" class="form-label">Card Purpose</label>
                                <select class="form-select" name="purpose" id="purpose">
                                    <option value="">Select Purpose</option>
                                    <option value="online">Online Payments</option>
                                    <option value="subscription">Subscription Services</option>
                                    <option value="international">International Shopping</option>
                                </select>
                                <?php
                                if (!empty($purposeError)) {

                                    echo "<div style= 'color:red'>" . $purposeError . "</div>";
                                }
                                ?>
                            </div>

                            <div class="mb-4">
                                <label class="form-label d-block">Select Card Type</label>
                                <div class="form-check form-check-inline card-options">
                                    <input class="form-check-input" type="radio" id="mastercard" name="card_type" value="mastercard" />
                                    <label class="form-check-label" for="mastercard">
                                        <i class="fab fa-cc-mastercard text-danger"></i> MasterCard
                                    </label>
                                </div>

                                <div class="form-check form-check-inline card-options">
                                    <input class="form-check-input" type="radio" id="visa" name="card_type" value="visa" />
                                    <label class="form-check-label" for="visa">
                                        <i class="fab fa-cc-visa text-primary"></i> Visa
                                    </label>
                                </div>
                                <?php
                                if (!empty($typeError)) {
                                    echo "<div style= 'color:red'>" . $typeError . "</div>";
                                }
                                ?>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Submit Request</button>
                                <a id="hr" href="card.php"></a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($card_created)): ?>
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'Your virtual card has been created!',
                icon: 'success',
                confirmButtonColor: '#7bed9f',
                confirmButtonText: 'Continue'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'card.php';
                }
            });
        </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>