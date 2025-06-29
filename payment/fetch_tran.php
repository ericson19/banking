<?php
session_start();
// DB connection
include "../database/bankdb.php";
$user_id = $_SESSION['User_id'];
if (isset($_POST['startDate']) && isset($_POST['startDate'])) {
  $start_date = $_POST['startDate'];
  $end_date = $_POST['endDate'];
  $query = " SELECT * FROM (
        (SELECT * FROM inbank_transaction WHERE (receive_user_id = ? OR send_user_id = ?) ) 
        UNION ALL 
        (SELECT * FROM nig_transaction WHERE (receive_user_id = ? OR send_user_id = ?) )
        UNION ALL
        (SELECT * FROM credit WHERE receive_user_id = ?))
        as all_transactions
        WHERE DATE(created_at) BETWEEN ? AND ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sssssss", $user_id, $user_id, $user_id, $user_id, $user_id, $start_date,  $end_date);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $amount = number_format($row['amount'], 2);
      $type = $row['receive_user_id'] === $user_id ? "credit" : "debit";
      $color = $type === 'credit' ? 'text-success' : 'text-danger';
      $modal_id = $row['txn_id'];
      echo "<div class='col-md-6'>
                  <div class='card shadow-sm'>
                    <div class='card-body d-flex justify-content-between'>
                      <div>
                        <h6><strong>TRX ID:</strong> {$row['txn_id']}</h6>
                        <p class='mb-1 text-muted'>{$row['created_at']}</p>
                        <p class='mb-0'>{$row['send_name']}</p>
                      </div>
                      <div class='text-end'>
                        <p class='mb-1'><strong class='{$color}'>" . ($type == 'credit' ? '+ ' : '- ') . "₦{$amount}</strong></p>
                        <span class='badge bg-" . ($type == 'credit' ? 'success' : 'danger') . "'>" . ucfirst($type) . "</span>
                         <button class='btn btn-sm btn-outline-primary mt-2' data-bs-toggle='modal' data-bs-target='#{$modal_id}'>View</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class='modal fade' id='{$modal_id}' tabindex='-1' aria-labelledby='detailModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='etailModalLabel'>Transaction Details</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>

                    <p><strong>Transaction ID:</strong> TRX123456</p>
                    <p><strong>Date:</strong> 2025-05-08 14:35</p>
                    <p><strong>Description:</strong> Payment for groceries</p>
                    <p><strong>Amount:</strong> - ₦5,000.00</p>
                    <p><strong>Status:</strong> Completed</p>
                    <p><strong>Type:</strong> Debit</p>
                    <button>
                </div>
            </div>
        </div>
    </div>
                
                ";
    }
  } else {
    echo "No Transaction Within that Amount range is found";
  }
  $stmt->close();
}
if (isset($_POST['startAmount']) && isset($_POST['endAmount'])) {
  $start_amount = $_POST['startAmount'];
  $end_amount = $_POST['endAmount'];
  $query = " SELECT * FROM (
        (SELECT * FROM inbank_transaction WHERE (receive_user_id = ? OR send_user_id = ?) ) 
        UNION ALL 
        (SELECT * FROM nig_transaction WHERE (receive_user_id = ? OR send_user_id = ?) )
        UNION ALL
        (SELECT * FROM credit WHERE receive_user_id = ?))
        as all_transactions
        WHERE amount BETWEEN ? AND ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("sssssss", $user_id, $user_id, $user_id, $user_id, $user_id, $start_amount,  $end_amount);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $amount = number_format($row['amount'], 2);
      $type = $row['receive_user_id'] === $user_id ? "credit" : "debit";
      $color = $type === 'credit' ? 'text-success' : 'text-danger';
      $modal_id = $row['txn_id'];
      echo "<div class='col-md-6 col-12'>
                  <div class='card shadow-sm'>
                    <div class='card-body d-flex justify-content-between'>
                      <div>
                        <h6><strong>TRX ID:</strong> {$row['txn_id']}</h6>
                        <p class='mb-1 text-muted'>{$row['created_at']}</p>
                        <p class='mb-0'>{$row['send_name']}</p>
                      </div>
                      <div class='text-end'>
                        <p class='mb-1'><strong class='{$color}'>" . ($type == 'credit' ? '+ ' : '- ') . "₦{$amount}</strong></p>
                        <span class='badge bg-" . ($type == 'credit' ? 'success' : 'danger') . "'>" . ucfirst($type) . "</span>
                         <button class='btn btn-sm btn-outline-primary mt-2' data-bs-toggle='modal' data-bs-target='#{$modal_id}'>View</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class='modal fade' id='{$modal_id}' tabindex='-1' aria-labelledby='detailModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='etailModalLabel'>Transaction Details</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>

                    <p><strong>Transaction ID:</strong> TRX123456</p>
                    <p><strong>Date:</strong> 2025-05-08 14:35</p>
                    <p><strong>Description:</strong> Payment for groceries</p>
                    <p><strong>Amount:</strong> - ₦5,000.00</p>
                    <p><strong>Status:</strong> Completed</p>
                    <p><strong>Type:</strong> Debit</p>
                    
                </div>
            </div>
        </div>
    </div>
                
                ";
    }
  } else {
    echo "No Transaction Within that Amount range is found";
  }
  $stmt->close();
}



$conn->close();
