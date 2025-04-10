<?php
  session_start();
  if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
    if($_SESSION['role'] === 'user'){
      header('Location: index.php');
      exit;
    }
    require_once ('process/dbh.php');
    if(isset($_POST['update']))
    {
    $id = generateSanitize($_SESSION['id']);
    $old = sha1($_POST['oldpass']);
    $new = sha1($_POST['newpass']);
    $sql = "SELECT alogin.password from alogin WHERE `id` = ?";
    $result = $conn->prepare($sql);
    $result->execute([$id]);
    $employee = $result->fetch();
    if($old == $employee['password']){
      $sql = "UPDATE `alogin` SET `password`= ? WHERE id = ? ";
      $result = $conn->prepare($sql);
      $result->execute([$new, $id]);
      echo ("<SCRIPT LANGUAGE='JavaScript'>
              window.alert('Password Updated')
              window.location.href='aloginwel.php?id=$id';</SCRIP>");   
            }
            else{
              echo ("<SCRIPT LANGUAGE='JavaScript'>
              window.alert('Failed to Update Password')
              window.location.href='javascript:history.go(-1)';
              </SCRIPT>");
            }
          }
          $id = (isset($_GET['id']) ? $_GET['id'] : '');
          $sql = "SELECT * from `alogin` WHERE id= ? ";
          $result = $conn->prepare($sql);
          $result->execute([$id]);
          if($result){
            while($res = $result->fetch()){
              $old = $res['password'];
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
            <li><a href="aloginwel.php?id=<?php echo $id?>">HOME</a></li>
            <li><a href="logout.php">Log Out</a></li>
          </ul>
          <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
      </div>
    </header>
    <div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
      <div class="wrapper wrapper--w680">
        <div class="card card-1">
          <div class="card-heading"></div>
          <div class="card-body">
            <h2 class="title">Update Password</h2>
            <form id = "registration" action="changeadminpass.php" method="POST">
              <div class="row row-space">
                <div class="col-2">
                    <div class="input-group">
                      <p>Old Password</p>
                        <input class="input--style-1" type="Password" name = "oldpass" required >
                    </div>
                </div>
                <div class="col-2">
                    <div class="input-group">
                      <p>New Password</p>
                        <input class="input--style-1" type="Password" name="newpass" required>
                    </div>
                </div>
              </div>
              <input type="hidden" name="id" id="textField" value="<?php echo $id;?>" required="required"><br><br>
              <div class="p-t-20">
                  <button class="btn btn--radius btn--green" type="submit" name="update">Submit</button>
              </div>
            </form>
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