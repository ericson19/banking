<?php
session_reset();
include('../database/bankdb.php');
$id = $_GET['id'];
$query = "DELETE FROM card WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    $_SESSION['deleted'] = "Card Deactivated successfully";
    header('location: ../view/card.php');
}
