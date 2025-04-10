<?php
session_start();
ob_start();
require_once ('dbh.php');
// Employee table

$id = $_POST['user_id'];
$query = "SELECT * FROM `employee` where id = ?";
$result = $conn->prepare($query);
$result->execute([$id]);
$result_row = $result->fetch();

$e_id = generateSanitize($result_row['e_id']);
$firstname = generateSanitize($result_row['firstName']);
$lastName = generateSanitize($result_row['lastName']);
$email = generateSanitize($result_row['email']);
// Employee Leave
$reason = generateSanitize($_POST['reason']);
$start = generateSanitize($_POST['start']);
$end = generateSanitize($_POST['end']);
$type = generateSanitize($_POST['type']);

$date1 = new DateTime($employee['start']);
$date2 = new DateTime($employee['end']);
$interval = $date1->diff($date2);

$params = array(
    'id' => $id,
    'token' => (int) 0,
    'start' => $start,
    'end' => $end,
    'reason' => $reason,
    'type' => $type,
    'status' => 'Pending'
);


$sql = "INSERT INTO `employee_leave`(`id`,`token`, `start`, `end`, `reason`, `type`, `status`) VALUES (:id, :token, :start, :end, :reason, :type, :status)";
$result = $conn->prepare($sql);
$result->execute($params);
if(sendmail($email, $reason, $type, $intervalday, $start, $end, $firstname, $lastName)){
    header("Location:../applyleave.php?msg=Updated and mailed.");
    exit();
}else{
    header("Location:../applyleave.php?msg=Updated and failed to send mail.");
    exit();
}
ob_end_flush();

function sendmail($email1, $reason1, $type1, $intervalday1, $start1, $end1, $firstname1, $lastName1){
    $fromEmail = $email1;
    $to = 'soumyadg@disseminare.com,praloy@disseminare.com,sanjoybose@disseminarebd.com,prashitasen@connectingdot.in';
    $subject = $type1;
    $headers .= 'From: '.$fromEmail."\r\n" . 'X-Mailer: PHP/' . phpversion();
    $message = "Dear Sir,\r\n I am writing to request you for a ".$intervalday1." leave from ".$start1." to ".$end1." as ".$reason1." I shall be reachable at my email ".$email1." \r\n Yours Sincerely,\r\n".$firstname1." ".$lastName1;
    $result = @mail($to, $subject, $message, $headers);
    return $result;
}
?>

