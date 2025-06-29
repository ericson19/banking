<?php
session_start();
include('../database/bankdb.php');
include('../payment/recieve.php');
if (!isset($_SESSION['email'])) {

    header("location: ../auth/login.php");
}
$balance = $_SESSION['balance'];
$user_id = $_SESSION['User_id'];
$sql = "SELECT * FROM loan WHERE user_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $loan_amount = $row['loan_amount'];
    $repayment = $row['pay_amount'];
    $interest = $row['interest'];
    $loan_type = $row['loan_type'];
    $duration = $row['duration'];
    $status = $row['status'];
    $credited = $row['credited'];
    $currency = $row['currency'];
    $date = $row['date'];
}
echo "User ID from session: " . $_SESSION['User_id'];
$query = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $balance = $row['balance'];
    $_SESSION['kyc_status'] = $row['kyc_status'];
}

if (isset($loan_amount)) {
    $newBalance = $balance + $loan_amount;
}
if (isset($status)) {
    if ($status === "approved" && $credited === "no") {


        $sql = "UPDATE user SET balance = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $newBalance, $user_id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            //Loan amount added to balance successfully
            $_SESSION['balance'] = $newBalance;  //Update session balance
            $sqll = "UPDATE loan SET credited = 'yes' WHERE user_id = ?";
            $stmt = $conn->prepare($sqll);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        } else {
            //Error adding loan amount to balance
            echo "Error updating balance: " . $conn->error;
        }
    }
}
$sql = "SELECT * FROM credit WHERE receive_user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['txn_type'] === 'transfer') {
            $newBalance = $balance + $row['amount'];
            $sql = "UPDATE user SET balance = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("di", $newBalance, $user_id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                // Loan amount added to balance successfully
                $_SESSION['balance'] = $newBalance; // Update session balance
                $sqll = "UPDATE credit SET txn_type = 'receive' WHERE receive_user_id = ?";
                $stmt = $conn->prepare($sqll);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
            };
        }
    }
}


$qquery = "SELECT * FROM investment WHERE user_id = ?";
$stmt = $conn->prepare($qquery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['plan_name'] = $row['plan_name'];
    $_SESSION['Amount'] = $row['Amount'];
    $_SESSION['profit'] = $row['profit'];
    $_SESSION['total_profit'] = $row['total_profit'];
    $_SESSION['status'] = $row['status'];
    $_SESSION['profit_paid'] = $row['profit_paid'];
    if ($_SESSION['profit_paid'] === "yes") {
        $balance += $_SESSION['profit'];
        $total_profit = $_SESSION['total_profit'] + $_SESSION['profit'];
        $sql = "UPDATE user SET balance = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $balance, $user_id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $_SESSION['balance'] = $balance;
        }
        $profit_paid = 'no';
        $sql = "UPDATE investment SET profit_paid = ?, total_profit = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $profit_paid, $total_profit, $user_id);
        $stmt->execute();
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
    <title>Document</title>

</head>

<body>
    <div class=" container d-flex justify-content-between px-3">
        <span class="nav text-end" onclick="navbar()"><i class="fas fa-bars fa-2x p-2"></i></span>
        <div class="text-end" onclick="lightMode()">
            <span id="lmode" class="px-3">light mode</span><i id="icon" class="fas fa-sun"></i>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row mt-2">
            <?php include('sidebar.php'); ?>

            <!-- main page -->
            <div class="col-sm-9 col-md-9 col-lg-9 p-2">
                <div>
                    <div class="greet">
                        <div>
                            <h3 class="text-start">Good Day, <?php echo "<span>{$_SESSION['firstName']} {$_SESSION['lastName']} </span>"; ?></h3>
                            <p class="text-start">Welcome to your banking dashboard. Here you can manage your account,
                                make transfers, check transactions, and more.</p>
                        </div>
                        <div>
                            <span class="nav text-end" onclick="navbar()"><i class="fas fa-bars fa-2x p-2"></i></span>
                            <a href="" class="btn btn-info m-2">Deposit</a>
                            <a href="" class="btn btn-danger m-2">Transfer</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="card bg-primary text-white mt-2">
                                <div class="card-header">
                                    <h5>User's Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <img class="img-logo" src="<?php print $_SESSION['img'] ?>" alt="">
                                            <h6 class="mt-3">Current city</h6>
                                            <p><?php echo $_SESSION['city'] ?></p>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mt-3">Available Balance</h6>
                                            <p class="text-white"><?php echo "<div>{$_SESSION['currency']}" . number_format($_SESSION['balance'], 2) . "</div>"; ?></p>
                                            <h6><strong><?php echo "<div>{$_SESSION['firstName']} {$_SESSION['lastName']} " . ($_SESSION['kyc_status'] == 'verified' ? '✅' : '') . "</div>"; ?></strong></h6>
                                            <h6>Nigeria</h6>
                                            <p><?php echo $_SESSION['ip'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card w-100 mt-4">
                                <div class="card-header d-flex justify-content-between">
                                    <h5>Recent Transactions</h5>
                                    <a class="link-item" href="../payment/filt.php">view all</a>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <?php
                                        //get all the transactions done
                                        $sql = " SELECT * FROM (
                                            (SELECT * FROM inbank_transaction WHERE (receive_user_id = ? OR send_user_id = ?) ) 
                                            UNION ALL 
                                            (SELECT * FROM nig_transaction WHERE (receive_user_id = ? OR send_user_id = ?) )
                                            UNION ALL
                                            (SELECT * FROM credit WHERE receive_user_id = ?))
                                            as all_transactions ORDER BY created_at DESC LIMIT 2";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("iiiii", $user_id, $user_id, $user_id, $user_id, $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                if ($row['send_user_id'] == $user_id) {
                                                    echo "<div class='col-4'>";
                                                    echo " <h6><strong>{$row['txn_id']}</strong></h6>";
                                                    echo "<p>{$row['send_name']}</p>";
                                                    echo "<p>SENT</p>";
                                                    echo "</div>";
                                                    echo "<div class='col-4 text-end'>";
                                                    echo "<h6><strong>Amount</strong></h6>";
                                                    echo   "<p>{$row['amount']}</p>";
                                                    echo  "completed";
                                                    echo  "</div>";
                                                    echo "<div class='col-4 text-end'>";
                                                    echo  "<p><strong>Credit</strong></p>";
                                                    echo  "<p>{$row['created_at']}</p>";
                                                    echo "<button class='btn border-danger'>view details</button>";
                                                    echo "</div>";
                                                } else {
                                                    echo "<div class='col-4'>";
                                                    echo " <h6><strong>{$row['txn_id']}</strong></h6>";
                                                    echo "<p>{$row['send_name']}</p>";
                                                    echo "<p>RECEIVED</p>";
                                                    echo "</div>";
                                                    echo "<div class='col-4 text-end'>";
                                                    echo "<h6><strong>Amount</strong></h6>";
                                                    echo   "<p>{$row['amount']}</p>";
                                                    echo  "completed";
                                                    echo  "</div>";
                                                    echo "<div class='col-4 text-end'>";
                                                    echo  "<p><strong>Debit</strong></p>";
                                                    echo  "<p>{$row['created_at']}</p>";
                                                    echo "<button class='btn border-danger'>view details</button>";
                                                    echo "</div>";
                                                }
                                            };
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <!-- next line -->
                        </div>
                        <div class="col-md-6 col-12">
                            <div>
                                <div class="d-flex justify-content-between">
                                    <?php echo "<h6><strong>{$_SESSION['accType']}</strong></h6>" ?>
                                    <a class="nav-link " href="">Transfer Fund</a>
                                </div>
                                <div class="items w-100 p-3">
                                    <?php echo "<p><i class='fas fa-piggy-bank px-3'></i>{$_SESSION['account_number']}</p>" ?>

                                    <?php echo "<div class='px-3'>{$_SESSION['currency']}" . number_format($_SESSION['balance'], 2) . "</div>"; ?>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mt-3">
                                    <h6><strong>Loans and lines of credit</strong></h6>
                                    <a class="nav-link " href="../view/loan.php">View All loans</a>
                                </div>
                                <div class="items w-100 p-3">
                                    <div class="d-flex justify-content-between">
                                        <p class=""><?php echo isset($loan_type) ? "<span>" . strtoupper($loan_type) . "</span>" : ""; ?> Loan Type</p>
                                        <p class="">Loan Amount: <?php echo isset($loan_amount) ? "<span>" . $currency . number_format($loan_amount, 2) . "</span>" : ""; ?></p>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <p class="">Repayment Amount: <?php echo isset($repayment) ? "<span>" . $currency . number_format($repayment, 2) . "</span>" : ""; ?></p>
                                        <p class="">interest:<?php echo isset($interest) ? "<span> {$interest} %</span>" : "" ?></p>

                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="">Loan Duration: <?php echo isset($duration) ? "<span>{$duration} months</span>" : "" ?></p>
                                        <?php
                                        if (isset($status)) {
                                            if ($status === "pending") {
                                                echo "<b class='' style='color:yellow'>$status</b>";
                                            } elseif ($status === "approved") {
                                                echo "<b class='' style='color:green'>$status</b>";
                                            } elseif ($status === "rejected") {
                                                echo "<b class='' style='color:red'>$status</b>";
                                            }
                                        } else {
                                            echo "NO LOAN AVAILABLE";
                                        }

                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mt-3">
                                    <h6><strong>Investments</strong></h6>
                                    <a class="nav-link " href="../view/investment.php
                                    ">Invest Now</a>
                                </div>
                                <div class="items w-100 p-3">
                                    <div class="d-flex justify-content-between">
                                        <p class="">Active Plan: Standard Plan</p>
                                        <p class="">Amount invested: $500</p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="">Total Profit: <?php echo isset($_SESSION['total_profit']) ? "<span>{$_SESSION['total_profit']}</span>" : "" ?></p>
                                        <p class="">Ongoing</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h6><strong>Balance Flow</strong></h6>
                                <div class="items">

                                    <canvas id="flowchart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- TradingView Forex Cross Rates Widget -->
                    <div class="tradingview-widget-container m-3 mx-auto">
                        <h3>Currency conversions</h3>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-forex-cross-rates.js" async>
                            {
                                "width": "100%",
                                "height": 300,
                                "currencies": ["USD", "EUR", "GBP", "NGN", "CAD", "CNY"],
                                "isTransparent": false,
                                "colorTheme": "dark",
                                "locale": "en"
                            }
                        </script>
                    </div>
                    <!-- TradingView Ticker Widget -->


                    <!-- TradingView Advanced Chart Widget -->
                    <div class="tradingview-widget-container mt-5">

                        <div id="tradingview_advanced"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                        <script type="text/javascript">
                            new TradingView.widget({
                                "width": "100%",
                                "height": 600,
                                "symbol": "FX:EURUSD",
                                "interval": "30",
                                "timezone": "Etc/UTC",
                                "theme": "dark", // Change to "light" for light mode
                                "style": "1",
                                "locale": "en",
                                "toolbar_bg": "#f1f3f6",
                                "enable_publishing": false,
                                "withdateranges": true,
                                "hide_side_toolbar": false,
                                "allow_symbol_change": true,
                                "save_image": false,
                                "container_id": "tradingview_advanced"
                            });
                        </script>
                    </div>
                    <!-- TradingView Advanced Chart Widget END -->


                    <!-- TradingView Ticker Widget -->
                    <div class="tradingview-widget-container">
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                            {
                                "symbols": [{
                                        "proName": "FX:EURUSD",
                                        "title": "EUR/USD"
                                    },
                                    {
                                        "proName": "FX:GBPUSD",
                                        "title": "GBP/USD"
                                    },
                                    {
                                        "proName": "FX:USDJPY",
                                        "title": "USD/JPY"
                                    },
                                    {
                                        "proName": "NASDAQ:AAPL",
                                        "title": "Apple"
                                    },
                                    {
                                        "proName": "NASDAQ:TSLA",
                                        "title": "Tesla"
                                    }
                                ],
                                "colorTheme": "dark",
                                "isTransparent": false,
                                "displayMode": "adaptive",
                                "locale": "en"
                            }
                        </script>
                    </div>
                    <!-- TradingView Ticker Widget END -->



                </div>
            </div>
        </div>
    </div>


    <select name="currency" id="">
        <option value="1">gbp</option>
        <option value="2">ngn</option>
        <option value="3">usd</option>
    </select>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // CHART EVENTS
        let data = {
            labels: ['Credit', 'Debit'],
            datasets: [{
                data: [<?php echo $total_receive; ?>, <?php echo $total_sent; ?>],
                backgroundColor: ['green', 'yellow'],
                label: 'Account Balance'

            }]

        }
        const ctx = document.getElementById('flowchart').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: data
        })
    </script>

    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>