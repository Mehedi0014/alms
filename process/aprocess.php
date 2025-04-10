<?php
    session_start();
    ob_start();
    require_once ('dbh.php');
        $email = generateSanitize($_POST['mailuid'], "email");
        if($email === false){
            ob_end_flush();
            echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Invalid Email')
            window.location.href='../alogin.php';
            </SCRIPT>");
            exit;
        }
        $password = sha1($_POST['pwd']);
        $params = array(
            'password' => $password,
            'email' => $email
        );
        $sql = "SELECT * from `alogin` WHERE email = :email AND password = :password";
        $result = $conn->prepare($sql);
        $result->execute($params);
        if($result->rowCount() == 1){
            $row_res = $result->fetch();
            $_SESSION['email'] = $email;
            $_SESSION['id'] = $row_res['id'];
            $_SESSION['company_name'] = 'ALMS';
            $_SESSION['role'] = 'admin';
            if($password === sha1("1234") ){
                ob_end_flush();
                header("Location: ../changeadminpass.php");
                exit;
                
            }else{
                ob_end_flush();
                header("Location: ../aloginwel.php");
                exit;
            }
        }else{
            ob_end_flush();
            echo ("<SCRIPT LANGUAGE='JavaScript'>
            window.alert('Invalid Email And Password')
            window.location.href='../alogin.php';
            </SCRIPT>");
            exit;
        }
?>