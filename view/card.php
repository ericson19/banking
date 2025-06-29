<?php
session_start();
include('../database/bankdb.php');
$user_id = $_SESSION['User_id'];
$currency = $_SESSION['currency'];
$sql = "SELECT * FROM card WHERE user_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $card_name = $row['name'];
    $card_email = $row['email'];
    $card_status = $row['status'];
    $card_number = $row['card_number'];
    $cvv = $row['cvv'];
    $expiry = $row['expiry'];
    $card_type = $row['card_type'];
    // $currency = $row['currency'];
}
$first = isset($card_number) ? substr($card_number, 0, 4) : "";
$second = isset($card_number) ?  substr($card_number, 4, 4) : "";
$third = isset($card_number) ? substr($card_number, 8, 4) : "";
$fourth = isset($card_number) ? substr($card_number, 12, 4) : "";
if (isset($currency)) {
    if ($currency == "NGN") {
        $card_currency = "NAIRA CARD";
    } elseif ($currency === "USD") {
        $card_currency = "DOLLAR CARD";
    } elseif ($currency === "EUR") {
        $card_currency = "EURO CARD";
    } elseif ($currency === "GBP") {
        $card_currency = "BRITISH CARD";
    } elseif ($currency === "CNY") {
        $card_currency = "CHINESE CARD";
    } else {
        $card_currency = "";
    }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Request Virtual Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://kit.fontawesome.com/a2e4e6fd4b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        .card-option {
            height: 250px;
            width: 350px;
            background-color: blue;
            font-size: 1.5rem;
            margin: auto;
        }

        .img {
            opacity: 0;

            background-image: url("../images/visa.png");
            color: wheat;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            font-size: 1.2em;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-weight: bold;
            width: 0vw;
            max-width: 400px;
            aspect-ratio: 16 / 10;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            padding: 10px;
            transform: scale(1.5);
            transition: transform 0.5s ease-in-out;

        }

        .img.active {
            width: 90vw;
            opacity: 1;
            transform: scale(1);
        }

        /* Make all text position absolute inside .img */
        .img p {
            position: absolute;
            margin: 0;
        }

        /* Position each field */
        .name {
            top: 80%;
            left: 5%;
            font-size: 1em;
        }

        .expiry {
            top: 70%;
            right: 40%;
            font-size: 1em;
        }

        .cvv {
            top: 40%;
            right: 20%;
            font-size: 1em;
        }

        .number {
            bottom: 30%;
            left: 15%;
            font-size: 1.1em;
            letter-spacing: 2px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="ml-auto">
            <span class="nav text-end" onclick="navbar()"><i class="fas fa-bars fa-2x p-2"></i></span>

        </div>
        <div class="row mt-2">
            <?php include('sidebar.php') ?>

            <div class="col-sm-9 col-md-9 col-lg-9">

                <div class="container">
                    <div class="row ">
                        <div class="col-md-6 col-12 mt-5">
                            <div>
                                <div class="img">
                                    <p class="name"><?php echo $card_name ?></p>
                                    <p class="expiry"><?php echo $expiry ?></p>
                                    <p class="cvv">cvv: <span><?php echo $cvv ?></span></p>
                                    <p class="number"><span><?php echo $first ?> </span><span><?php echo $second ?> </span><span><?php echo $third ?> </span><span><?php echo $fourth ?></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 text-center mt-5">
                            <div class=" card rounded shadow p-5">
                                <?php echo "<h4 class='text-center text-danger mt-4'>" . $card_currency . "</h4>" ?>
                                <h5>GET A VIRTUAL NUMBER</h5>
                                <button class="btn btn-primary p-2 text-white" id="showCard" type="button" onclick="showCard()">Show Card Details</button>
                                <br>
                                <a class="btn btn-primary p-2 text-white" href="../view/requestCard.php">Get A New Card</a>
                                <br>
                                <a class="delete-btn btn btn-primary p-2 mt-2 text-white" href="../view/deactivate.php?id=<?php echo $user_id ?>">Deactivate card here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showCard() {
            let img = document.querySelector('.img');
            const showCard = document.querySelector('#showCard');
            // img.classList.toggle('active');
            if (img.classList.contains('active')) {
                img.classList.remove('active');
                showCard.textContent = 'Show Card Details';
            } else {
                img.classList.add('active');
                showCard.textContent = 'Hide Card Details';
            }
        }
        let img = document.querySelector('.img');
        const card = "<?php echo $card_type ?>";
        console.log(card);
        if (card == "visa") {
            img.style.backgroundImage = "url('../images/visa.png')";
        } else if (card == "mastercard") {
            img.style.backgroundImage = "url('../images/mastercard.png')";
        } else {
            img.style.backgroundImage = "url('../images/mastercard1.png')";
        }

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); // prevent link click
                const id = this.getAttribute('data-id');
                const link = this.getAttribute('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to undo this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'YES, DEACTIVATE CARD!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = link; // proceed with delete
                    }
                });
            });
        });
    </script>
</body>

</html>