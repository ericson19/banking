<?php
session_start();
include("../database/bankdb.php");
$user_id = $_SESSION['User_id'];
$query = "SELECT * FROM security WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Login Activities</title>
</head>

<body>

    <div class="container-fluid">

        <h3 class="text-center">Login Activities</h3>
        <table class="table shadow rounded">
            <tr class="table-head">
                <th>IP Address</th>
                <th>Country</th>
                <th>City</th>
                <th>Device</th>
                <th>Login Time</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                             <td>{$row['ip_address']}</td>
                             <td>{$row['ip_country']}</td>
                             <td>{$row['ip_city']}</td>
                              <td>{$row['os']}</td>
                              <td>{$row['created_at']}</td>
                              </tr>";
                }
            }
            ?>

        </table>

    </div>
</body>

</html>