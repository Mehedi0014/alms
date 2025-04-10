<?php
    session_start();
    ob_start();
    require_once('dbh.php');
    $email = generateSanitize($_POST['mailuid']);
    if($email === false){
        ob_end_flush();
        echo ("<SCRIPT LANGUAGE='JavaScript'>
        window.alert('Invalid Email')
        window.location.href='../elogin.php';
        </SCRIPT>");
        exit;
    }
    $password = sha1($_POST['pwd']);
    $params = array(
        'password' => $password,
        'email' => $email
    );
    $sql = "SELECT * from `employee` WHERE email = :email AND password = :password";
    $result = $conn->prepare($sql);
    $result->execute($params);
    if($result->rowCount() === 1){
        $row_res = $result->fetch();
        $id = $row_res['id'];
        $_SESSION['email'] = $email;
        $_SESSION['id'] = $id;
        $_SESSION['company_name'] = 'ALMS';
        $_SESSION['role'] = 'user';
        if($password === sha1("1234") ){
            ob_end_flush();
            header("Location: ../changepassemp.php");
            exit;
            
        }else{
            ob_end_flush();
            header("Location: ../eloginwel.php");
            exit;
        }
    }else{
        ob_end_flush();
        echo ("<SCRIPT LANGUAGE='JavaScript'>
        window.alert('Invalid Email And Password')
        window.location.href='../elogin.php';
        </SCRIPT>");
        exit;
    }
?>