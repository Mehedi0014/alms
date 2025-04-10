<?php
    session_start();
    require_once ('process/dbh.php');
    if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
        if($_SESSION['role'] === 'user'){
            header('Location: index.php');
            exit;
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
            <li><a href="aloginwel.php">HOME</a></li>
			<li><a href="addemp.php" class="active">Add Employee</a></li>
			<li><a href="empattend.php">Employee Attendance</a></li>
            <li><a href="lateattend.php">Late Attendance</a></li>
			<li><a href="empleave.php">Employee Leave</a></li>
            <li><a href="holiday.php">Holiday</a></li>
			<li><a href="setting.php">Setting</a></li>
			<li><a href="logout.php">Log Out</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
    </div>
  </header>
    <div class="container-fluid" style="height: 100%; min-height: 93vh;">
        <div class="table-responsive">
            <div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
                <div class="wrapper wrapper--w680">
                    <div class="card card-1">
                        <div class="card-heading"></div>
                        <div class="card-body">
                            <h2 class="title">Registration Info</h2>
                            <form action="process/addempprocess.php" method="POST" enctype="multipart/form-data">
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-1" type="text" placeholder="Employee ID" name="e_id" required="required" >
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <select class="form-control" name="city" required="required" id="">
                                            <option value="">-- Select office region --</option>
                                            <option value="bd">Bangladesh</option>
                                            <option value="ind">India</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group">
                                            <input class="input--style-1" type="text" placeholder="First Name" name="firstName" required="required">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-1" type="text" placeholder="Last Name" name="lastName" required="required">
                                    </div>
                                </div>
                            </div>
                            <div class="input-group">
                                <input class="input--style-1" type="email" placeholder="Email" name="email" required="required">
                            </div>
                            <p>Birthday</p>
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group">
                                        <input class="input--style-1" type="date" placeholder="BIRTHDATE" name="birthday" required="required">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <div class="rs-select2 js-select-simple select--no-search">
                                            <select name="gender">
                                                <option disabled="disabled" selected="selected">GENDER</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <div class="select-dropdown"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="input-group">
                                    <input class="input--style-1" type="number" placeholder="Contact Number" name="contact" required="required" >
                                </div>

                                <div class="input-group">
                                    <input class="input--style-1" type="text" placeholder="NID" name="nid">
                                </div>

                                <div class="input-group">
                                    <input class="input--style-1" type="text" placeholder="Address" name="address">
                                </div>

                                <div class="input-group">
                                    <input class="input--style-1" type="text" placeholder="Department" name="dept" required="required">
                                </div>

                                <div class="input-group">
                                    <input class="input--style-1" type="text" placeholder="Degree" name="degree" required="required">
                                </div>

                                <div class="input-group">
                                    <input class="input--style-1" type="number" placeholder="Salary" name="salary">
                                </div>

                                <div class="input-group">
                                    <input class="input--style-1" type="file" placeholder="file" name="file">
                                </div>

                                <div class="p-t-20">
                                    <button class="btn btn--radius btn--green" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
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