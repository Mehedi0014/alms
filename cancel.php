<?php
    session_start();
    if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
        if($_SESSION['role'] === 'user'){
            header('Location: index.php');
            exit;
        }
    $id = $_GET['id'];
    $token = $_GET['token'];
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
					<li><a href="aloginwel.php">HOME</a></li>
					<li><a href="addemp.php">Add Employee</a></li>
					<li><a href="empattend.php">Employee Attendance</a></li>
					<li><a href="empleave.php">Employee Leave</a></li>
					<li><a href="holiday.php">Holiday</a></li>
					<li><a href="setting.php">Setting</a></li>
					<li><a href="logout.php">Log Out</a></li>
				</ul>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav>
		</div>
	</header>
    <div style="padding-top: 100px; width: 100%;">
        <div class="container" style="height: 100%; min-height: 82.7vh; text-align: center; margin: 0 auto;">
            <div class="card" style="width: 100%; max-width: 30rem; margin: 0 auto;">
                <div class="card-body">
                    <div class="card-body-1">
                        <h2 class="title">Comments</h2>
                        <form id = "registration" action="process/cmnt.php" method="POST">
                        <div class="input-group">
                            <label for="" class="animation">Enter Comment</label>
                            <input class="input--style-1" type="text"  name="cmnt">
                            <input class="input--style-1" type="hidden"  name="status" value="Cancelled">
                            <input class="input--style-1" type="hidden"  name="id" value="<?php echo $id;?>">
                            <input class="input--style-1" type="hidden"  name="token" value="<?php echo $token;?>">
                        </div>
                        <div class="p-t-20">
                            <button class="btn btn--radius btn--green" type="submit" name="update">Submit</button>
                        </div>
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
<?php 
	}else{
		header('location: logout.php');
		exit;
	}
?>