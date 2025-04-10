<?php    
    session_start();    
    ob_start();    
    include("dbh.php");    
    $cmnt = generateSanitize($_POST['cmnt']);    
    $status = generateSanitize($_POST['status']);    
    $id = generateSanitize($_POST['id']);
    $token = generateSanitize($_POST['token']);
    $query = "SELECT * FROM `employee` where id = ?";
    $result = $conn->prepare($query);
    $result->execute([$id]);
    $result_row = $result->fetch();
    $e_id = generateSanitize($result_row['e_id']);
    $firstname = generateSanitize($result_row['firstName']);
    $lastName = generateSanitize($result_row['lastName']);
    $emailto = generateSanitize($result_row['email']);


    $params = array(
        'cmnt' => $cmnt ,
        'status' => $status ,
        'id' => $id ,
        'token' => $token
    );
    $a_id = generateSanitize($_SESSION['id']);
    $sqla="SELECT `email` FROM `alogin` WHERE `id` =  ?";
    $resulta = $conn->prepare($sqla);
    $resulta->execute([$a_id]);
    $resulta_row = $resulta->fetch();
    $emailfrm = generateSanitize($resulta_row['email']);
    
    $sql = "UPDATE `employee_leave` SET `cmnt`= :cmnt, `status`= :status WHERE id = :id and token = :token";
    $result = $conn->prepare($sql);
    $result->execute($params);
    
    
    
    if(sendmail($emailfrm, $emailto, $status, $firstname, $lastName, $cmnt)){
        ob_end_flush();
        header("Location:./../empleave.php?msg=Updated and mailed.");
        exit();
    }else{
        ob_end_flush();
        header("Location:./../empleave.php?msg=Updated and failed to send mail.");
        exit();
    }


    function sendmail($emailfrm1, $emailto1, $status1, $firstname1, $lastName1, $cmnt1){
        $from = $emailfrm1;
        $to = $emailto1;
        $subject = $status1." Leave";
        $headers .= 'From: '.$from."\r\n" . 'X-Mailer: PHP/' . phpversion();
        $message = "Dear ".$firstname1." ".$lastName1.",\r\n ".$cmnt1."\r\n Yours Sincerely,\r\n .$from.";
        $result = @mail($to, $subject, $message, $headers);
        return $result;
    }

?>