<?php
session_start();
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
    <div>
        <span class="nav text-end" onclick="navbar()"><i class="fas fa-bars fa-2x p-2"></i></span>
    </div>
    <div class="container-fluid">
        <div class="row mt-2">
            <?php include('sidebar.php'); ?>

            <!-- main page -->
            <div class="col-sm-9 col-md-9 col-lg-9 p-2">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <h4>Send Mail To Company</h4>
                                <div class="mb-3">
                                    <label class="form-label" for="">Email Address</label>
                                    <input class="form-control" type="text" name="email" id="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="">Subject</label>
                                    <input class="form-control" type="text" name="email" id="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="">Enter Your Text Here</label>
                                    <textarea class="form-control" name="" id=""></textarea>
                                </div>
                                <button type="submit">Send Mail</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>