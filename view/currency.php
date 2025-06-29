<?php
session_start();
include('../database/bankdb.php');

if (!isset($_SESSION['email'])) {
    die("User not logged in");
}

$email = $_SESSION['email'];
$user_id = $_SESSION['User_id'];

// Fetch user details
$query = "SELECT * FROM user WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $balance = $row['balance'];
    $dcurrency = $row['currency'];
}

include('../api.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['conversion_rates'])) {
    $currency = $_POST['currency'];

    if (!empty($currency) && $currency !== $dcurrency) {


        if (isset($data['conversion_rates'][$currency]) && isset($data['conversion_rates'][$dcurrency])) {
            // Convert balance to new currency
            $mainBalance = ($balance / $data['conversion_rates'][$dcurrency]) * $data['conversion_rates'][$currency];

            // Update the balance in the database
            $query = "UPDATE user SET balance = ?, currency = ? WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("dss", $mainBalance, $currency, $email);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $_SESSION['currency'] = $currency;
                $_SESSION['balance'] = $mainBalance;
                $balance = $mainBalance; // Update balance for display

                //update transaction amount
                $sql =  "SELECT id, amount FROM nig_transaction WHERE receive_user_id = ? OR send_user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id, $user_id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    while ($tr = $res->fetch_assoc()) {
                        $amount = $tr['amount'];

                        if (!is_numeric($amount) || $amount == 0) {
                            continue; // skip invalid or zero amounts
                        }
                        $converted = ($amount / $data['conversion_rates'][$dcurrency]) * $data['conversion_rates'][$currency];
                        $update = "UPDATE nig_transaction SET amount = ?, currency = ? WHERE id = ?";
                        $uStmt = $conn->prepare($update);
                        $uStmt->bind_param("dsi", $converted, $currency, $tr['id']);
                        $uStmt->execute();
                    }
                } else {
                    echo "no amount found";
                }
                $sql =  "SELECT id, amount FROM inbank_transaction WHERE receive_user_id = ? OR send_user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id, $user_id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    while ($tr = $res->fetch_assoc()) {
                        $amount = $tr['amount'];

                        if (!is_numeric($amount) || $amount == 0) {
                            continue; // skip invalid or zero amounts
                        }
                        $converted = ($amount / $data['conversion_rates'][$dcurrency]) * $data['conversion_rates'][$currency];
                        $update = "UPDATE inbank_transaction SET amount = ?, currency = ? WHERE id = ?";
                        $uStmt = $conn->prepare($update);
                        $uStmt->bind_param("dsi", $converted, $currency, $tr['id']);
                        $uStmt->execute();
                    }
                } else {
                    echo "no amount found";
                }
                $sql =  "SELECT id, amount FROM credit WHERE receive_user_id = ? OR send_user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id, $user_id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    while ($tr = $res->fetch_assoc()) {
                        $amount = $tr['amount'];

                        if (!is_numeric($amount) || $amount == 0) {
                            continue; // skip invalid or zero amounts
                        }
                        $converted = ($amount / $data['conversion_rates'][$dcurrency]) * $data['conversion_rates'][$currency];
                        $update = "UPDATE credit SET amount = ?, currency = ? WHERE id = ?";
                        $uStmt = $conn->prepare($update);
                        $uStmt->bind_param("dsi", $converted, $currency,  $tr['id']);
                        $uStmt->execute();
                    }
                } else {
                    echo "no amount found";
                }
            }
        } else {
            echo "<script>alert('Invalid currency selection. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Invalid currency selection. Please try again.');</script>";
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
    <title>Currency Conversion</title>
</head>

<body>
    <div class="row">
        <?php include('../view/sidebar.php'); ?>
        <div class="col-9 col-sm-9">
            <h1>Welcome, <?php echo "{$_SESSION['firstName']} {$_SESSION['lastName']}"; ?></h1>
            <h3>Your balance: <?php echo "{$_SESSION['currency']} " . number_format($balance, 2); ?></h3>

            <form method="POST">
                <select class="form-control" name="currency">
                    <option value="">Select currency</option>
                    <option value="EUR">Euro (€)</option>
                    <option value="USD">US Dollar ($)</option>
                    <option value="GBP">British Pound (£)</option>
                    <option value="NGN">Nigerian Naira (₦)</option>
                    <option value="CNY">Chinese Yuan (¥)</option>
                </select>
                <button type="submit">Convert</button>
            </form>
        </div>
    </div>
</body>

</html>