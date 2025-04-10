<?php
    session_start();
    ob_start();
    include("dbh.php");
    $id = generateSanitize($_GET['id']);
    $sql = "UPDATE `employee` SET `status`= ? WHERE id = ?";
    $result = $conn->prepare($sql);
    $result->execute(["1", $id]);
    ob_end_flush();
    header("Location:./../aloginwel.php?");
    exit();
?>