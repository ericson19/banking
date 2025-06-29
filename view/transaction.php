<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Transaction History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .credit {
            color: green;
        }

        .debit {
            color: red;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-5">
        <h3 class="mb-4">Transaction History</h3>
        <div class="container mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search transactions...">
        </div>

        <div class="row g-3">

            <!-- Transaction Card -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6><strong>TRX ID:</strong> TRX123456</h6>
                                <p class="mb-1 text-muted">2025-05-08 14:35</p>
                                <p class="mb-0">Payment for groceries</p>
                            </div>
                            <div class="text-end">
                                <p class="mb-1"><strong class="debit">- ₦5,000.00</strong></p>
                                <span class="badge bg-danger">Debit</span><br>
                                <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#detailModal">View</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Another transaction -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6><strong>TRX ID:</strong> TRX789101</h6>
                                <p class="mb-1 text-muted">2025-05-07 10:12</p>
                                <p class="mb-0">Salary payment</p>
                            </div>
                            <div class="text-end">
                                <p class="mb-1"><strong class="credit">+ ₦120,000.00</strong></p>
                                <span class="badge bg-success">Credit</span><br>
                                <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#detailModal">View</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add more transactions here... -->

        </div>
    </div>

    <!-- Modal for transaction details -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Transaction ID:</strong> TRX123456</p>
                    <p><strong>Date:</strong> 2025-05-08 14:35</p>
                    <p><strong>Description:</strong> Payment for groceries</p>
                    <p><strong>Amount:</strong> - ₦5,000.00</p>
                    <p><strong>Status:</strong> Completed</p>
                    <p><strong>Type:</strong> Debit</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>