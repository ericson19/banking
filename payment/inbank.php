<?php
session_start();
include "../database/bankdb.php";


$user_id = $_SESSION['User_id'];
$kyc = $_SESSION['kyc_status'];
$verification = $_SESSION['verification'];




if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $acc_num = $_POST['acc_num'];
    $acc_name = $_POST['acc_name'];
    $amount = $_POST['amount'];
    $desc = $_POST['desc'];
    $twofa = $_POST['twofa'];
    $txn_id = "tx" . mt_rand(10000000, 99999999);
    $txn_type = "transfer";
    $currency = $_SESSION['currency'];



    $hasError = false;
    if (empty($amount)) {
        $amountError = "kindly Enter Amount";
        $hasError = true;
    }
    if (empty($acc_num)) {
        $numError = "kindly Enter Account number";
        $hasError = true;
    }
    if (empty($desc)) {
        $descError = "Enter Short Description";
        $hasError = true;
    }
    if (empty($twofa)) {
        $twofaError = "Kindly input pin";
        $hasError = true;
    }
    if ($kyc != 'verified' && $verification == 'on') {
        $verifyError = "Your Account is not verified";
        $hasError = true;
    }

    //retrieve recievers details
    $qquery = "SELECT * FROM user WHERE account_number = ?";
    $stmt = $conn->prepare($qquery);
    $stmt->bind_param("i", $acc_num);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $recieve_id = $row['id'];
        $receiver_balance = $row['balance'];
        $firstName = $row['FirstName'];
        $receiverCurrency = $row['currency'];
        $account = $row['account_number'];
        if ($receiver_balance < $amount) {
            $balanceError = "Insuffient fund";
            $hasError = true;
        }
    }

    //retieve senders details
    $query = "SELECT * FROM user WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $sender_balance = $row['balance'];
        $senderCurrency = $row['currency'];
        $send_name = "{$row['LastName']} {$row['FirstName']}";
        if (!password_verify($twofa, $row['2fa'])) {
            $descError = "incorrect password";
            $hasError = true;
        }
    }
    $stmt = $conn->prepare("SELECT * FROM txn_set");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $usd_min = $row['usd_min'];
        $usd_max = $row['usd_max'];
        $gbp_min = $row['gbp_min'];
        $gbp_max = $row['gbp_max'];
        $eur_min = $row['eur_min'];
        $eur_max = $row['eur_max'];
        $ngn_min = $row['ngn_min'];
        $ngn_max = $row['ngn_max'];
        $cny_min = $row['cny_min'];
        $cny_max = $row['cny_max'];
    }
    if ($currency === "USD") {
        if ($usd_min > $amount) {
            $amountLimt = "limit is low, transfer more than {$usd_min}";
            $hasError = true;
        } elseif ($usd_max < $amount) {
            $amountLimt = "limit is high, transfer more than {$usd_max}";
            $hasError = true;
        }
    }
    if ($currency === "GBP") {
        if ($gbp_min > $amount) {
            $amountLimt = "limit is low, transfer more than {$gbp_min}";
            $hasError = true;
        } elseif ($gbp_max < $amount) {
            $amountLimt = "limit is low, transfer below  {$gbp_max}";
            $hasError = true;
        }
    }
    if ($currency === "EUR") {
        if ($eur_min > $amount) {
            $amountLimt = "limit is low, transfer more than {$eur_min}";
            $hasError = true;
        } elseif ($eur_max < $amount) {
            $amountLimt = "limit is low, transfer below {$eur_max}";
            $hasError = true;
        }
    }
    if ($currency === "NGN") {
        if ($ngn_min > $amount) {
            $amountLimt = "limit is low, transfer more than {$ngn_min}";
            $hasError = true;
        } elseif ($ngn_max < $amount) {
            $amountLimt = "limit is low, transfer below {$ngn_max}";
            $hasError = true;
        }
    }
    if ($currency === "CNY") {
        if ($cny_min > $amount) {
            $amountLimt = "limit is low, transfer more than {$cny_min}";
            $hasError = true;
        } elseif ($cny_max < $amount) {
            $amountLimt = "limit is low, transfer below {$cny_max}";
            $hasError = true;
        }
    }
    if ($receiverCurrency != $senderCurrency) {
        $currError = "The currency type is different, {$firstName} is currently on {$receiverCurrency}";
        $hasError = true;
    }


    //verify pin
    // $stmt = $conn->prepare("SELECT 2fa FROM user WHERE id = ?");
    // $stmt->bind_param("i", $user_id);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // if ($result->num_rows > 0) {
    //     $row = $result->fetch_assoc();
    //     if (!password_verify($twofa, $row['2fa'])) {
    //         $descError = "incorrect password";
    //         $hasError = true;
    //     }
    // }
    if (!$hasError) {
        $sql = "INSERT INTO inbank_transaction (send_user_id, receive_user_id, receive_name, send_name, send_num, amount, txn_id, txn_type, description) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssssss", $user_id, $recieve_id, $acc_name, $send_name, $acc_num, $amount, $txn_id, $txn_type, $desc);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "payment successful";
            $payment_done = true;
            $sender_balance -= $amount;
            $sql = "UPDATE user SET balance = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $sender_balance, $user_id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {

                $_SESSION['balance'] = $sender_balance;
            }
            $receiver_balance += $amount;
            $sql = "UPDATE user SET balance = ? WHERE account_number = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $receiver_balance, $acc_num);
            $stmt->execute();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>inbank transfer</title>
</head>

<body>
    <div class="container-fluid">
        <span class="nav text-end" onclick="navbar()"><i class="fas fa-bars fa-2x p-2"></i></span>
    </div>

    <div class="container-fluid">
        <div class="row">
            <?php include('../view/sidebar.php'); ?>
            <!-- main page -->
            <div class="col-sm-9 col-md-9 col-lg-9 p-2">
                <div class="card rounded shadow p-2 mx-2">
                    <h3 class="text-center">TRANSFER WITHIN OUR BANK</h3>
                    <form action="" method="post">
                        <?php if (isset($currError)) {
                            echo $currError;
                        }
                        if (isset($verifyError)) {
                            echo $verifyError;
                        }
                        ?>
                        <div class="m-2">
                            <label class="form-label" for="">Enter Amount that you wish to send</label>
                            <input class="form-control" type="number" name="amount" id="iamount">
                            <?php if (isset($balanceError)) {
                                echo $balanceError;
                            } ?>
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Enter Reciepient Account number</label>
                            <input class="form-control" type="number" name="acc_num" id="iacc_num">
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Description</label>
                            <input class="form-control" type="text" name="desc" id="desc">
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Pin</label>
                            <input class="form-control" type="password" name="twofa" id="twofa">
                            <?php if (isset($descError)) {
                                echo $descError;
                            } ?>
                        </div>

                        <div>
                            <input class="form-control-plaintext" id="ishowName" name="acc_name" type="text" readonly>
                        </div>
                        <!-- <input type="hidden" name="id" id="id"> -->
                        <div>
                            <button type="submit">Make Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./inbank_tran.js"></script>
    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>