<?php
session_start();
$user_id = $_SESSION['']
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
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="" method="post">
                                        <h4>Update Account</h4>
                                        <div class="mb-3">
                                            <label class="form-label" for="">ACCOUNT NAME</label>
                                            <input class="form-control" type="text" name="" id="">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Email</label>
                                            <input class="form-control" type="text" name="" id="">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">State Of Origin</label>
                                            <input class="form-control" type="text" name="" id="">
                                        </div>
                                        <div class="input-group mb-3">
                                            <label class="input-group-text" for="">City</label>
                                            <input class="form-control" type="text" name="" id="">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Occupation</label>
                                            <select name="occupation" class="form-select">
                                                <option value="employer">Employer </option>
                                                <option value="self-employed">Self Employed</option>
                                                <option value="not-employed">Not Employed</option>
                                                <option value="graduate">Graduate</option>
                                                <option value="under-graduate">Under graduate</option>
                                            </select>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input class="form-control" type="file" name="" id="">
                                            <label class="input-group-text" for="">Change Passport Photograph</label>
                                        </div>

                                        <button class="btn btn-info" type="submit">Save Changes</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <form action="" method="post">
                                        <h4>Reset Password</h4>
                                        <div class="mb-3">
                                            <label class="form-label" for=""> Old Password</label>
                                            <input class="form-control" type="text" name="oldPassword" id="">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for=""> New Password</label>
                                            <input class="form-control" type="text" name="newPassword" id="">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Confirm Password</label>
                                            <input class="form-control" type="text" name="conPassword" id="">
                                        </div>
                                        <button class="btn btn-info" type="submit">Save Changes</button>
                                    </form>
                                </div>
                            </div>
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