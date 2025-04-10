<?php
    session_start();
    ob_start();
    include("dbh.php");
    $id = $_GET['id'];
    $sql = "UPDATE `employee_attendance` SET `status`='5' WHERE `id`= ?";
    $resulta = $conn->prepare($sql);
    $resulta->execute([$id]);
    ob_end_flush();
    header("Location:./../lateattend.php");
    exit;
?>