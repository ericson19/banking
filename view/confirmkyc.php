<?php
session_start();
include "../database/bankdb.php";
$user_id = $_SESSION['User_id'];
$kyc = $_SESSION['kyc_status'];
$kyc_verify = "ongoing";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = htmlspecialchars($_POST['idNum'], ENT_QUOTES);
    $houseAd = htmlspecialchars($_POST['houseAd'], ENT_QUOTES);
    $hasError = false;
    if (empty($id_number)) {
        $idnumError = 'Please Enter Your Valid ID number';
        $hasError = true;
    }
    if (empty($houseAd)) {
        $idnumError = 'Please Enter Your Valid Resident Address';
        $hasError = true;
    }
    $imgPath = $_FILES['img']['tmp_name'];
    $imgName = str_replace(' ', '_', basename($_FILES['img']['name']));
    $uploadDir = "../upload/";
    $sendTo = $uploadDir . $imgName;
    if (!move_uploaded_file($imgPath, $sendTo)) {
        echo 'image not properly parsed';
    } elseif (!$hasError) {
        $stmt = $conn->prepare("INSERT INTO kyc (user_id, id_number, house_address, document) VALUE (?,?,?,?)");
        $stmt->bind_param("isss", $user_id, $id_number, $houseAd, $sendTo);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "UPLOAD COMPLETED SUCCESSFULLY, ";
            $stmt = $conn->prepare("UPDATE user SET kyc_status = ? WHERE id = ?");
            $stmt->bind_param("si", $kyc_verify, $user_id);
            $stmt->execute();
        }
    }

    echo "<pre>";
    print_r($imgName);
    echo "</pre>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>KYC Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .limit-box {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <?php
        // include "../database/bankdb.php";
        // session_start();
        // $user_id = $_SESSION['User_id'];
        // $stmt = $conn->prepare("SELECT kyc_status FROM user WHERE id = ?");
        // $stmt->bind_param("i", $user_id);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // if ($result->num_rows > 0) {
        //     $row = $result->fetch_assoc();
        //     $kyc = $row['kyc_status'];

        // }
        if ($kyc === "not_verified") {
            echo " <h2 class='mb-4 text-center'>KYC Verification</h2>
        <div class='text-center'>
            <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#kycModal'>
                Start KYC Verification
            </button>
        </div>";
        }
        if ($kyc === "ongoing") {
            echo "<div class='container-fluid'>
        <p class='text-center text-info'> YOUR VERIFICATION IS CURRENTLY ONGOING, <br> KINDLY CHECK AGAIN WITHIN 48 HOURS</p>
       </div>";
        } elseif ($kyc === "verified") {
            $stmt = $conn->prepare("SELECT * FROM kyc WHERE user_id = ? ORDER BY id DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                echo "<div class='container-fluid'>
                    <p class='text-center text-info'> Verification Successful, Account is verified ✅ </p>
                    <img class='mx-auto d-block' style='width:25rem' src='{$row['document']}' alt=''>
            </div>";
            }
        }

        ?>

        <!-- Display limits -->
        <div class="limit-box p-3 mt-4">
            <h5>Account Limits</h5>
            <p><strong>Current Limit:</strong> ₦50,000</p>
            <p><strong>Potential Limit After KYC:</strong> ₦500,000</p>
        </div>
    </div>



    <!-- KYC Confirmation Modal -->
    <div class="modal fade" id="kycModal" tabindex="-1" aria-labelledby="kycModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kycModalLabel">Confirm KYC</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Do you want to continue with KYC verification?</p>
                    <div class="mb-3">
                        <label class="form-label" for="">ID Number</label>
                        <input class="form-control" type="text" name="idNum" id="">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="">House Address</label>
                        <input class="form-control" type="text" name="houseAd" id="">
                    </div>
                    <div class="mb-3">
                        <label for="idUpload" class="form-label">Upload Valid ID</label>
                        <input type="file" class="form-control" name="img" id="idUpload" accept="image/*,application/pdf" required>
                        <div class="form-text">Accepted: NIN, Voter's Card, Driver's License, etc.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit KYC</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optional JS to show an alert on form submission -->
    <!-- <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const fileInput = document.getElementById('idUpload');
            if (fileInput.files.length === 0) {
                alert("Please upload a valid ID.");
            } else {
                alert("KYC submitted successfully! We will review your document shortly.");
                const modal = bootstrap.Modal.getInstance(document.getElementById('kycModal'));
                modal.hide();
            }
        });
    </script> -->

</body>

</html>