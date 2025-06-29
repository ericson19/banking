<?php
include "./database/bankdb.php";
// $request = file_get_contents("php://input");
// $data = json_encode($request, true);
if (isset($_POST['num'])) {
    $num = $_POST['num'];

    $query = "SELECT * FROM user WHERE account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $num);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($row as $data) {
            $bank_name = "{$data['LastName']} {$data['FirstName']} {$data['MidName']}";
            $name = [
                "status" => true,
                "name" => $bank_name,

            ];
        }
        header("Content-Type: application/json");
        echo json_encode($name);
    }
}
