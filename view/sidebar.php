<div class="sidebar  col-sm-12 col-md-12 col-lg-3 p-2">
  <div style="border-bottom: 1px solid grey;" class="container-fuid">
    <img src="./images/partner-1.png" class="brand-logo" alt="">
    <h3>Banking</h3>
  </div>
  <?php
  echo "<div>{$_SESSION['firstName']} {$_SESSION['lastName']} " . ($_SESSION['kyc_status'] == 'verified' ? '✅' : '') . "</div>";

  ?>

  <h6>Available Balance</h6>
  <?php
  echo  "<div>{$_SESSION['email']} </div>";
  echo "<div>{$_SESSION['currency']}" . number_format($_SESSION['balance'], 2) . "</div>";

  ?>
  <br>
  <?php include('../payment/recieve.php'); ?>
  <div class="d-flex justify-content-between">
    <p>income</p>
    <p class="text-success"><?php echo $_SESSION['currency'] . number_format($total_receive, 2) ?></p>
  </div>
  <div class="d-flex justify-content-between">
    <p>Debits</p>
    <p class="text-danger"><?php echo $_SESSION['currency'] . number_format($total_sent, 2) ?></p>
  </div>
  <div class="d-flex justify-content-around m-2">
    <a href="../payment/deposit.php" class="btn btn-lg btn-primary">
      <span><i class="fas fa-money-bill-alt"></i> Deposit</span>
    </a>
    <a href="transfer" class="btn btn-lg btn-danger">
      <span><i class="fas fa-file-invoice-dollar"></i> Pay Bills</span>
    </a>
  </div>

  <h6>Menu</h6>


  <ul class="nav d-flex flex-column">
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../view/dashboard.php"><i class="fa fa-tachometer-alt px-2"></i>Dashboard</a></li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href=""><i class="fa fa-user px-2"></i>My Account</a></li>
    <!-- <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href=""><i class="fa fa-exchange-alt px-2"></i>Transfer</a></li> -->
    <li class="li-main nav-item  w-100 rounded-5 mt-1">
      <div class="dropdown">
        <button class="navnav btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Transfer</button>
        <ul class="dropdown-menu w-100">
          <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link dropdown-item" href="../payment/inbank.php">inBank Transfer</a></li>
          <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link dropdown-item" href="../payment/nigeria.php">Nigeria Bank Transfer</a></li>
          <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link dropdown-item" href="../payment/wire.php">Wire(International) Transfer</a></li>

        </ul>
      </div>
    </li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../payment/deposit.php"><i class="fa fa-wallet px-2"></i>Deposit</a></li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../view/confirmkyc.php"><i class="fas fa-id-card px-2"></i>KYC Status</a></li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../view/investment.php"><i class="fas fa-piggy-bank px-2"></i>Investments </a></li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../view/card.php"><i class="fas fa-credit-card px-2"></i>visual Card </a></li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../view/currency.php"><i class="fas fa-exchange-alt PX-2"></i>Change Currency</a></li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../view/loan.php"><i class="fas fa-hand-holding-usd px-2"></i>loan/Credit Financing </a></li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../view/activities.php"><i class="fas fa-cog px-2"></i>Login Activities</a></li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../view/support.php"><i class="fas fa-life-ring px-2"></i>Support</a></li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../view/settings.php"><i class="fas fa-life-ring px-2"></i>Account settings</a></li>
    <li class="li-main nav-item  w-100 rounded-5 mt-1"><a class="navnav nav-link" href="../auth/logout.php"><i class="fas fa-sign-out-alt PX-2"></i>Logout</a></li>



  </ul>

</div>