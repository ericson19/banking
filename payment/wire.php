<?php
session_start();
include('../database/bankdb.php');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>inbank transfer</title>
</head>

<body>
    <div class="container-fluid">
        <span class="nav text-end" onclick="navbar()"><i class="fas fa-bars fa-2x p-2"></i></span>
    </div>

    <div class="container-fluid">
        <div class="row">
            <?php include('../view/sidebar.php'); ?>
            <!-- main page -->
            <div class="col-sm-9 col-md-9 col-lg-9 p-2">
                <div class="card rounded shadow p-2 mx-2">
                    <h3 class="text-center">WIRE TRANSFER(INTERNATIONAL)</h3>
                    <form action="" method="post">
                        <div class="m-2">
                            <label class="form-label" for="">Enter Amount that you wish to send</label>
                            <input class="form-control" type="number" name="amount" id="iamount">
                            <?php if (isset($balanceError)) {
                                echo $balanceError;
                            } ?>
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Enter IBAN</label>
                            <input class="form-control" type="number" name="acc_num" id="iacc_num">
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Enter SWIFT/BIC Code</label>
                            <input class="form-control" type="number" name="acc_num" id="iacc_num">
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Enter Bank Address</label>
                            <input class="form-control" type="number" name="acc_num" id="iacc_num">
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Enter Beneficiary Name</label>
                            <input class="form-control" type="number" name="acc_num" id="iacc_num">
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Description</label>
                            <input class="form-control" type="text" name="desc" id="desc">
                        </div>
                        <div class="m-2">
                            <label class="form-label" for="">Pin</label>
                            <input class="form-control" type="password" name="twofa" id="twofa">
                            <?php if (isset($descError)) {
                                echo $descError;
                            } ?>
                        </div>

                        <div>
                            <input class="form-control-plaintext" id="ishowName" name="acc_name" type="text" readonly>
                        </div>
                        <!-- <input type="hidden" name="id" id="id"> -->
                        <div>
                            <button class="btn btn-info" type="submit">Make Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="./inbank_tran.js"></script>
    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>