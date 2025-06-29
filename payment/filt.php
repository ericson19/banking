<?php
session_start();
// DB connection
include "../database/bankdb.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container-fluid">
        <span class="nav text-end" onclick="navbar()"><i class="fas fa-bars fa-2x p-2"></i></span>
    </div>

    <div class="container-fluid">
        <div class="row">
            <?php include('../view/sidebar.php'); ?>
            <div class="col-sm-12 col-md-12 col-lg-9 p-2">
                <div class="container-fluid">
                    <h3 class="mb-4">Search Transactions by Date</h3>
                    <!-- filter by date -->
                    <div class="row  mb-4">
                        <div class="col-md-4">
                            <input type="date" id="startDate" class="form-control" placeholder="Start Date">
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="endDate" class="form-control" placeholder="End Date">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" onclick="fetchDate()">View</button>
                        </div>
                    </div>

                    <!-- filter by amount -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <input type="text" id="startAmount" class="form-control" placeholder="Start Amount">
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="endAmount" class="form-control" placeholder="End Amount">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" onclick="fetchAmount()">View</button>
                        </div>
                    </div>


                    <div class="row" id="transactionResults">
                        <!-- Transactions will be inserted here -->
                    </div>



                    <h3 class="mb-4">Transaction History</h3>
                    <div class="container mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search transactions ID...">
                    </div>

                    <div class="row g-3">
                        <?php
                        $user_id = $_SESSION['User_id'];
                        $query = " SELECT * FROM (
                (SELECT * FROM inbank_transaction WHERE (receive_user_id = ? OR send_user_id = ?) ) 
                UNION ALL 
                (SELECT * FROM nig_transaction WHERE (receive_user_id = ? OR send_user_id = ?) )
                UNION ALL
                (SELECT * FROM credit WHERE receive_user_id = ?))
                as all_transactions ORDER BY created_at DESC";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("sssss", $user_id, $user_id, $user_id, $user_id, $user_id,);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $amount = number_format($row['amount'], 2);
                                $txn_id = $row['txn_id'];

                                $type  = $row['receive_user_id'] === $user_id ? "credit" : "debit";
                                $color = $type === "credit" ? 'bg-success' : 'bg-danger';
                                $txn_id = $row['txn_id'];
                                $send_name = $row['send_name'];
                                $modal_id = $row['txn_id'];
                                echo "
                    <div class='col-12 col-md-12 col-lg-6'>
                <div class='card shadow-sm'>
                    <div class='card-body'>
                        <div class='d-flex justify-content-between'>
                            <div>
                                <h6><strong>TRX ID:</strong>{$row['txn_id']}</h6>
                                <p class='mb-1 text-muted'>{$row['created_at']}</p>
                                <p class='mb-0'>{$row['send_name']}</p>
                            </div>
                            <div class='text-end'>
                                <p class='mb-1'><strong class='debit'>" . ($type == 'credit' ? '+' : '-') . "{$amount}</strong></p>
                                <span class='badge {$color}'>{$type}</span><br>
                
                                <a class='btn btn-sm btn-outline-primary mt-2' href='details.php?id={$modal_id}'>VIEW HERE </a>
                            </div>
                           
                        </div>
                      
                    </div>
                </div>
               </div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/transaction.js"></script>


</body>

</html>