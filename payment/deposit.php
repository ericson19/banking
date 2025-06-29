<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Receive Payment - Account Details</title>
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {

            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .copy-btn {
            cursor: pointer;
        }

        .label {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .section-title {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 0.25rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .show {
            opacity: 0;
            background-color: green;
            color: white;
            font-weight: bolder;
            border-radius: 25rem;
            width: 8rem;
            box-shadow: 2px 2px 5px gray;
            padding-left: 0.5rem;
            transform: translateX(8rem);
            transition: transform 1.5s ease-in-out;
            position: relative;
            left: 8rem;
        }

        .show.carrd {
            opacity: 1;
            transform: translateX(0);
            background-color: green;
            color: white;
            font-weight: bolder;
            border-radius: 25rem;
            width: 8rem;
            box-shadow: 2px 2px 5px gray;
            padding-left: 0.5rem;
            position: relative;
            left: 0rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <span class="nav text-end" onclick="navbar()"><i class="fas fa-bars fa-2x p-2"></i></span>
    </div>

    <div class="container-fluid">
        <div class="row">
            <?php include('../view/sidebar.php'); ?>
            <div class="col-sm-9 col-md-9 col-lg-9 p-2">
                <div class="container-fluid py-5">
                    <div class="row justify-content-center">
                        <div class="">
                            <div class="card p-4">
                                <h4 class="text-center mb-4">Bank Account Details for Payment</h4>

                                <!-- Domestic Payment -->
                                <div class="mb-4">
                                    <div class="section-title">Domestic Payment</div>

                                    <div class="mb-3">
                                        <div class="label">Bank Name</div>
                                        <div class="fs-5 fw-semibold">Zenith Bank</div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="label">Account Number</div>
                                        <div class="input-group">
                                            <input id="accountNumber" type="text" class="form-control" value="1234567890" readonly>
                                            <button class="btn btn-outline-secondary copy-btn" onclick="copyText('accountNumber', 'show-accountNumber')">📋</button>
                                        </div>
                                        <div class="show" id="show-accountNumber"></div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="label">Account Name👔</div>
                                        <div class="fs-5 fw-semibold">CitySoft Technologies Ltd</div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="label">Payment Reference</div>
                                        <div class="fs-6">YourUsername_Invoice123</div>
                                    </div>
                                </div>

                                <!-- International Payment -->
                                <div>
                                    <div class="section-title">International Payment</div>

                                    <div class="mb-3">
                                        <div class="label">SWIFT/BIC Code</div>
                                        <div class="input-group">
                                            <input id="swiftCode" type="text" class="form-control" value="ZEIBNGLA" readonly>
                                            <button class="btn btn-outline-secondary copy-btn" onclick="copyText('swiftCode', 'show-swiftCode')">📋</button>
                                        </div>
                                        <div class="show" id="show-swiftCode"></div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="label">IBAN</div>
                                        <div class="input-group">
                                            <input id="iban" type="text" class="form-control" value="NG58ZEIB1234567890123456" readonly>
                                            <button class="btn btn-outline-secondary copy-btn" onclick="copyText('iban', 'show-iban')">📋</button>
                                        </div>
                                        <div class="show" id="show-iban"></div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="label">Bank Address</div>
                                        <div>Zenith Bank Plc, 87 Ajose Adeogun Street, Victoria Island, Lagos, Nigeria</div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="label">Beneficiary Name</div>
                                        <div class="fs-5 fw-semibold">CitySoft Technologies Ltd</div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <small class="text-muted">Double-check international details with your bank before sending payment.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // function copyText(inputId, btn) {
        //     const input = document.getElementById(inputId);
        //     input.select();
        //     input.setSelectionRange(0, 99999); // For mobile
        //     document.execCommand("copy");

        //     // Show tooltip feedback
        //     const originalTitle = btn.getAttribute("title");
        //     btn.setAttribute("title", "Copied!");
        //     const tooltip = bootstrap.Tooltip.getInstance(btn);
        //     tooltip.show();

        //     setTimeout(() => {
        //         btn.setAttribute("title", originalTitle);
        //         tooltip.hide();
        //     }, 1500);
        // }

        // Initialize tooltips
        // document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        //     new bootstrap.Tooltip(el);
        // });
    </script>
    <script>
        function copyText(inputId, bubble) {
            const number = document.getElementById(inputId);
            const show = document.getElementById(bubble);
            // const show = document.getElementById('show');
            number.select();
            number.setSelectionRange(0, 99999);
            document.execCommand('copy');

            //show copied
            show.innerHTML = "Copied";
            show.classList.add('carrd');
            setTimeout(() => {
                show.classList.remove('carrd');
            }, 1500)
        }
    </script>
    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>