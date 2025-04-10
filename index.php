<?php
	session_start();
	if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS' && isset($_SESSION['role'])) ){

    $role = $_SESSION['role'];
		if($role === 'admin'){

      header("location: aloginwel.php");
		}elseif ($role === 'user') {

      header("location: eloginwel.php");
		}else{

      echo "problem";
		}

    
		exit;
	}

  include "./header.php";

?>

  <style>

    .carousel-caption{

      bottom: 10rem !important;

      background: #0000007a;

      border: 5px solid #ededed;

      border-radius: 3px;

    }

  </style>

  <header id="header" class="fixed-top">

    <div class="container d-flex justify-content-between align-items-center">

      <div class="d-flex align-items-center">

        <h1 class="logo"><a href="index.php"><img src="assets\logo\Disseminarebd_logo.png"></a></h1>

        <h1 class="logo"><a href="index.php"><span>AL</span>MS</a></h1>

        <h1 class="logo"><a href="index.php"><img src="assets\logo\Connecting_Dot_logo.png"></a></h1>

      </div>

      <nav id="navbar" class="navbar order-last order-lg-0">

        <ul>

          <li><a href="index.php" class="active">Home</a></li>

          <li class="dropdown"><a href="#"><span>LOGIN</span> <i class="bi bi-chevron-down"></i></a>

            <ul>

              <li><a href="elogin.php">EMPLOYEE LOGIN</a></li>

              <li><a href="alogin.php">ADMIN LOGIN</a></li>

            </ul>

          </li>

        </ul>

        <i class="bi bi-list mobile-nav-toggle"></i>

      </nav>

    </div>

</header>

  <div class="container-fluid g-0"  style="height: 70%; min-height: 60.7vh;">

    <section id="hero">

      <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-indicators">

          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>

          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>

        </div>

        <div class="carousel-inner">

          <div class="carousel-item active" style="background-size: cover;">

            <img src="assets/img/index_bg.jpeg" class="d-block w-100" alt="...">

            <div class="carousel-caption d-none d-md-block">

              <h1 style="text-align: center;">Welcome to <span>Attendance And Leave Management System</span></h1>

            </div>

          </div>

          <div class="carousel-item" style="background-size: cover;">

            <img src="assets/img/index_bg2.jpeg" class="d-block w-100" alt="...">

            <!--<div class="carousel-caption d-none d-md-block">-->

              <!--<h1 style="text-align: center;">Welcome to <span>Attendance And Leave Management System</span></h1>-->

            <!--</div>-->

          </div>

        </div>

      </div>

    </section>

  </div>

  <?php

    include "./footer.php";

  ?>

</html>