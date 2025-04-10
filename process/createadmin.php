<?php
session_start();
ob_start();
require_once ('dbh.php');

$email = generateSanitize($_POST['email']);

$sqla = "SELECT COUNT(`email`) AS `total` FROM `alogin` WHERE `email` = ?";

$res = $conn->prepare($sqla);

$res->execute([$email]);

$p = $res->fetch();

if($p['total'] === "0"){

    $params = array(

        'email' => $email ,

        'password' => sha1('1234')  

    );

    $sql = "INSERT INTO `alogin`(`email`, `password`) VALUES (:email, :password)";

    $result = $conn->prepare($sql);

    $result->execute($params);

    ob_end_flush();

    echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Succesfully Registered')

    window.location.href='../aloginwel.php';

    </SCRIPT>");

    exit();

}else{

    ob_end_flush();

    echo ("<SCRIPT LANGUAGE='JavaScript'>

    window.alert('Failed to Register. Credential matched or manipulated.')

    window.location.href='javascript:history.go(-1)';

    </SCRIPT>");

    exit();

}

?>