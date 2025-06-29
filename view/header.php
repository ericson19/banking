<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/headstyle.css">
    <title>Header</title>
</head>

<body>
    <nav class="navhead">
        <div class="d-flex align-items-center">
            <img class="logo" src="../images/partner-1.png" alt="">
            <h1>Site name</h1>
        </div>
        <div class="navlist">
            <ul class="nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="">Home</a></li>
                <li class="dropdown">
                    <h3 class="more" href="">More <i class="fa-solid fa-caret-down"></i></h3>
                    <div class="dropdown-content">
                        <ul class="nav d-flex flex-column">
                            <li class="nav-item"><a class="nav-link" href="">Get a Virtual Card</a></li>
                            <li class="nav-item"><a class="nav-link" href="">Get Loan</a></li>
                            <li class="nav-item"><a class="nav-link" href="">Complete international transaction</a></li>
                        </ul>
                    </div>
                </li>
                <li class="create nav-item"><a class="nav-link" href="">Create An Account</a></li>
                <li class="nav-item"><a class="nav-link" href="">Sign in</a></li>

            </ul>
        </div>
        <button class="navbtn btn" id="navbtn" onClick="navFunction()"><i class="fa-solid fa-bars fa-2x mt-1"></i></button>

    </nav>
    <div class="toggle nav">
        <ul class=" mr-auto">
            <li class="nav-item"><a class="nav-link" href="">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="">Get a Virtual Card</a></li>
            <li class="nav-item"><a class="nav-link" href="">Get Loan</a></li>
            <li class="nav-item"><a class="nav-link" href="">Complete international transaction</a></li>
            <li class=" nav-item"><a class="create nav-link" href="">Create An Account</a></li>
            <li class="nav-item"><a class="nav-link" href="">Sign in</a></li>

        </ul>
    </div>
    <script>
        //DROPDOWN NAVBAR
        let dropdown = document.querySelector(".dropdown-content");
        let more = document.querySelector(".dropdown");

        more.addEventListener("click", function() {
            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "block";
            } else {
                dropdown.style.display = "none";
            }

        });
        let toggle = document.querySelector(".toggle");
        let navbtn = document.querySelector("#navbtn");


        function navFunction() {
            event.stopPropagation();
            if (toggle.style.display === "none" || toggle.style.display === "") {
                toggle.style.display = "block";
                document.body.style.backgroundColor = "grey";


            } else {
                toggle.style.display = "none";
                document.body.style.backgroundColor = "";
            }
        }
        // document.body.addEventListener("click", function (event) {
        //   if (!toggle.contains(event.target) && event.target !== navbtn) {
        //     toggle.style.display = "none";
        //     document.body.style.backgroundColor = "";
        //   }
        // });
        document.body.addEventListener("click", function() {
            toggle.style.display = "none";
        });
    </script>
</body>

</html>