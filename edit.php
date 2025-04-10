<?php
session_start();
require_once ('process/dbh.php');
if(isset($_SESSION['email']) && (isset($_SESSION['company_name']) && $_SESSION['company_name'] === 'ALMS') && (isset($_SESSION['role'])) ){
    require_once ('process/dbh.php');
    $sql = "SELECT * FROM `employee`";
    $result = $conn->prepare($sql);
$result->execute();
if(isset($_POST['update'])){
    $id = generateSanitize($_POST['id']);
    $e_id = generateSanitize($_POST['e_id']);
    $city = generateSanitize($_POST['city']);
	$firstname = generateSanitize($_POST['firstName']);
	$lastname = generateSanitize($_POST['lastName']);
	$email = generateSanitize($_POST['email']);
	$birthday = generateSanitize($_POST['birthday']);
	$contact = generateSanitize($_POST['contact']);
	$address = generateSanitize($_POST['address']);
	$gender = generateSanitize($_POST['gender']);
	$nid = generateSanitize($_POST['nid']);
	$dept = generateSanitize($_POST['dept']);
	$degree = generateSanitize($_POST['degree']);
    $salary = generateSanitize($_POST['salary']);
    $params = array(
        'e_id' => $e_id,
        'city' => $city,
        'firstName' => $firstname,
        'lastName' => $lastname,
        'email' => $email,
        'birthday' => $birthday,
        'gender' => $gender,
        'contact' => $contact,
        'nid' => $nid,
        'address' => $address,
        'dept' => $dept,
        'degree' => $degree,
        'salary' => $salary,
        'id' => $id
    );
    $result = $conn->prepare("UPDATE `employee` SET `e_id`= :e_id,`city`= :city,`firstName`= :firstName,`lastName`=:lastName,`email`=:email,`birthday`=:birthday,`gender`=:gender,`contact`=:contact,`nid`=:nid,`address`=:address,`dept`=:dept,`degree`=:degree, `salary`=:salary WHERE id= :id");
    if($result->execute($params)){
        echo ("<SCRIPT LANGUAGE='JavaScript'>
        window.alert('Succesfully Updated')
        window.location.href='aloginwel.php';
        </SCRIPT>");
    }else{
        echo ("<SCRIPT LANGUAGE='JavaScript'>
        window.alert('Failed to update.')
        window.location.href='aloginwel.php';
        </SCRIPT>");
    }
}
$id = (isset($_GET['id']) ? $_GET['id'] : '');
$sql = "SELECT * from `employee` WHERE `id` = ?";
$result = $conn->prepare($sql);
$result->execute([$id]);
if($result){
    while($res = $result->fetch()){
        $e_id = $res['e_id'];
        $city = $res['city'];
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
                    <li><a href="aloginwel.php">HOME</a></li>
                    <li><a href="addemp.php">Add Employee</a></li>
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
                    <h2 class="title">Update Employee Info</h2>
                    <form id = "registration" action="edit.php" method="POST">
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-1" type="text" name="e_id" value="<?php echo $e_id;?>" >
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-1" type="text" name="city" value="<?php echo $city;?>" Readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-1" type="text" name="firstName" value="<?php echo $firstname;?>" >
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-1" type="text" name="lastName" value="<?php echo $lastname;?>">
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <input class="input--style-1" type="email"  name="email" value="<?php echo $email;?>">
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-1" type="text" name="birthday" value="<?php echo $birthday;?>">
                                
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-1" type="text" name="gender" value="<?php echo $gender;?>">
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <input class="input--style-1" type="number" name="contact" value="<?php echo $contact;?>">
                        </div>

                        <div class="input-group">
                            <input class="input--style-1" type="number" name="nid" value="<?php echo $nid;?>">
                        </div>
                        <div class="input-group">
                            <input class="input--style-1" type="text"  name="address" value="<?php echo $address;?>">
                        </div>

                        <div class="input-group">
                            <input class="input--style-1" type="text" name="dept" value="<?php echo $dept;?>">
                        </div>

                        <div class="input-group">
                            <input class="input--style-1" type="text" name="degree" value="<?php echo $degree;?>">
                        </div>
                        <div class="input-group">
                            <input class="input--style-1" type="number" name="salary" value="<?php echo $salary;?>">
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