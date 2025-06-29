<?php
session_start();
include('../database/bankdb.php');
//create plans array where all the rows will be stored of UI Update
$plans = [];
$user_id = $_SESSION['User_id'];

$query = "SELECT * FROM plan";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $plans[$row['plan_name']] = $row;
}
$plans_json = json_encode($plans);
// echo "$plans_json";
echo "<script>const plans_json = {$plans_json}</script>";


//SELECT BALANCE
$sql = "SELECT balance FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $balance = $row['balance'];
}
//INSERT INVESTMENT DETAIL
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $planName2 = $_POST['planName2'];
    $plan_id = $_POST['planID'];
    $planDuration = $_POST['planDuration'];
    $min_Amount = $_POST['minAmount'];
    $max_Amount = $_POST['maxAmount'];
    $status = 'ongoing';
    $created_at = date('Y-m-d H:i:s');
    $hasError = false;


    if ($amount < $min_Amount) {
        $minError = "Amount too small for this plan";
        $hasError = true;
    } elseif ($amount > $max_Amount) {
        $maxError = "Amount too big for this plan";
        $hasError = true;
    }
    if ($amount > $balance) {
        $lowError = "Insufficient funds: <a href='loan.php'>DEPOSIT FUNDS</a>";
        $hasError = true;
    } elseif (empty($amount)) {
        $AmountError = "Kindly Enter Amount";
        $hasError = true;
    }
    if (empty($planName2)) {
        $planError = "Kindy Choose a plan let's proceed";
        $hasError = true;
    }
    if (!$hasError) {
        $query = "INSERT INTO investment (plan, user_id, plan_name, Amount, inv_duration, status, created_at) VALUE (?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iississ", $plan_id, $user_id, $planName2, $amount, $planDuration, $status, $created_at);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "investment plan purchase";
            $balance = $balance - $amount;
            $sql = "UPDATE user SET balance = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $balance, $user_id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $_SESSION['balance'] = $balance;
            }
        }
    }
}


// echo $balance;
// echo "<br>";
// echo $min_Amount;
// echo "<br>";
// echo $max_Amount;
// echo "<br>";
// echo $amount;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>INVEST AND EARN</title>
    <style>
        .plan h6:hover {
            background-color: #f0f0f0;
            border-radius: 5px;

            cursor: pointer;

        }

        .plan {
            opacity: 0;
            transform: translateY(30%);
            transition: transform 0.5s ease-in-out;
            pointer-events: none;
            cursor: pointer;
        }

        .plan.move {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .direction {
            transition: transform 0.5s ease-in-out;
        }

        .direction.rotate {
            transform: rotate(90deg);
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-between">
        <div onclick="lightMode()">
            <span id="lmode" class="px-3">light mode</span><i id="icon" class="fas fa-sun"></i>
        </div>
        <span class="nav text-end" onclick="navbar()"><i class="fas fa-bars fa-2x p-2"></i></span>
    </div>
    <div class="container-fluid">
        <div class="row mt-2">
            <?php include('sidebar.php') ?>
            <div class="col-sm-9 col-md-9 col-lg-9">
                <div class="items container mt-2  p-2 rounded-3 shadow">
                    <div class="row">
                        <div class="col-12 col-md-8 col-lg-8">

                            <div class="border border-1 p-3 rounded-3 shadow-sm">
                                <label for="" class="form-label">Choose and investment plan</label>
                                <br>
                                <?php
                                if (!empty($planError)) {
                                    echo $planError;
                                }
                                ?>
                                <div class="w-100 rounded-3 d-flex justify-content-between shadow-sm  p-1 mt-2" onclick="selectPlan()">
                                    <h5>SELECT PLANS</h5>
                                    <span class="direction px-4" id="direction"><i class="fa fa-angle-right"></i></span>
                                </div>
                                <div class="plan w-100 rounded-3 shadow-sm  p-1 mt-2">
                                    <?php
                                    foreach ($plans as $plan) {
                                        echo "<h5 class='plans' data-plan='{$plan['plan_name']}'>{$plan['plan_name']}</h5>";
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="border border-1 p-3 rounded-3 shadow-sm">
                                <h6>Choose amount</h6>
                                <div class="row text-center">
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-danger mt-2 px-3" onclick="two()">200</button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-danger  mt-2 px-3" onclick="five()">500</button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-danger  mt-2 px-3" onclick="one()">1000</button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-danger  mt-2" onclick="two0()">2000</button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-danger  mt-2" onclick="five0()">$5000</button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-danger  mt-2" onclick="one0()">$10000</button>
                                    </div>
                                </div>
                                <h6 class="mt-5">or enter amount here</h6>
                                <input id="Eamount" type="number" class="form-control" onkeyup="Eamount()">
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="inv-det border rounded shadow p-4">
                                <h6>your investment details</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mt-2">
                                            <p>Name of plan:</p>
                                            <p id="plan_name"></p>

                                        </div>
                                        <div class="mt-2">
                                            <p>Duration:</p>
                                            <span id="duration"></span><span id="day"></span>

                                        </div>
                                        <div class="mt-2">
                                            <p>minimium deposit:</p>

                                            <p id="minDeposit"></p>
                                        </div>
                                        <div class="mt-2">
                                            <p>minimium return:</p>
                                            <p id="minReturn"></p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <p>Plan Price:</p>
                                            <p id="plan_price"></p>
                                        </div>
                                        <div>
                                            <p>profit:</p>
                                            <p id="profit"></p>
                                        </div>
                                        <div>
                                            <p>maximium deposit:</p>
                                            <p id="maxDeposit"></p>
                                        </div>
                                        <div>
                                            <p>maximuim return:</p>
                                            <p id="maxReturn"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-top border-bottom mt-4 p-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <p>Payment method</p>
                                        </div>
                                        <div class="col-6">
                                            <p>account balance: <?php echo "<span> {$balance}</span>" ?></p>
                                        </div>
                                    </div>
                                </div>
                                <form action="" method="post">
                                    <div class="border-top border-bottom mt-4 p-2">
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="pt-2">Amount to Invest</p>
                                            </div>
                                            <div class="col-6">
                                                <input id="mainAmount" type="number" name="amount" class="form-control-plaintext">
                                            </div>
                                            <?php
                                            if (!empty($AmountError)) {
                                                echo $AmountError;
                                            }
                                            ?>
                                        </div>

                                        <div>
                                            <?php
                                            if (!empty($minError)) {
                                                echo $minError;
                                            }

                                            if (!empty($maxError)) {
                                                echo $maxError;
                                            }
                                            if (!empty($lowError)) {
                                                echo $lowError;
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" value="" id="planDuration" name="planDuration">
                                        <input type="hidden" value="" id="planID" name="planID">
                                        <input type="hidden" value="" id="planName2" name="planName2">
                                        <input type="hidden" value="" id="maxAmount" name="maxAmount">
                                        <input type="hidden" value="" id="minAmount" name="minAmount">
                                    </div>
                                    <button type="submit">submit</button>
                                </form>
                            </div>
                            <p class="bg-dark">helo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function selectPlan() {
            const plan = document.querySelector('.plan');
            const direction = document.getElementById('direction');
            // Toggle the class to show/hide the plan options
            plan.classList.toggle('move');
            direction.classList.toggle('rotate');
        }
        // Add event listeners to each plan option

        const planOptions = document.querySelectorAll('.plans');
        planOptions.forEach(el => {
            el.addEventListener('click', function() {


                const plan_name = document.getElementById('plan_name');
                const planDuration = document.getElementById('planDuration');
                const duration = document.getElementById('duration');
                const day = document.getElementById('day');
                const minAmount = document.getElementById('minAmount');
                const maxAmount = document.getElementById('maxAmount');
                const minDeposit = document.getElementById('minDeposit');
                const maxDeposit = document.getElementById('maxDeposit');
                const minReturn = document.getElementById('minReturn');
                const maxReturn = document.getElementById('maxReturn');
                const planID = document.getElementById('planID');
                const planName2 = document.getElementById('planName2');
                const profit = document.getElementById('profit');
                const plan_price = document.getElementById('plan_price');


                const select = this.getAttribute('data-plan');
                const plankey = plans_json[select];
                // UPDATE PLAN VALUE
                planID.value = plankey.id;
                planName2.value = plankey.plan_name;
                maxAmount.value = plankey.max_amount;
                minAmount.value = plankey.min_amount;
                planDuration.value = plankey.duration;

                //UPDATE UI VALUES
                duration.textContent = plankey.duration;

                if (planDuration.value > 1) {
                    day.innerHTML = " days";
                } else {
                    day.innerHTML = " day";
                }
                console.log(planDuration.value);

                plan_name.textContent = plankey.plan_name;
                minDeposit.textContent = plankey.min_amount;
                maxDeposit.textContent = plankey.max_amount;
                minReturn.textContent = plankey.min_return;
                maxReturn.textContent = plankey.max_return;
                profit.innerHTML = plankey.plan_interest;
                plan_price.textContent = plankey.min_amount;


                // console.log(plankey.min_amount);
                console.log(plankey);

                console.log("selected data", select);

            })
        });


        console.log(plans_json);
    </script>
    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>

</html>