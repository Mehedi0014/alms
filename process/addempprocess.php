<?php
session_start();
ob_start();
require_once ('dbh.php');
$e_id = generateSanitize($_POST['e_id']);

$city = generateSanitize($_POST['city']);

$firstname = generateSanitize($_POST['firstName']);

$lastName = generateSanitize($_POST['lastName']);

$email = generateSanitize($_POST['email']);

$contact = generateSanitize($_POST['contact']);

$address = generateSanitize($_POST['address']);

$gender = generateSanitize($_POST['gender']);
$nid = generateSanitize($_POST['nid']);
$dept = generateSanitize($_POST['dept']);
$degree = generateSanitize($_POST['degree']);
$salary = generateSanitize($_POST['salary']);
$birthday =generateSanitize($_POST['birthday']);
$files = $_FILES['file'];
$filename = $files['name'];
$filrerror = $files['error'];
$filetemp = $files['tmp_name'];
$fileext = explode('.', $filename);
$filecheck = strtolower(end($fileext));
$fileextstored = array('png' , 'jpg' , 'jpeg');
$sqla = "SELECT COUNT(`email`) as count FROM `employee` WHERE `email` = ?";
$res = $conn->prepare($sqla);
$res->execute([$email]);
$p = $res->fetch();
//var_dump($p);
$e = $p[0];
if($e === "0"){
    $destinationfile = 'images/'.$filename;
    move_uploaded_file($filetemp, $destinationfile);
    $params = array(
        'employee_id' => $e_id ,
        'city' => $city ,
        'firstName' => $firstname ,
        'lastName' => $lastName ,
        'email' => $email ,
        'password' => sha1('1234') ,
        'birthday' => $birthday ,
        'gender' => $gender ,
        'contact' => $contact ,
        'nid' => $nid ,
        'address' => $address ,
        'dept' => $dept ,
        'degree' => $degree ,
        'destFile' => $destinationfile ,
        'salary' => ($salary === "" ? 0 : (int) $salary),
        'status' => '0'
    );
    
    // var_dump ($params);
    // $sql = "INSERT INTO `employee` (`e_id`, `city`, `firstName`, `lastName`, `email`, `password`, `birthday`, `gender`, `contact`, `nid`, `address`, `dept`, `degree`, `pic`, `salary`, `status`) VALUES (:employee_id, :city, :firstName, :lastName, :email, :password, :birthday, :gender, :contact, :nid, :address, :dept, :degree, :destFile, :salary, :status)";
    $sql = "INSERT INTO employee (`e_id`, `city`, `firstName`, `lastName`, `email`, `password`, `birthday`, `gender`, `contact`, `nid`, `address`, `dept`, `degree`, `pic`, `salary`, `status`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $result = $conn->prepare($sql);
    $ss = $result->execute($params);
    // var_dump($result);
    ob_end_flush();
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('User Registration successfully.')
    window.location.href='./../aloginwel.php';
    </SCRIPT>");
    exit();    
}else if($e === "1"){
    ob_end_flush();
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('User already Exist.')
    window.location.href='./../aloginwel.php';
    </SCRIPT>");
    exit();
}else{
    ob_end_flush();
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('User Registration failed.')
    window.location.href='./../aloginwel.php';
    </SCRIPT>");
    exit();
}
?>