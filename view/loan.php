<?php
session_start();
include('../database/bankdb.php');
$user_id = $_SESSION['User_id'];
$balance = $_SESSION['balance'];
$name = $_SESSION['firstName'] . " " . $_SESSION['lastName'];
$currency = $_SESSION['currency'];
$kyc = $_SESSION['kyc_status'];
$txn_id = "tx" . mt_rand(10000000, 99999999);
$send_name = "loan";
$txn_type = "receive";
$scope = "Loan";


$loan_option = [];
$currency = $_SESSION['currency'];

$query = "SELECT account_number, 2fa FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $account_number = $row['account_number'];

    $two_fa = $row['2fa'];
} else {
    echo "No account found";
}
$qquery = "SELECT * FROM loan_set";
$stmt = $conn->prepare($qquery);
$stmt->execute();
$result1 = $stmt->get_result();
if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $loan_option[] = [
            'loan_type' => $row['loan_type'],
            'interest_rate' => $row['loan_interest'],
            'loan_term' => $row['loan_term']
        ];
    }
} else {
    echo "No loan set found";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account = $_POST['account'];
    $loanAmount = $_POST['loanAmount'];
    $type = $_POST['type'];
    $loanTerm = $_POST['loanTerm'];
    $repayment = $_POST['repayment'];
    $interest = $_POST['interest'];
    $twofa = $_POST['twofa'];
    $status = "pending";
    $hasError = false;

    if (empty($account)) {
        $accountError = "Enter your Account Number";
        $hasError = true;
    } elseif ($account != $account_number) {
        $accountError2 = "Account number not correct";
        $hasError = true;
    }
    if (empty($loanAmount)) {
        $loanAmountError = "Enter the loan Amount";
        $hasError = true;
    }
    if (empty($type)) {
        $typeError = "Choose A loan Type";
        $hasError = true;
    }
    if (empty($loanTerm)) {
        $termError = "Choose a loan term";
        $hasError = true;
    }
    if (empty($repayment)) {
        $repayError = "kindly calculate your interest";
        $hasError = true;
    }
    if (empty($twofa)) {
        $twofaError = "Enter your Security PIN";
        $hasError = true;
    } elseif (!password_verify($twofa, $two_fa)) {
        $twofaError2 = "Security PIN is not correct";
        $hasError = true;
    }
    if ($kyc != 'verified' && $verification == 'on') {
        $verifyError = "Your Account is not verified";
        $hasError = true;
    }
    if (!$hasError) {
        $qquery = "INSERT INTO loan (loan_amount, pay_amount, interest, loan_type, duration, status, user_id, currency) VALUE(?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($qquery);
        $stmt->bind_param("dddsssss", $loanAmount, $repayment, $interest, $type, $loanTerm, $status, $user_id, $currency);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "registration successfull";
            //Insert into credit
            $sql = "INSERT INTO credit (receive_user_id, send_name, receive_name, amount, txn_id, txn_type, currency, scope) VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssss", $user_id, $send_name, $name, $loanAmount, $txn_id, $txn_type, $currency, $scope);
            $stmt->execute();
            // $sql = "INSERT INTO credit (receive_user_id, send_name, receive_name, amount, txn_id, txn_type, currency, scope) VALUES (?,?,?,?,?,?,?,?)";
            // $stmt = $conn->prepare($sql);
            // $stmt->bind_param("isssssss", $user_id, $send_name, $name, $loanAmount, $txn_id, $txn_type, $currency, $scope);
            // $stmt->execute();
            // $newBalance = $balance + $loanAmount;
            // $sql = "UPDATE user SET balance = ? WHERE id = ?";
            // $stmt = $conn->prepare($sql);
            // $stmt->bind_param("di", $newBalance, $user_id);
            // $stmt->execute();
            // if ($stmt->affected_rows > 0) {
            //     //Loan amount added to balance successfully
            //     $_SESSION['balance'] = $newBalance;  //Update session balance
            //     $sqll = "UPDATE loan SET credited = 'yes' WHERE user_id = ?";
            //     $stmt = $conn->prepare($sqll);
            //     $stmt->bind_param("i", $user_id);
            //     $stmt->execute();
            // } else {
            //     //Error adding loan amount to balance
            //     echo "Error updating balance: " . $conn->error;
            // }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .inpu {
            display: none;

        }

        .inpu2 {
            display: none;
        }

        .table-responsive {
            max-width: 100%;
            overflow-x: scroll;
        }
    </style>
</head>

<body>
    <div class="text-end px-3">
        <div onclick="lightMode()">
            <span id="lmode" class="px-3">light mode</span><i id="icon" class="fas fa-sun"></i>
        </div>
        <div class="ml-auto">
            <span class="nav text-end" onclick="navbar()"><i class="fas fa-bars fa-2x p-2"></i></span>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php') ?>
            <div class="col-sm-9 col-md-9 col-lg-9">
                <div class="container mt-5">
                    <h2 class="text-center mb-4">Loan Application Form</h2>

                    <div class="card p-4 shadow-sm">
                        <form method="post" action="">
                            <?php if (!empty($repayError)) {
                                echo "<p style='color:red'>$repayError</p>";
                            }
                            ?>
                            <div class="mb-3">
                                <label for="account" class="form-label">Account number</label>
                                <input name="account" type="text" class="form-control" id="account">
                                <?php if (!empty($accountError)) {
                                    echo "<p style='color:red'>$accountError</p>";
                                } elseif (!empty($accountError2)) {
                                    echo "<p style='color:red'>$accountError2</p>";
                                }
                                ?>
                            </div>

                            <div class="mb-3">
                                <label for="loanAmount" class="form-label">Loan Amount </label><?php echo " {$currency}" ?>
                                <input name="loanAmount" type="number" class="form-control" id="loanAmount">
                                <?php if (!empty($loanAmountError)) {
                                    echo "<p style='color:red'>$loanAmountError</p>";
                                }
                                ?>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Choose Loan Type</label>
                                <select class="form-select" name="type" id="type">
                                    <option value="">Choose Type</option>
                                    <?php
                                    foreach ($loan_option as $loan) {
                                        $loan_type = $loan['loan_type'];
                                        $interest = $loan['interest_rate'];
                                        echo "<option value='{$loan_type}' interest-rate-data='{$interest}'>{$loan_type}'s Loan</option>";
                                    }
                                    ?>

                                </select>
                            </div>



                            <div class="mb-3">
                                <label for="loanTerm" class="form-label">Loan Term (Months)</label>
                                <select name="loanTerm" class="form-select" id="loanTerm">
                                    <option value="">Choose term</option>
                                    <?php
                                    foreach ($loan_option as $loan) {
                                        if (!empty($loan['loan_term'])) {
                                            echo "<option value='{$loan['loan_term']}'>{$loan['loan_term']} months</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <?php if (!empty($termError)) {
                                    echo "<p style='color:red'>$termError</p>";
                                }
                                ?>
                            </div>


                            <div class="mb-3">
                                <label for="twofa" class="form-label">PIN (2FA)</label>
                                <input name="twofa" type="number" class="form-control" id="twofa">
                                <?php if (!empty($twofaError)) {
                                    echo "<p style='color:red'>$twofaError</p>";
                                } elseif (!empty($twofaError2)) {
                                    echo "<p style='color:red'>$twofaError2</p>";
                                }
                                ?>


                            </div>


                            <div>
                                <button type="button" class="btn btn-primary text-center m-3" onclick="calculate()"> Calculate Your Repayment</button>
                                <div class=" mb-3">
                                    <b id="pay"></b>
                                    <input type="number" class="navnav form-control-plaintext" name="repayment" id="repay" readonly>
                                    <b id="int"></b>
                                    <input type="number" class="navnav form-control-plaintext" name="interest" id="interest" readonly>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Apply for Loan</button>
                        </form>
                    </div>


                    <h3 class="mt-5">Loan History</h3>

                    <div class="table-responsive">
                        <table class="table table-bordered shadow-sm mt-3">
                            <thead class="table-dark rounded">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Term</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM loan WHERE user_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {



                                        echo "<tr>";
                                        echo "<td>" . $row['loan_amount'] . "</td>";
                                        echo "<td>" . $row['loan_amount'] . "</td>";
                                        echo "<td>" . $row['loan_amount'] . "</td>";
                                        echo "<td>" . $row['loan_amount'] . "</td>";
                                        echo "<td>" . $row['loan_amount'] . "</td>";

                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function calculate() {
            let loanAmount = document.getElementById("loanAmount").value;
            let type = document.getElementById("type");
            let duration = document.getElementById("loanTerm");
            let repay = document.getElementById("repay");
            let pay = document.getElementById("pay");
            let interest = document.getElementById("interest");
            let int = document.getElementById("int")
            let selectoption = type.options[type.selectedIndex];
            let repaymentRate = selectoption.getAttribute("interest-rate-data");



            if (repaymentRate) {
                let percentage = (repaymentRate / 100) * parseFloat(loanAmount);
                let repayment = percentage + parseFloat(loanAmount);
                console.log(percentage);

                repay.style.display = 'block';
                interest.style.display = 'block'
                repay.value = repayment;
                pay.textContent = "Your Repayment Amount is: ";
                int.textContent = "interest"
                interest.value = percentage;
            }

        }
    </script>
    <script src="../js/script.js"></script>
</body>

</html>