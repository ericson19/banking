<?php
// session_start();
include "../database/bankdb.php";
$user_id = $_SESSION['User_id'];

//get the total amount recieved
$sql = "SELECT SUM(amount) AS total_amount FROM (SELECT amount FROM inbank_transaction WHERE receive_user_id = ?
UNION ALL
SELECT amount FROM nig_transaction WHERE receive_user_id = ?
UNION ALL
SELECT amount FROM credit WHERE receive_user_id = ?)
AS combined_table";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total_receive = $result->fetch_assoc()['total_amount'];


//get the total amount sent
$sql = "SELECT SUM(amount) AS total_amount FROM (SELECT amount FROM inbank_transaction WHERE send_user_id = ? 
UNION ALL
SELECT amount FROM nig_transaction WHERE send_user_id = ? 
UNION ALL
SELECT amount FROM credit WHERE send_user_id = ?) AS combined_table";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total_sent = $result->fetch_assoc()['total_amount'];

// echo $total_sent;
// echo "<br>";
// echo $total_receive;

//get all the transactions done
// $sql = "SELECT * FROM inbank_transaction WHERE receive_user_id = ? OR send_user_id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $user_id);
// $stmt->execute();
// $result = $stmt->get_result();
// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         if ($row['send_user_id'] === $user_id) {
//         }
//     }
// }
