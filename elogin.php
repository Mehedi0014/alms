<?php
	session_start();
	if(isset($_SESSION['email']) && isset($_SESSION['company_name']) && isset($_SESSION['role']) && $_SESSION['company_name'] === 'ALMS'){
    header("location: eloginwel.php");
		exit;
	}
	if(isset($_GET['msg'])){
    ?>
		<script>
			alert("<?=$_GET['msg']?>");
		</script>
		<?php
	}
  include "./header.php";
?>
  <header id="header" class="fixed-top">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <h1 class="logo"><a href="index.php"><img src="assets\logo\Disseminarebd_logo.png"></a></h1>
        <h1 class="logo"><a href="index.php"><span>AL</span>MS</a></h1>
        <h1 class="logo"><a href="index.php"><img src="assets\logo\Connecting_Dot_logo.png"></a></h1>
      </div>
      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a href="index.php" >Home</a></li>
          <li class="dropdown"><a href="#" class="active"><span>LOGIN</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="elogin.php" class="active">Employee LOGIN</a></li>
              <li><a href="alogin.php">Admin Login</a></li>
            </ul>
          </li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
    </div>
  </header>	
  <div style="padding-top: 100px; width: 100%;">
    <div class="container-fluid"  style="height: 100%; min-height: 82.7vh;">
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <h2 class="text-center text-dark mt-5">Employee Login</h2>
          <div class="card my-5">
            <form action="process/eprocess.php" method="POST" class="card-body cardbody-color p-lg-5">
              <div class="text-center">
                <img src="assets/avatar.png" class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3"
                  width="200px" alt="profile">
              </div>
              <div class="mb-3">
                <input type="text" name="mailuid" class="form-control" id="Username" aria-describedby="emailHelp" placeholder="Enter Email Address" required="required">
              </div>
              <div class="mb-3">
                <input type="password" name="pwd" class="form-control" id="password" placeholder="Enter Password" required="required">
              </div>
              <div class="text-center"><button type="submit" class="btn btn-primary" value="Login">Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
    include "./footer.php";
  ?>
</html>