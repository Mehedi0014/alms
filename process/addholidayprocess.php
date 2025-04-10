<?php
    session_start();
    ob_start();
    require_once ('dbh.php');
    $date = generateSanitize($_POST['date']);
    $occasion = generateSanitize($_POST['occasion']);
    $city = generateSanitize($_POST['city']);
    $sql = "INSERT INTO `holiday`(`date`, `occasion`, `city`) VALUES(?, ?, ?)";
    $res = $conn->prepare($sql);
    $res->execute([$date, $occasion, $city]);
    ob_end_flush();
    header("Location:../holiday.php?");
    exit();
?>