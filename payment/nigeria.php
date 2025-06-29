<?php
session_start();
include "../database/bankdb.php";
require '../vendor/autoload.php';

use GuzzleHttp\Client;

$user_id = $_SESSION['User_id'];
$currency = $_SESSION['currency'];
$kyc = $_SESSION['kyc_status'];
$verification = $_SESSION['verification'];
$scope = "Nigerian Banks";
$txn_id = "tx" . mt_rand(10000000, 99999999);
$txn_type = 'transfer';
$receive_user_id = 1;

$query = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $balance = $row['balance'];
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $amount = $_POST['amount'];
    $acc_num = $_POST['acc_num'];
    $bank_code = $_POST['bank'];
    $acc_name = $_POST['acc_name'];
    $desc = $_POST['desc'];
    $twofa = $_POST['twofa'];
    $hasError = false;


    //Get the bank name by the code
    $client = new Client([
        'base_uri' => 'https://api.paystack.co',
        'headers' => [
            'Authorization' => 'Bearer sk_test_bce4e36c3c8fc2e08cbadca9023bf5122e069275',
            'Cache-Control' => 'no-cache'
        ]
    ]);
    $response = $client->request('GET', '/bank');
    $body = $response->getBody()->getContents();
    $banks = json_decode($body, true);

    foreach ($banks['data'] as $bank) {
        if ($bank['code'] === $bank_code) {
            $bank_name = $bank['name'];
        }
    }
    if (empty($amount)) {
        $amountError = "kindly Enter Amount";
        $hasError = true;
    }
    if (empty($acc_num)) {
        $amountError = "kindly Enter Account number";
        $hasError = true;
    }
    if (empty($bank_code)) {
        $amountError = "kindly Choose a bank";
        $hasError = true;
    }
    if (empty($desc)) {
        $descError = "kindly Choose a bank";
        $hasError = true;
    }
    if (empty($twofa)) {
        $descError = "Kindly input pin";
        $hasError = true;
    }
    if ($balance < $amount) {
        $amountLimt = "Insuficient Fund, Kind deposit up to {$amount}";
        $hasError = true;
    }
    if ($kyc != 'verified' && $verification == 'on') {
        $verifyError = "Your Account is not verified";
        $hasError = true;
    }
    $stmt = $conn->prepare("SELECT * FROM txn_set");
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();


        $ngn_min = $row['ngn_min'];
        $ngn_max = $row['ngn_max'];
    }

    if ($currency != "NGN") {
        $amountLimt = "You can only send to naira account here";
        $hasError = true;
    } elseif ($currency === "NGN") {
        if ($ngn_min > $amount) {
            $amountLimt = "limit is low, transfer more than {$ngn_min}";
            $hasError = true;
        } elseif ($ngn_max < $amount) {
            $amountLimt = "limit is low, transfer below {$ngn_max}";
            $hasError = true;
        }
    }
    $stmt = $conn->prepare("SELECT 2fa FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!password_verify($twofa, $row['2fa'])) {
            $descError = "incorrect password";
            $hasError = true;
        }
    }
    if (!$hasError) {
        $query = "INSERT INTO nig_transaction (send_user_id, receive_user_id, receive_name, send_name, amount, send_num, txn_id, txn_type, currency, scope, description) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iisssssssss", $user_id, $receive_user_id, $acc_name, $bank_name, $amount, $acc_num, $txn_id, $txn_type, $currency, $scope, $desc);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $payment_done = true;
            $balance -= $amount;
            $sql = "UPDATE user SET balance = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $balance, $user_id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $_SESSION['balance'] = $balance;
            }
        }
    } else {
        echo "An error occured";
        header("nigeria.php");
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- Select2 CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
    <!-- Select2 JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

    <link rel="stylesheet" href="../css/style.css">
    <title>Document</title>
</head>

<body>

    <div class="container-fluid">
        <div class="row mt-3">
            <?php include('../view/sidebar.php'); ?>
            <!-- main page -->
            <div class="col-sm-9 col-md-9 col-lg-9 p-2">
                <div class="card rounded shadow p-2 mx-2">
                    <h3 class="text-center">TRANSFER WITHIN NIGERIA BANKS</h3>
                    <form action="" method="post">
                        <?php if (isset($amountLimt)) {
                            echo $amountLimt;
                        }
                        if (isset($verifyError)) {
                            echo $verifyError;
                        }
                         ?>
                        <div class="m-2">
                            <label class="form-label" for="">Enter Amount that you wish to send</label>
                            <input class="form-control" type="number" name="amount" id="amount">
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Enter Reciepient Account number</label>
                            <input class="form-control" type="number" name="acc_num" id="acc_num">
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Choose Bank</label>
                            <select class="form-control" name="bank" id="bank">
                                <option value="">select bank</option>
                                <?php
                                $bank = new Client([
                                    'base_uri' => 'https://api.paystack.co',
                                    'headers' => [
                                        'Authorization' => 'Bearer sk_test_bce4e36c3c8fc2e08cbadca9023bf5122e069275',
                                        'Cache-Control' => 'no-cache'
                                    ]
                                ]);
                                $response = $bank->request('GET', '/bank');
                                $body = $response->getBody()->getContents();
                                $data = json_decode($body, true);
                                foreach ($data['data'] as $datas) {
                                    echo "<option value='{$datas['code']}'>{$datas['name']}</option>";
                                }
                                ?>
                            </select>
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
                            <input class="form-control-plaintext" id="showName" name="acc_name" type="text" readonly>
                        </div>
                        <div>
                            <button type="submit">Make Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <?php if (!empty($payment_done)): ?>

        <script>
            Swal.fire({
                title: 'Success!',
                text: 'Payment Successfully sent to <?php echo $acc_name; ?>!',
                icon: 'success',
                confirmButtonColor: '#7bed9f',
                confirmButtonText: 'Continue'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../view/dashboard.php';
                }
            });
        </script>
    <?php endif; ?>
    <script src="./transfer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>