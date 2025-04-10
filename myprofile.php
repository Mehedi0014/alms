<?php
session_start();
if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
  if($_SESSION['role'] === 'admin'){
		header('Location: index.php');
		exit;
	}
  require_once ('process/dbh.php');
$id = (isset($_SESSION['id']) ? $_SESSION['id'] : '');
$sql = "SELECT * from `employee` WHERE id = ?";
$result = $conn->prepare($sql);
$result->execute([$id]);

if($result){
  while($res = $result->fetch()){
    $firstname = $res['firstName'];
    $lastname = $res['lastName'];
    $email = $res['email'];
    $contact = $res['contact'];
    $address = $res['address'];
    $gender = $res['gender'];
    $birthday = $res['birthday'];
    $nid = $res['nid'];
    $dept = $res['dept'];
    $degree = $res['degree'];
    $pic = $res['pic'];
    $salary = $res['salary'];
  }
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
          <li><a href="eloginwel.php?id=<?php echo $id?>">HOME</a></li>
          <li><a href="myprofile.php?id=<?php echo $id?>"  class="active">My Profile</a></li>
          <li><a href="attendance.php?id=<?php echo $id?>">Attendance</a></li>
          <li><a href="applyleave.php?id=<?php echo $id?>">Apply Leave</a></li>
          <li><a href="logout.php">Log Out</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
    </div>
  </header>	
  <div class="divider"></div>
  <div class="page-wrapper font-robo" style="margin: 150px 0 60px 0;">
    <div class="wrapper wrapper--w680">
      <div class="card card-1" style="border-radius:20px;">
        <div class="card-heading"></div>
        <div class="card-body">
          <h2 class="title">My Info</h2>
          <form>
            <div class="input-group">
              <img src="process/<?php echo $pic;?>" height = 150px width = 150px>
            </div>
              <div class="row row-space">
                <div class="col-2">
                  <div class="input-group">
                    <p>First Name</p>
                    <input class="input--style-1" type="text" name="firstName" value="<?php echo $firstname;?>" readonly >
                  </div>
                </div>
                <div class="col-2">
                  <div class="input-group">
                    <p>Last Name</p>
                    <input class="input--style-1" type="text" name="lastName" value="<?php echo $lastname;?>" readonly>
                  </div>
                </div>
              </div>
              <div class="input-group">
                <p>Email</p>
                <input class="input--style-1" type="email"  name="email" value="<?php echo $email;?>" readonly>
              </div>
              <div class="row row-space">
                <div class="col-2">
                  <div class="input-group">
                    <p>Date of Birth</p>
                    <input class="input--style-1" type="text" name="birthday" value="<?php echo $birthday;?>" readonly>        
                  </div>
                </div>
                <div class="col-2">
                <div class="input-group">
                  <p>Gender</p>
                  <input class="input--style-1" type="text" name="gender" value="<?php echo $gender;?>" readonly>
                </div>
              </div>
            </div>
            <div class="input-group">
              <p>Contact Number</p>
              <input class="input--style-1" type="number" name="contact" value="<?php echo $contact;?>" readonly>
            </div>
            <div class="input-group">
              <p>NID</p>
              <input class="input--style-1" type="number" name="nid" value="<?php echo $nid;?>" readonly>
            </div>  
            <div class="input-group">
              <p>Address</p>
              <input class="input--style-1" type="text"  name="address" value="<?php echo $address;?>" readonly>
            </div>
            <div class="input-group">
              <p>Department</p>
              <input class="input--style-1" type="text" name="dept" value="<?php echo $dept;?>" readonly>
            </div>
            <div class="input-group">
              <p>Degree</p>
              <input class="input--style-1" type="text" name="degree" value="<?php echo $degree;?>" readonly>
            </div>
            <div class="input-group">
              <p>Total Salary</p>
              <input class="input--style-1" type="text" name="degree" value="<?php echo $salary;?>" readonly>
            </div>
              <input type="hidden" name="id" id="textField" value="<?php echo $id;?>" required="required">
            </form>
            <div class="p-t-20">
            <button class="btn btn--radius btn--green" onclick="window.location.href = 'changepassemp.php?id=<?php echo $id?>';">Change Password</button>
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